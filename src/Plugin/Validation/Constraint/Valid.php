<?php

namespace Drupal\wmrecaptcha\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 *
 * @Constraint(
 *   id = "recaptcha_valid",
 *   label = @Translation("reCAPTCHA token validator", context = "Validation"),
 * )
 */
class Valid extends Constraint
{
    public $message = 'reCAPTCHA token is invalid or outdated.';
}
