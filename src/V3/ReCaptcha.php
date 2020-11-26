<?php

namespace Drupal\wmrecaptcha\V3;

use Drupal\wmrecaptcha\ReCaptchaInterface;
use Drupal\wmrecaptcha\SettingsInterface;
use \Drupal\wmrecaptcha\V3;
use Drupal\wmrecaptcha\VerificationResponseInterface;
use GuzzleHttp\Client;

class ReCaptcha implements ReCaptchaInterface
{
    /** @var Client */
    protected $client;
    /** @var SettingsInterface */
    protected $settings;

    public function __construct(
        Client $client,
        SettingsInterface $settings
    ) {
        $this->client = $client;
        $this->settings = $settings;
    }

    public function getSiteKey(): ?string
    {
        return $this->settings->get('v3.secret_key');
    }

    public function getSecretKey(): ?string
    {
        return $this->settings->get('v3.secret_key');
    }

    public function isTrusted(VerificationResponseInterface $verification): bool
    {
        if (!$verification->isSuccess()) {
            return false;
        }

        if ($verification instanceof V3\VerificationResponse) {
            $action = $verification->getAction();
            $threshold = $this->getTrustThreshold($action);

            if ($threshold !== null) {
                return $verification->getScore() > $threshold;
            }
        }

        return false;
    }

    public function verify(string $responseToken): VerificationResponseInterface
    {
        $response = $this->client->post('https://www.google.com/recaptcha/api/siteverify', [
            'form_params' => [
                'secret' => $this->getSecretKey(),
                'response' => $responseToken,
            ],
        ]);

        $body = $response->getBody()->getContents();
        $body = \GuzzleHttp\json_decode($body, true);

        return VerificationResponse::fromArray($body);
    }

    protected function getTrustThreshold(?string $action): ?float
    {
        if ($action === null) {
            return $this->settings->get('v3.trust_threshold.global');
        }

        return $this->settings->get("v3.trust_threshold.per_action.${$action}");
    }
}
