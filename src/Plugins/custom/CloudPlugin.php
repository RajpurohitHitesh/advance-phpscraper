<?php

namespace AdvancePHPSraper\Plugins\custom;

use AdvancePHPSraper\Core\Scraper;
use AdvancePHPSraper\Plugins\PluginInterface;
use Aws\Lambda\LambdaClient;
use Exception;

/**
 * Cloud deployment plugin for AWS Lambda
 */
class CloudPlugin implements PluginInterface
{
    protected $config = [
        'provider' => 'aws',
        'region' => 'us-east-1',
        'function_name' => 'scraper',
        'credentials' => [
            'key' => null,
            'secret' => null,
        ],
    ];

    public function register(Scraper $scraper): void
    {
        if (!class_exists(LambdaClient::class)) {
            throw new Exception('AWS SDK is not installed. Run: composer require aws/aws-sdk-php');
        }

        $scraper->deployToCloud = function (string $url, array $params = []) use ($scraper) {
            try {
                $client = new LambdaClient([
                    'region' => $this->config['region'],
                    'version' => 'latest',
                    'credentials' => [
                        'key' => $this->config['credentials']['key'],
                        'secret' => $this->config['credentials']['secret'],
                    ],
                ]);

                $payload = array_merge(['url' => $url], $params);
                $result = $client->invoke([
                    'FunctionName' => $this->config['function_name'],
                    'InvocationType' => 'RequestResponse',
                    'Payload' => json_encode($payload),
                ]);

                $response = json_decode($result['Payload']->getContents(), true);
                if (isset($response['errorMessage'])) {
                    throw new Exception($response['errorMessage']);
                }

                return $response['body'] ?? [];
            } catch (Exception $e) {
                $scraper->getLogger()->error('Cloud deployment error: ' . $e->getMessage());
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
        return 'CloudPlugin';
    }
}