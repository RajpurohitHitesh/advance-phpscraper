#!/usr/bin/env php
<?php

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Console\Application;
use AdvancePHPSraper\Console\ScrapeCommand;

$app = new Application('Advance PHP Scraper', '1.0.0');
$app->add(new ScrapeCommand());
$app->run();