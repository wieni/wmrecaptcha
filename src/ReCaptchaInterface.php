<?php

namespace Drupal\wmrecaptcha;

interface ReCaptchaInterface
{
    public function getSiteKey(): ?string;

    public function getSecretKey(): ?string;

    public function isTrusted(VerificationResponseInterface $verification): bool;

    public function verify(string $responseToken): VerificationResponseInterface;
}
