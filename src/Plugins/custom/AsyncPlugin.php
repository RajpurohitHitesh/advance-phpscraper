<?php

namespace AdvancePHPSraper\Plugins\custom;

use AdvancePHPSraper\Core\Scraper;
use AdvancePHPSraper\Plugins\PluginInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Asynchronous scraping plugin using Guzzle Promises
 */
class AsyncPlugin implements PluginInterface
{
    protected $config = [
        'max_concurrent' => 10,
        'timeout' => 30,
        'proxy' => null,
        'rate_limit' => ['requests' => 10, 'per_second' => 1],
    ];

    public function register(Scraper $scraper): void
    {
        $client = new Client([
            'timeout' => $this->config['timeout'],
            'proxy' => $this->config['proxy'],
        ]);

        $scraper->goAsync = function (array $urls) use ($scraper, $client): array {
            try {
                $promises = [];
                $urls = array_slice($urls, 0, $this->config['max_concurrent']);
                $requestTimestamps = [];
                $now = microtime(true);

                foreach ($urls as $url) {
                    $requestTimestamps = array_filter($requestTimestamps, fn($t) => $t > $now - 1);
                    if (count($requestTimestamps) >= $this->config['rate_limit']['requests']) {
                        $sleep = (1 - ($now - min($requestTimestamps))) * 1000000;
                        usleep($sleep);
                        $now = microtime(true);
                    }
                    $requestTimestamps[] = $now;
                    $promises[$url] = $client->requestAsync('GET', $url);
                }

                $results = Promise\Utils::settle($promises)->wait();
                $crawlers = [];

                foreach ($results as $url => $result) {
                    if ($result['state'] === 'fulfilled') {
                        $content = $result['value']->getBody()->getContents();
                        $crawlers[$url] = new Crawler($content, $url);
                    } else {
                        $scraper->getLogger()->error('Async request failed for ' . $url . ': ' . $result['reason']);
                    }
                }

                return $crawlers;
            } catch (RequestException $e) {
                $scraper->getLogger()->error('Async scraping error: ' . $e->getMessage());
                return [];
            }
        };
    }

    public function configure(array $config): self
    {
        $this->config = array_merge($this->config, $config);
        return $this;
    }

    public function getName(): string
    {
        return 'AsyncPlugin';
    }
}