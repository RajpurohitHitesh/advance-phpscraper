<?php

namespace AdvancePHPSraper\Core;

use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;
use AdvancePHPSraper\Plugins\PluginManager;
use AdvancePHPSraper\Extractors\Links;
use AdvancePHPSraper\Extractors\Images;
use AdvancePHPSraper\Extractors\Meta;
use AdvancePHPSraper\Extractors\StructuredData;
use AdvancePHPSraper\Extractors\Content;
use AdvancePHPSraper\Utils\Encoding;
use AdvancePHPSraper\Utils\Logger;
use AdvancePHPSraper\Queue\QueueManager;
use Symfony\Component\EventDispatcher\EventDispatcher;
use League\Uri\Uri;
use SimpleXMLElement;

class Scraper
{
    protected $client;
    protected $crawler;
    protected $config;
    protected $pluginManager;
    protected $dispatcher;
    protected $logger;
    protected $queueManager;
    protected $rateLimit = ['requests' => 10, 'per_second' => 1];
    protected $requestTimestamps = [];

    public function __construct(array $config = [])
    {
        $this->config = new Config($config);
        $this->client = new HttpBrowser(HttpClient::create(['timeout' => $this->config->get('timeout')]));
        $this->pluginManager = new PluginManager($this);
        $this->dispatcher = new EventDispatcher();
        $this->logger = new Logger();
        $this->queueManager = new QueueManager();
        $this->setUserAgent($this->config->get('user_agent'));
    }

    public function go(string $url, string $method = 'GET', array $params = []): self
    {
        $this->applyRateLimit();
        $this->crawler = $this->client->request($method, $url, $params);
        $this->crawler = new Crawler(Encoding::normalize($this->crawler->html()), $url);
        $this->dispatcher->dispatch(new \stdClass(), 'scraper.page_loaded');
        return $this;
    }

    public function queueUrls(array $urls, callable $callback = null): self
    {
        foreach ($urls as $url) {
            $this->queueManager->addJob($url, $callback);
        }
        return $this;
    }

    public function processQueue(): array
    {
        return $this->queueManager->process($this);
    }

    public function setRateLimit(int $requests, int $perSecond): self
    {
        $this->rateLimit = ['requests' => $requests, 'per_second' => $perSecond];
        return $this;
    }

    protected function applyRateLimit(): void
    {
        $now = microtime(true);
        $this->requestTimestamps = array_filter($this->requestTimestamps, fn($t) => $t > $now - 1);
        
        if (count($this->requestTimestamps) >= $this->rateLimit['requests']) {
            $sleep = (1 - ($now - min($this->requestTimestamps))) * 1000000;
            usleep($sleep);
        }
        
        $this->requestTimestamps[] = microtime(true);
    }

    public function getStatusCode(): int
    {
        return $this->client->getResponse()->getStatusCode();
    }

    public function isErrorPage(): bool
    {
        return $this->getStatusCode() >= 400;
    }

    public function title(): string
    {
        return $this->crawler->filter('title')->count() ? $this->crawler->filter('title')->text() : '';
    }

    public function links(): array
    {
        return (new Links($this->crawler, $this->crawler->getUri()))->extract();
    }

    public function images(): array
    {
        return (new Images($this->crawler))->extract();
    }

    public function meta(): array
    {
        return (new Meta($this->crawler))->extract();
    }

    public function structuredData(): array
    {
        return (new StructuredData($this->crawler))->extract();
    }

    public function content(): array
    {
        return (new Content($this->crawler))->extract();
    }

    public function sitemap(): array
    {
        $sitemapUrl = $this->getSitemapUrl();
        if (!$sitemapUrl) {
            return [];
        }

        try {
            $content = $this->fetchAsset($sitemapUrl);
            $xml = new SimpleXMLElement($content);
            $urls = [];

            foreach ($xml->url as $url) {
                $urls[] = [
                    'loc' => (string)$url->loc,
                    'lastmod' => (string)$url->lastmod ?? null,
                    'changefreq' => (string)$url->changefreq ?? null,
                    'priority' => (string)$url->priority ?? null,
                ];
            }

            return $urls;
        } catch (\Exception $e) {
            $this->logger->error('Sitemap parsing error: ' . $e->getMessage());
            return [];
        }
    }

    public function rssFeed(): array
    {
        $feedUrls = $this->crawler->filter('link[type="application/rss+xml"]')->extract(['href']);
        $feeds = [];

        foreach ($feedUrls as $url) {
            try {
                $content = $this->fetchAsset($url);
                $xml = new SimpleXMLElement($content);
                $items = [];

                foreach ($xml->channel->item as $item) {
                    $items[] = [
                        'title' => (string)$item->title,
                        'link' => (string)$item->link,
                        'description' => (string)$item->description,
                        'pubDate' => (string)$item->pubDate ?? null,
                    ];
                }

                $feeds[] = [
                    'url' => $url,
                    'title' => (string)$xml->channel->title,
                    'items' => $items,
                ];
            } catch (\Exception $e) {
                $this->logger->error('RSS parsing error: ' . $e->getMessage());
            }
        }

        return $feeds;
    }

    public function getSitemapUrl(): ?string
    {
        $robotsUrl = rtrim($this->crawler->getUri(), '/') . '/robots.txt';
        try {
            $content = $this->fetchAsset($robotsUrl);
            if (preg_match('/Sitemap:\s*(.+)/i', $content, $match)) {
                return trim($match[1]);
            }
        } catch (\Exception $e) {
            $this->logger->error('Robots.txt parsing error: ' . $e->getMessage());
        }
        return null;
    }

    public function apiRequest(string $endpoint, array $params = [], string $method = 'GET'): array
    {
        try {
            $response = $this->client->request($method, $endpoint, [], [], ['HTTP_CONTENT_TYPE' => 'application/json'], $params ? json_encode($params) : null);
            $content = $this->client->getResponse()->getContent();
            return json_decode($content, true) ?? [];
        } catch (\Exception $e) {
            $this->logger->error('API request error: ' . $e->getMessage());
            return [];
        }
    }

    public function filter(string $selector): Crawler
    {
        return $this->crawler->filter($selector);
    }

    public function fetchAsset(string $url): string
    {
        $this->applyRateLimit();
        $this->client->request('GET', Uri::createFromString($url));
        return $this->client->getResponse()->getContent();
    }

    public function parseCsv(string $content, bool $useFirstRowAsHeaders = true): array
    {
        $lines = explode("\n", $content);
        $data = array_map('str_getcsv', $lines);
        if ($useFirstRowAsHeaders && !empty($data)) {
            $headers = array_shift($data);
            return array_map(function ($row) use ($headers) {
                return array_combine($headers, array_pad($row, count($headers), null));
            }, $data);
        }
        return $data;
    }

    public function parseJson(string $content): array
    {
        return json_decode($content, true) ?? [];
    }

    public function parseXml(string $content): \SimpleXMLElement
    {
        return simplexml_load_string($content);
    }

    public function setUserAgent(string $userAgent): self
    {
        $this->client->setServerParameter('HTTP_USER_AGENT', $userAgent);
        return $this;
    }

    public function setTimeout(int $timeout): self
    {
        $this->client->getInternalRequest()->setTimeout($timeout);
        return $this;
    }

    public function setFollowRedirects(bool $follow): self
    {
        $this->client->followRedirects($follow);
        return $this;
    }

    public function getPluginManager(): PluginManager
    {
        return $this->pluginManager;
    }

    public function getCrawler(): Crawler
    {
        return $this->crawler;
    }

    public function getDispatcher(): EventDispatcher
    {
        return $this->dispatcher;
    }

    public function getLogger(): Logger
    {
        return $this->logger;
    }
}