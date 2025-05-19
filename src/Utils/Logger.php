<?php

namespace AdvancePHPSraper\Utils;

use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;

class Logger
{
    protected $logger;

    public function __construct()
    {
        $this->logger = new MonologLogger('scraper');
        $this->logger->pushHandler(new StreamHandler('php://stderr', MonologLogger::DEBUG));
    }

    public function info(string $message): void
    {
        $this->logger->info($message);
    }

    public function error(string $message): void
    {
        $this->logger->error($message);
    }
}