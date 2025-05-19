<?php

namespace AdvancePHPSraper\Extractors;

use AdvancePHPSraper\Core\Extractor;
use Symfony\Component\DomCrawler\Crawler;
use League\Uri\Uri;
use League\Uri\UriResolver;

class Links extends Extractor
{
    protected $filters = [];
    protected $baseUri;

    public function __construct(Crawler $crawler, string $baseUri = '')
    {
        parent::__construct($crawler);
        $this->baseUri = $baseUri ? Uri::createFromString($baseUri) : null;
    }

    public function filterByAttribute(string $attribute, string $value): self
    {
        $this->filters['attribute'][$attribute] = $value;
        return $this;
    }

    public function filterByUrlRegex(string $pattern): self
    {
        $this->filters['url_regex'] = $pattern;
        return $this;
    }

    public function extract(): array
    {
        if (!$this->crawler->filter('a')->count()) {
            return [];
        }

        $links = [];
        $this->crawler->filter('a')->each(function (Crawler $node) use (&$links) {
            $href = $node->attr('href') ?? '';
            if (!$href) {
                return;
            }

            $uri = Uri::createFromString($href);
            if ($this->baseUri) {
                $uri = UriResolver::resolve($uri, $this->baseUri);
            }

            if (isset($this->filters['url_regex']) && !preg_match($this->filters['url_regex'], (string)$uri)) {
                return;
            }

            $rel = $node->attr('rel') ?? '';
            $attributes = array_filter([
                'rel' => $rel,
                'class' => $node->attr('class'),
                'title' => $node->attr('title'),
            ]);

            if (isset($this->filters['attribute'])) {
                foreach ($this->filters['attribute'] as $attr => $value) {
                    if ($node->attr($attr) !== $value) {
                        return;
                    }
                }
            }

            $links[] = [
                'href' => (string)$uri,
                'text' => trim($node->text()),
                'protocol' => $uri->getScheme(),
                'is_nofollow' => strpos($rel, 'nofollow') !== false,
                'is_ugc' => strpos($rel, 'ugc') !== false,
                'is_sponsored' => strpos($rel, 'sponsored') !== false,
                'attributes' => $attributes,
            ];
        });

        return array_values($links);
    }
}