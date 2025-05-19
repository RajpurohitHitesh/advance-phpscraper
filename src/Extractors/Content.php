<?php

namespace AdvancePHPSraper\Extractors;

use AdvancePHPSraper\Core\Extractor;
use Symfony\Component\DomCrawler\Crawler;
use DonatelloZa\RakePlus\RakePlus;

class Content extends Extractor
{
    protected $filters = [];
    protected $keywordCache = null;

    public function filterByType(string $type): self
    {
        $this->filters['type'] = $type; // e.g., 'headings', 'paragraphs', 'lists', 'tables'
        return $this;
    }

    public function extract(): array
    {
        $content = [
            'headings' => [],
            'paragraphs' => [],
            'lists' => [],
            'tables' => [],
            'keywords' => [],
            'outline' => [],
            'keyword_density' => [],
        ];

        if (!isset($this->filters['type']) || $this->filters['type'] === 'headings') {
            $this->crawler->filter('h1,h2,h3,h4,h5,h6')->each(function (Crawler $node) use (&$content) {
                $content['headings'][] = [
                    'tag' => $node->nodeName(),
                    'text' => trim($node->text()),
                    'level' => (int)substr($node->nodeName(), 1),
                ];
            });
        }

        if (!isset($this->filters['type']) || $this->filters['type'] === 'paragraphs') {
            $this->crawler->filter('p')->each(function (Crawler $node) use (&$content) {
                $text = trim($node->text());
                if ($text) {
                    $content['paragraphs'][] = $text;
                }
            });
        }

        if (!isset($this->filters['type']) || $this->filters['type'] === 'lists') {
            $this->crawler->filter('ul,ol')->each(function (Crawler $node) use (&$content) {
                $items = [];
                $node->filter('li')->each(function (Crawler $item) use (&$items) {
                    $items[] = trim($item->text());
                });
                if ($items) {
                    $content['lists'][] = [
                        'type' => $node->nodeName(),
                        'items' => $items,
                    ];
                }
            });
        }

        if (!isset($this->filters['type']) || $this->filters['type'] === 'tables') {
            $this->crawler->filter('table')->each(function (Crawler $node) use (&$content) {
                $rows = [];
                $node->filter('tr')->each(function (Crawler $row) use (&$rows) {
                    $cells = [];
                    $row->filter('th,td')->each(function (Crawler $cell) use (&$cells) {
                        $cells[] = trim($cell->text());
                    });
                    if ($cells) {
                        $rows[] = $cells;
                    }
                });
                if ($rows) {
                    $content['tables'][] = $rows;
                }
            });
        }

        if (!isset($this->filters['type']) || $this->filters['type'] === 'keywords') {
            if ($this->keywordCache === null) {
                $text = $this->crawler->text();
                $this->keywordCache = RakePlus::create($text)->keywords();
            }
            $content['keywords'] = $this->keywordCache;

            $words = array_count_values(array_map('strtolower', explode(' ', preg_replace('/[^\w\s]/', '', $text))));
            $totalWords = array_sum($words);
            $content['keyword_density'] = array_map(function ($count) use ($totalWords) {
                return ['count' => $count, 'density' => $count / $totalWords * 100];
            }, $words);
        }

        if (!isset($this->filters['type']) || $this->filters['type'] === 'outline') {
            $content['outline'] = array_map(function ($heading) {
                return ['level' => $heading['level'], 'text' => $heading['text']];
            }, $content['headings']);
        }

        return array_filter($content);
    }
}