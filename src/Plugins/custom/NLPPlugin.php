<?php

namespace AdvancePHPSraper\Plugins\custom;

use AdvancePHPSraper\Core\Scraper;
use AdvancePHPSraper\Plugins\PluginInterface;
use DonatelloZa\RakePlus\RakePlus;
use Exception;

/**
 * NLP plugin for keyword and entity extraction
 */
class NLPPlugin implements PluginInterface
{
    protected $config = [
        'min_keyword_score' => 1.5,
        'max_keywords' => 20,
    ];

    public function register(Scraper $scraper): void
    {
        $scraper->extractEntities = function () use ($scraper): array {
            try {
                $text = $scraper->getCrawler()->text();
                $rake = RakePlus::create($text);
                $keywords = $rake->sortByScore()->keywords();

                $results = [];
                $wordCount = str_word_count(strtolower($text));
                
                foreach (array_slice($keywords, 0, $this->config['max_keywords']) as $keyword) {
                    if ($rake->getScore($keyword) >= $this->config['min_keyword_score']) {
                        $count = substr_count(strtolower($text), strtolower($keyword));
                        $results[] = [
                            'keyword' => $keyword,
                            'score' => $rake->getScore($keyword),
                            'density' => ($count / $wordCount) * 100,
                        ];
                    }
                }

                return $results;
            } catch (Exception $e) {
                $scraper->getLogger()->error('NLP processing error: ' . $e->getMessage());
                return [];
            }
        };
    }

    public function configure(array $config): self
    {
        $this->config = array_merge($this->config, $config);
        return $this;
    }

    public function getName(): string
    {
        return 'NLPPlugin';
    }
}