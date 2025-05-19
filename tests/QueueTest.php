<?php

namespace AdvancePHPSraper\Tests;

use AdvancePHPSraper\Core\Scraper;
use AdvancePHPSraper\Queue\QueueManager;
use PHPUnit\Framework\TestCase;

class QueueTest extends TestCase
{
    protected $scraper;

    protected function setUp(): void
    {
        $this->scraper = new Scraper();
    }

    public function testQueueUrlsAndProcess(): void
    {
        $urls = ['https://example.com', 'https://example.org'];
        $callback = fn($crawler) => $crawler->filter('title')->text();

        $this->scraper->queueUrls($urls, $callback);
        $results = $this->scraper->processQueue();

        $this->assertIsArray($results);
        $this->assertCount(2, $results);
        $this->assertArrayHasKey('https://example.com', $results);
    }

    public function testEmptyQueue(): void
    {
        $results = $this->scraper->processQueue();
        $this->assertIsArray($results);
        $this->assertEmpty($results);
    }

    public function testQueueWithFailedUrl(): void
    {
        $urls = ['https://nonexistent.example.com'];
        $this->scraper->queueUrls($urls);
        $results = $this->scraper->processQueue();
        $this->assertIsArray($results);
        $this->assertArrayHasKey('https://nonexistent.example.com', $results);
        $this->assertNull($results['https://nonexistent.example.com']);
    }
}