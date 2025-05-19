<?php

namespace AdvancePHPSraper\Console;

use AdvancePHPSraper\Core\Scraper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ScrapeCommand extends Command
{
    protected static $defaultName = 'scrape';

    protected function configure(): void
    {
        $this->setDescription('Scrape a website and extract data')
            ->addArgument('url', InputArgument::REQUIRED, 'The URL to scrape')
            ->addOption('extract', 'e', InputOption::VALUE_REQUIRED, 'Data to extract (links,images,meta,content,sitemap,rss)', 'links');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $scraper = new Scraper();
        $url = $input->getArgument('url');
        $extract = explode(',', $input->getOption('extract'));

        try {
            $scraper->go($url);
            $results = [];

            foreach ($extract as $type) {
                $method = match ($type) {
                    'links' => 'links',
                    'images' => 'images',
                    'meta' => 'meta',
                    'content' => 'content',
                    'sitemap' => 'sitemap',
                    'rss' => 'rssFeed',
                    default => throw new \Exception('Invalid extract type: ' . $type),
                };
                $results[$type] = $scraper->$method();
            }

            $output->writeln(json_encode($results, JSON_PRETTY_PRINT));
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}