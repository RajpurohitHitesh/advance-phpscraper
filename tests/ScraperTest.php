<?php

namespace AdvancePHPSraper\Tests;

use AdvancePHPSraper\Core\Scraper;
use PHPUnit\Framework\TestCase;

class ScraperTest extends TestCase
{
    protected $scraper;

    protected function setUp(): void
    {
        $this->scraper = new Scraper();
    }

    public function testGoAndExtractLinks(): void
    {
        $this->scraper->go('https://example.com');
        $links = $this->scraper->links();
        $this->assertIsArray($links);
        $this->assertNotEmpty($links);
    }

    public function testStatusCode(): void
    {
        $this->scraper->go('https://example.com');
        $this->assertEquals(200, $this->scraper->getStatusCode());
    }

    public function testSitemapParsing(): void
    {
        $this->scraper->go('https://example.com');
        $sitemap = $this->scraper->sitemap();
        $this->assertIsArray($sitemap);
    }

    public function testRssFeedParsing(): void
    {
        $this->scraper->go('https://example.com');
        $rss = $this->scraper->rssFeed();
        $this->assertIsArray($rss);
    }

    public function testRateLimit(): void
    {
        $this->scraper->setRateLimit(2, 1);
        $start = microtime(true);
        $this->scraper->go('https://example.com');
        $this->scraper->go('https://example.com');
        $this->scraper->go('https://example.com');
        $duration = microtime(true) - $start;
        $this->assertGreaterThan(1, $duration);
    }

    public function testApiRequest(): void
    {
        $result = $this->scraper->apiRequest('https://jsonplaceholder.typicode.com/posts/1');
        $this->assertIsArray($result);
    }
}