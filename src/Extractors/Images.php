<?php

namespace AdvancePHPSraper\Extractors;

use AdvancePHPSraper\Core\Extractor;
use Symfony\Component\DomCrawler\Crawler;
use Intervention\Image\ImageManagerStatic as Image;
use Exception;

class Images extends Extractor
{
    protected $filters = [];

    public function filterByAttribute(string $attribute, string $value): self
    {
        $this->filters['attribute'][$attribute] = $value;
        return $this;
    }

    public function filterByMinDimensions(int $minWidth, int $minHeight): self
    {
        $this->filters['dimensions'] = ['width' => $minWidth, 'height' => $minHeight];
        return $this;
    }

    public function extract(): array
    {
        if (!class_exists(Image::class)) {
            throw new Exception('Intervention Image is not installed. Run: composer require intervention/image');
        }

        if (!$this->crawler->filter('img')->count()) {
            return [];
        }

        $images = [];
        $this->crawler->filter('img')->each(function (Crawler $node) use (&$images) {
            $src = $node->attr('src') ?? $node->attr('data-src') ?? '';
            if (!$src) {
                return;
            }

            $width = (int)$node->attr('width') ?: null;
            $height = (int)$node->attr('height') ?: null;

            if (isset($this->filters['dimensions'])) {
                if (($width && $width < $this->filters['dimensions']['width']) ||
                    ($height && $height < $this->filters['dimensions']['height'])) {
                    return;
                }
            }

            if (isset($this->filters['attribute'])) {
                foreach ($this->filters['attribute'] as $attr => $value) {
                    if ($node->attr($attr) !== $value) {
                        return;
                    }
                }
            }

            $srcset = $node->attr('srcset') ? $this->parseSrcset($node->attr('srcset')) : [];
            $imageData = $this->processImage($src);

            $images[] = array_filter([
                'src' => $src,
                'alt' => $node->attr('alt'),
                'width' => $width,
                'height' => $height,
                'srcset' => $srcset,
                'title' => $node->attr('title'),
                'exif' => $imageData['exif'] ?? null,
                'thumbnail' => $imageData['thumbnail'] ?? null,
            ]);
        });

        return array_values($images);
    }

    protected function parseSrcset(string $srcset): array
    {
        $sources = [];
        foreach (explode(',', $srcset) as $source) {
            $parts = preg_split('/\s+/', trim($source));
            if (count($parts) >= 1) {
                $sources[] = [
                    'url' => $parts[0],
                    'descriptor' => $parts[1] ?? null,
                ];
            }
        }
        return $sources;
    }

    protected function processImage(string $url): array
    {
        try {
            $image = Image::make($url);
            $exif = $image->exif() ?? [];
            $thumbnail = (string)$image->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->encode('data-url');

            return [
                'exif' => array_filter($exif),
                'thumbnail' => $thumbnail,
            ];
        } catch (Exception $e) {
            return [];
        }
    }
}