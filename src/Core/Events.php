<?php

namespace AdvancePHPSraper\Core;

use Symfony\Component\EventDispatcher\Event;

class PageLoadedEvent extends Event
{
    public const NAME = 'scraper.page_loaded';
}

class DataExtractedEvent extends Event
{
    public const NAME = 'scraper.data_extracted';
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getData(): array
    {
        return $this->data;
    }
}