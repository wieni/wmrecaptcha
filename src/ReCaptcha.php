<?php

namespace Drupal\wmrecaptcha;

use Drupal\Core\Config\ConfigFactoryInterface;
use GuzzleHttp\Client;

class ReCaptcha
{
    /** @var ConfigFactoryInterface */
    protected $configFactory;
    /** @var Client */
    protected $client;
    /** @var string */
    protected $siteKey;
    /** @var string */
    protected $secretKey;

    public function __construct(
        Client $client,
        ?string $siteKey,
        ?string $secretKey
    ) {
        $this->client = $client;
        $this->siteKey = $siteKey;
        $this->secretKey = $secretKey;
    }

    public function setConfigFactory(ConfigFactoryInterface $configFactory)
    {
        $this->configFactory = $configFactory;

        return $this;
    }

    public function getSiteKey(): ?string
    {
        if (isset($this->siteKey)) {
            return $this->siteKey;
        }

        if (isset($this->configFactory)) {
            return $this->configFactory
                ->get('wmrecaptcha.settings')
                ->get('site_key');
        }

        return null;
    }

    public function getSecretKey(): ?string
    {
        if (isset($this->secretKey)) {
            return $this->secretKey;
        }

        if (isset($this->configFactory)) {
            return $this->configFactory
                ->get('wmrecaptcha.settings')
                ->get('secret_key');
        }

        return null;
    }

    public function verify(string $responseToken): bool
    {
        $response = $this->client->post('https://www.google.com/recaptcha/api/siteverify', [
            'form_params' => [
                'secret' => $this->getSecretKey(),
                'response' => $responseToken,
            ],
        ]);

        $body = $response->getBody()->getContents();
        $body = \GuzzleHttp\json_decode($body, true);

        return $body['success'];
    }
}
