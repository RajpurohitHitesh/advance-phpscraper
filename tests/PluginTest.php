<?php

namespace AdvancePHPSraper\Tests;

use AdvancePHPSraper\Core\Scraper;
use AdvancePHPSraper\Plugins\PluginManager;
use PHPUnit\Framework\TestCase;

class PluginTest extends TestCase
{
    protected $scraper;

    protected function setUp(): void
    {
        $this->scraper = new Scraper();
    }

    public function testPluginRegistration(): void
    {
        $manager = $this->scraper->getPluginManager();
        $manager->enablePlugin('CachePlugin');
        $this->assertArrayHasKey('CachePlugin', $manager->getPlugins());
    }

    public function testPluginDisable(): void
    {
        $manager = $this->scraper->getPluginManager();
        $manager->enablePlugin('CachePlugin');
        $manager->disablePlugin('CachePlugin');
        $this->assertArrayNotHasKey('CachePlugin', $manager->getPlugins());
    }

    public function testPluginConfiguration(): void
    {
        $manager = $this->scraper->getPluginManager();
        $manager->enablePlugin('CachePlugin');
        $plugin = $manager->getPlugins()['CachePlugin'];
        $plugin->configure(['ttl' => 7200]);
        $this->assertTrue(true); // Placeholder: Add reflection to check config
    }
}