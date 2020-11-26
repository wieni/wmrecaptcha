<?php

namespace Drupal\wmrecaptcha;

interface VerificationResponseInterface
{
    public const ERROR_MISSING_INPUT_SECRET = 'missing-input-secret';
    public const ERROR_INVALID_INPUT_SECRET = 'invalid-input-secret';
    public const ERROR_MISSING_INPUT_RESPONSE = 'missing-input-response';
    public const ERROR_INVALID_INPUT_RESPONSE = 'invalid-input-response';
    public const ERROR_BAD_REQUEST = 'bad-request';
    public const ERROR_TIMEOUT_OR_DUPLICATE = 'timeout-or-duplicate';

    public function isSuccess(): bool;

    public function getChallengeTimestamp(): string;

    public function getChallengeTime(): ?\DateTimeInterface;

    public function getErrorCodes(): array;

    public function getErrorMessages(): array;
}
