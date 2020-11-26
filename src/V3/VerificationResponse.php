<?php

namespace Drupal\wmrecaptcha\V3;

use Drupal\wmrecaptcha\VerificationResponseBase;

class VerificationResponse extends VerificationResponseBase
{
    /**
     * The score for this request (0.0 - 1.0)
     * @var float|null
     */
    protected $score;

    /**
     * The action name for this request (important to verify)
     * @var string|null
     */
    protected $action;

    /**
     * The hostname of the site where the reCAPTCHA was solved
     * @var string|null
     */
    protected $hostname;

    public function __construct(
        bool $success,
        array $errorCodes,
        ?string $challengeTimestamp = null,
        ?float $score = null,
        ?string $action = null,
        ?string $hostname = null
    ) {
        parent::__construct($success, $errorCodes, $challengeTimestamp);
        $this->score = $score;
        $this->action = $action;
        $this->hostname = $hostname;
    }

    public function getScore(): ?float
    {
        return $this->score;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function getHostname(): ?string
    {
        return $this->hostname;
    }

    public static function fromArray(array $data): self
    {
        return new static(
            $data['success'],
            $data['error-codes'],
            $data['challenge_ts'] ?? null,
            $data['score'] ?? null,
            $data['action'] ?? null,
            $data['hostname'] ?? null,
        );
    }
}
