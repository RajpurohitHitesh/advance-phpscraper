<?php

namespace AdvancePHPSraper\Extractors;

use AdvancePHPSraper\Core\Extractor;
use Symfony\Component\DomCrawler\Crawler;

class Meta extends Extractor
{
    protected $filters = [];

    public function filterByType(string $type): self
    {
        $this->filters['type'] = $type; // e.g., 'og', 'twitter', 'standard'
        return $this;
    }

    public function extract(): array
    {
        if (!$this->crawler->filter('meta')->count()) {
            return [];
        }

        $meta = [
            'standard' => [],
            'og' => [],
            'twitter' => [],
            'charset' => null,
            'viewport' => null,
        ];

        $this->crawler->filter('meta')->each(function (Crawler $node) use (&$meta) {
            $name = $node->attr('name') ?? $node->attr('property') ?? '';
            $content = $node->attr('content') ?? '';

            if (!$name && $node->attr('charset')) {
                $meta['charset'] = $node->attr('charset');
                return;
            }

            if (!$name && $node->attr('name') === 'viewport') {
                $meta['viewport'] = $content;
                return;
            }

            if (!$name || !$content) {
                return;
            }

            $type = 'standard';
            if (strpos($name, 'og:') === 0) {
                $type = 'og';
            } elseif (strpos($name, 'twitter:') === 0) {
                $type = 'twitter';
            }

            if (isset($this->filters['type']) && $this->filters['type'] !== $type) {
                return;
            }

            $meta[$type][$name] = $content;
        });

        return array_filter($meta);
    }
}