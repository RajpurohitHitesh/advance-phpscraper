<?php

namespace AdvancePHPSraper\Plugins\custom;

use AdvancePHPSraper\Core\Scraper;
use AdvancePHPSraper\Plugins\PluginInterface;
use Smalot\PdfParser\Parser;
use Exception;

/**
 * Document parsing plugin for PDFs
 */
class DocumentPlugin implements PluginInterface
{
    protected $config = [
        'max_size_mb' => 10,
        'validate_schema' => false,
    ];

    public function register(Scraper $scraper): void
    {
        if (!class_exists(Parser::class)) {
            throw new Exception('Smalot PDF Parser is not installed. Run: composer require smalot/pdfparser');
        }

        $scraper->parseDocument = function (string $url) use ($scraper) {
            try {
                $content = $scraper->fetchAsset($url);
                if (strlen($content) / 1024 / 1024 > $this->config['max_size_mb']) {
                    throw new Exception('Document exceeds max size limit');
                }

                $parser = new Parser();
                $pdf = $parser->parseContent($content);
                $text = $pdf->getText();

                $result = ['text' => $text];

                if ($this->config['validate_schema']) {
                    $result['schema_valid'] = $this->validateSchema($text);
                }

                return $result;
            } catch (Exception $e) {
                $scraper->getLogger()->error('Document parsing error: ' . $e->getMessage());
                return [];
            }
        };
    }

    protected function validateSchema(string $text): bool
    {
        // Placeholder for schema validation logic
        return true;
    }

    public function configure(array $config): self
    {
        $this->config = array_merge($this->config, $config);
        return $this;
    }

    public function getName(): string
    {
        return 'DocumentPlugin';
    }
}