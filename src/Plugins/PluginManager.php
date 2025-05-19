<?php

namespace AdvancePHPSraper\Plugins;

use AdvancePHPSraper\Core\Scraper;

class PluginManager
{
    protected $scraper;
    protected $plugins = [];
    protected $configFile = __DIR__ . '/../../plugins.json';

    public function __construct(Scraper $scraper)
    {
        $this->scraper = $scraper;
        $this->loadConfig();
    }

    public function registerPlugin(PluginInterface $plugin): void
    {
        $plugin->register($this->scraper);
        $this->plugins[$plugin->getName()] = $plugin;
    }

    public function enablePlugin(string $pluginName): void
    {
        $config = $this->getConfig();
        $config['plugins'][$pluginName] = ['enabled' => true];
        $this->saveConfig($config);
        $class = "AdvancePHPSraper\\Plugins\\custom\\{$pluginName}";
        if (class_exists($class)) {
            $this->registerPlugin(new $class());
        }
    }

    public function disablePlugin(string $pluginName): void
    {
        $config = $this->getConfig();
        $config['plugins'][$pluginName] = ['enabled' => false];
        $this->saveConfig($config);
        unset($this->plugins[$pluginName]);
    }

    public function getPlugins(): array
    {
        return $this->plugins;
    }

    protected function loadConfig(): void
    {
        if (file_exists($this->configFile)) {
            $config = json_decode(file_get_contents($this->configFile), true);
            foreach ($config['plugins'] ?? [] as $pluginName => $settings) {
                if ($settings['enabled']) {
                    $class = "AdvancePHPSraper\\Plugins\\custom\\{$pluginName}";
                    if (class_exists($class)) {
                        $this->registerPlugin(new $class());
                    }
                }
            }
        }
    }

    protected function getConfig(): array
    {
        return file_exists($this->configFile) ? json_decode(file_get_contents($this->configFile), true) : ['plugins' => []];
    }

    protected function saveConfig(array $config): void
    {
        file_put_contents($this->configFile, json_encode($config, JSON_PRETTY_PRINT));
    }
}