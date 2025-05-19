<?php

namespace AdvancePHPSraper\Plugins\custom;

use AdvancePHPSraper\Core\Scraper;
use AdvancePHPSraper\Plugins\PluginInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\DomCrawler\Crawler;
use Exception;

/**
 * Caching plugin using Symfony Cache
 */
class CachePlugin implements PluginInterface
{
    protected $config = [
        'ttl' => 3600,
        'cache_dir' => 'cache',
    ];

    public function register(Scraper $scraper): void
    {
        if (!class_exists(FilesystemAdapter::class)) {
            throw new Exception('Symfony Cache is not installed. Run: composer require symfony/cache');
        }

        $cache = new FilesystemAdapter('', $this->config['ttl'], $this->config['cache_dir']);

        $originalGo = fn(string $url, string $method = 'GET', array $params = []) => $scraper->go($url, $method, $params);
        $scraper->enableCache = function () use ($scraper, $cache, $originalGo) {
            $scraper->go = function (string $url, string $method = 'GET', array $params = []) use ($scraper, $cache, $originalGo) {
                try {
                    // Use SHA-256 for secure cache key generation
                    $cacheKey = hash('sha256', $url . $method . serialize($params));
                    $item = $cache->getItem($cacheKey);

                    if ($item->isHit()) {
                        $scraper->crawler = new Crawler($item->get(), $url);
                    } else {
                        $scraper->crawler = $originalGo($url, $method, $params)->getCrawler();
                        $item->set($scraper->crawler->html());
                        $cache->save($item);
                    }

                    $scraper->crawler = new Crawler(\AdvancePHPSraper\Utils\Encoding::normalize($scraper->crawler->html()), $url);
                    $scraper->getDispatcher()->dispatch(new \stdClass(), 'scraper.page_loaded');
                    return $scraper;
                } catch (Exception $e) {
                    $scraper->getLogger()->error('Cache error: ' . $e->getMessage());
                    return $originalGo($url, $method, $params);
                }
            };
        };

        $scraper->clearCache = function () use ($cache) {
            $cache->clear();
        };
    }

    public function configure(array $config): self
    {
        $this->config = array_merge($this->config, $config);
        return $this;
    }

    public function getName(): string
    {
        return 'CachePlugin';
    }
}