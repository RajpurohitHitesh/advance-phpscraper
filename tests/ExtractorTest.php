<?php

namespace AdvancePHPSraper\Tests;

use AdvancePHPSraper\Core\Scraper;
use AdvancePHPSraper\Extractors\Links;
use AdvancePHPSraper\Extractors\Images;
use AdvancePHPSraper\Extractors\Meta;
use AdvancePHPSraper\Extractors\StructuredData;
use AdvancePHPSraper\Extractors\Content;
use Symfony\Component\DomCrawler\Crawler;
use PHPUnit\Framework\TestCase;

class ExtractorTest extends TestCase
{
    protected $scraper;

    protected function setUp(): void
    {
        $this->scraper = new Scraper();
        $this->scraper->go('https://example.com');
    }

    public function testLinksExtractor(): void
    {
        $extractor = new Links($this->scraper->getCrawler(), 'https://example.com');
        $links = $extractor->filterByAttribute('rel', 'nofollow')->extract();
        $this->assertIsArray($links);
    }

    public function testImagesExtractor(): void
    {
        $extractor = new Images($this->scraper->getCrawler());
        $images = $extractor->filterByMinDimensions(100, 100)->extract();
        $this->assertIsArray($images);
    }

    public function testMetaExtractor(): void
    {
        $extractor = new Meta($this->scraper->getCrawler());
        $meta = $extractor->filterByType('og')->extract();
        $this->assertIsArray($meta);
    }

    public function testStructuredDataExtractor(): void
    {
        $extractor = new StructuredData($this->scraper->getCrawler());
        $data = $extractor->filterBySchemaType('Article')->extract();
        $this->assertIsArray($data);
    }

    public function testContentExtractor(): void
    {
        $extractor = new Content($this->scraper->getCrawler());
        $content = $extractor->filterByType('keywords')->extract();
        $this->assertIsArray($content);
        $this->assertArrayHasKey('keywords', $content);
    }
}