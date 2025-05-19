<?php

namespace AdvancePHPSraper\Extractors;

use AdvancePHPSraper\Core\Extractor;
use Symfony\Component\DomCrawler\Crawler;

class StructuredData extends Extractor
{
    protected $filters = [];

    public function filterBySchemaType(string $type): self
    {
        $this->filters['schema_type'] = $type;
        return $this;
    }

    public function extract(): array
    {
        $data = [];

        // JSON-LD
        $this->crawler->filter('script[type="application/ld+json"]')->each(function (Crawler $node) use (&$data) {
            $json = json_decode($node->text(), true);
            if ($json && !json_last_error()) {
                $data['json-ld'][] = $this->filterBySchema($json);
            }
        });

        // Microdata
        $microdata = $this->extractMicrodata();
        if ($microdata) {
            $data['microdata'] = $this->filterBySchema($microdata);
        }

        // RDFa
        $this->crawler->filter('[property]')->each(function (Crawler $node) use (&$data) {
            $property = $node->attr('property');
            $content = $node->attr('content') ?? $node->text();
            if ($property && $content) {
                $data['rdfa'][] = ['property' => $property, 'content' => $content];
            }
        });

        return array_filter($data);
    }

    protected function extractMicrodata(): array
    {
        $items = [];
        $this->crawler->filter('[itemscope]')->each(function (Crawler $node) use (&$items) {
            $item = [];
            if ($type = $node->attr('itemtype')) {
                $item['@type'] = $type;
            }
            $node->filter('[itemprop]')->each(function (Crawler $propNode) use (&$item) {
                $propName = $propNode->attr('itemprop');
                $propValue = $propNode->attr('content') ?? $propNode->text();
                $item[$propName] = $propValue;
            });
            $items[] = $item;
        });
        return $items;
    }

    protected function filterBySchema(array $items): array
    {
        if (!isset($this->filters['schema_type'])) {
            return $items;
        }

        $filtered = [];
        foreach ($items as $item) {
            if (isset($item['@type']) && $item['@type'] === $this->filters['schema_type']) {
                $filtered[] = $item;
            }
        }
        return $filtered;
    }
}