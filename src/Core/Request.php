<?php

namespace AdvancePHPSraper\Core;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Request
{
    protected $client;
    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->client = new Client([
            'timeout' => $this->config->get('timeout'),
            'headers' => ['User-Agent' => $this->config->get('user_agent')],
        ]);
    }

    public function get(string $url, array $options = []): string
    {
        $retries = $this->config->get('max_retries', 3);
        while ($retries--) {
            try {
                return $this->client->get($url, $options)->getBody()->getContents();
            } catch (RequestException $e) {
                if ($retries === 0) {
                    throw $e;
                }
            }
        }
        return '';
    }
}