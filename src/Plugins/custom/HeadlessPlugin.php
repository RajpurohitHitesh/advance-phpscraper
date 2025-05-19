<?php

namespace AdvancePHPSraper\Plugins\custom;

use AdvancePHPSraper\Core\Scraper;
use AdvancePHPSraper\Plugins\PluginInterface;
use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\PantherTestCase;
use Exception;

/**
 * Headless browser plugin using Symfony Panther
 */
class HeadlessPlugin implements PluginInterface
{
    protected $config = [
        'browser' => 'chrome',
        'headless' => true,
        'timeout' => 30,
        'window_size' => [1920, 1080],
    ];

    public function register(Scraper $scraper): void
    {
        if (!class_exists(Client::class)) {
            throw new Exception('Symfony Panther is not installed. Run: composer require symfony/panther');
        }

        $scraper->getDispatcher()->addListener('scraper.page_loaded', function () use ($scraper) {
            try {
                $client = Client::createChromeClient(null, null, [
                    '--headless' => $this->config['headless'],
                    '--window-size' => implode(',', $this->config['window_size']),
                ]);

                $client->request('GET', $scraper->getCrawler()->getUri());
                $scraper->getCrawler()->add($client->getCrawler()->getNode(0));
                $client->quit();
            } catch (Exception $e) {
                $scraper->getLogger()->error('Headless browser error: ' . $e->getMessage());
            }
        });
    }

    public function configure(array $config): self
    {
        $this->config = array_merge($this->config, $config);
        return $this;
    }

    public function getName(): string
    {
        return 'HeadlessPlugin';
    }
}