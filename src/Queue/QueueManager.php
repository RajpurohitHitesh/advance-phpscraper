<?php

namespace AdvancePHPSraper\Queue;

use AdvancePHPSraper\Core\Scraper;

class QueueManager
{
    protected $jobs = [];

    public function addJob(string $url, callable $callback = null): void
    {
        $this->jobs[] = ['url' => $url, 'callback' => $callback];
    }

    public function process(Scraper $scraper): array
    {
        $results = [];
        foreach ($this->jobs as $job) {
            try {
                $scraper->go($job['url']);
                $result = $job['callback'] ? call_user_func($job['callback'], $scraper->getCrawler()) : $scraper->getCrawler();
                $results[$job['url']] = $result;
            } catch (\Exception $e) {
                $scraper->getLogger()->error('Queue job error for ' . $job['url'] . ': ' . $e->getMessage());
                $results[$job['url']] = null;
            }
        }
        $this->jobs = [];
        return $results;
    }
}