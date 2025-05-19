<?php

namespace AdvancePHPSraper\Plugins;

use AdvancePHPSraper\Core\Scraper;

interface PluginInterface
{
    public function register(Scraper $scraper): void;
    public function getName(): string;
}