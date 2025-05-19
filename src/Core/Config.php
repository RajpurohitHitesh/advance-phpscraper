<?php

namespace AdvancePHPSraper\Core;

class Config
{
    protected $settings = [
        'user_agent' => 'Mozilla/5.0 (compatible; AdvancePHPSraper/1.0; +https://github.com/rajpurohithitesh/advance-phpscraper)',
        'timeout' => 30,
        'follow_redirects' => true,
        'max_retries' => 3,
    ];

    public function __construct(array $config = [])
    {
        $this->settings = array_merge($this->settings, $config);
    }

    public function get(string $key, $default = null)
    {
        return $this->settings[$key] ?? $default;
    }

    public function set(string $key, $value): self
    {
        $this->settings[$key] = $value;
        return $this;
    }
}