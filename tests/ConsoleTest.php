<?php

namespace AdvancePHPSraper\Tests;

use AdvancePHPSraper\Console\ScrapeCommand;
use Symfony\Component\Console\Tester\CommandTester;
use PHPUnit\Framework\TestCase;

class ConsoleTest extends TestCase
{
    public function testScrapeCommand(): void
    {
        $command = new ScrapeCommand();
        $tester = new CommandTester($command);
        $tester->execute([
            'url' => 'https://example.com',
            '--extract' => 'links,meta',
        ]);

        $output = $tester->getDisplay();
        $this->assertStringContainsString('"links":', $output);
        $this->assertStringContainsString('"meta":', $output);
        $this->assertEquals(0, $tester->getStatusCode());
    }

    public function testInvalidExtractType(): void
    {
        $command = new ScrapeCommand();
        $tester = new CommandTester($command);
        $tester->execute([
            'url' => 'https://example.com',
            '--extract' => 'invalid',
        ]);

        $this->assertEquals(1, $tester->getStatusCode());
        $this->assertStringContainsString('Invalid extract type', $tester->getDisplay());
    }

    public function testSitemapExtraction(): void
    {
        $command = new ScrapeCommand();
        $tester = new CommandTester($command);
        $tester->execute([
            'url' => 'https://example.com',
            '--extract' => 'sitemap',
        ]);

        $output = $tester->getDisplay();
        $this->assertStringContainsString('"sitemap":', $output);
        $this->assertEquals(0, $tester->getStatusCode());
    }
}