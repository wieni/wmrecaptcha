<?php

namespace Drupal\wmrecaptcha;

use Drupal\Core\Config\ConfigFactoryInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

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
        try {
            $response = $this->client->post('https://www.google.com/recaptcha/api/siteverify', [
                'form_params' => [
                    'secret' => $this->getSecretKey(),
                    'response' => $responseToken,
                ],
            ]);
        } catch (GuzzleException $e) {
            return false;
        }

        $body = $response->getBody()->getContents();
        // Guzzle's Utils::jsonDecode() is deprecated and is removed in
        // guzzlehttp/guzzle:8.0. JSON_THROW_ON_ERROR keeps the existing
        // behaviour of throwing on a malformed response body (Guzzle threw an
        // InvalidArgumentException; this throws a JsonException).
        $body = json_decode($body, true, 512, \JSON_THROW_ON_ERROR);

        return $body['success'];
    }
}
