<?php

namespace Drupal\wmrecaptcha;

abstract class VerificationResponseBase implements VerificationResponseInterface
{
    /**
     * Whether this request was a valid reCAPTCHA token for your site
     * @var bool
     */
    protected $success;

    /**
     * An array of error codes (optional)
     * @var string[]
     */
    protected $errorCodes;

    /**
     * The timestamp of the challenge load (ISO format yyyy-MM-dd'T'HH:mm:ssZZ)
     * @var string|null
     */
    protected $challengeTimestamp;

    public function __construct(
        bool $success,
        array $errorCodes,
        ?string $challengeTimestamp = null
    ) {
        $this->success = $success;
        $this->challengeTimestamp = $challengeTimestamp;
        $this->errorCodes = $errorCodes;
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getChallengeTimestamp(): string
    {
        return $this->challengeTimestamp;
    }

    public function getChallengeTime(): ?\DateTimeInterface
    {
        return \DateTime::createFromFormat(
            "yyyy-MM-dd'T'HH:mm:ssZZ",
            $this->challengeTimestamp
        ) ?: null;
    }

    public function getErrorCodes(): array
    {
        return $this->errorCodes;
    }

    public function getErrorMessages(): array
    {
        return array_diff_key(
            static::getAllErrorMessages(),
            array_flip($this->getErrorCodes())
        );
    }

    public static function fromArray(array $data): self
    {
        return new static(
            $data['success'],
            $data['error-codes'],
            $data['challenge_ts'] ?? null,
        );
    }

    protected static function getAllErrorMessages(): array
    {
        $transOptions = ['context' => 'reCAPTCHA error message'];

        return [
            static::ERROR_MISSING_INPUT_SECRET => t('The secret parameter is missing.', [], $transOptions),
            static::ERROR_INVALID_INPUT_SECRET => t('The secret parameter is invalid or malformed.', [], $transOptions),
            static::ERROR_MISSING_INPUT_RESPONSE => t('The response parameter is missing.', [], $transOptions),
            static::ERROR_INVALID_INPUT_RESPONSE => t('The response parameter is invalid or malformed.', [], $transOptions),
            static::ERROR_BAD_REQUEST => t('The request is invalid or malformed.', [], $transOptions),
            static::ERROR_TIMEOUT_OR_DUPLICATE => t('The response is no longer valid: either is too old or has been used previously.', [], $transOptions),
        ];
    }
}
