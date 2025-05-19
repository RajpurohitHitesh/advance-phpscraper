<?php

namespace AdvancePHPSraper\Core;

use Symfony\Component\DomCrawler\Crawler;

abstract class Extractor
{
    protected $crawler;

    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    abstract public function extract(): array;
}