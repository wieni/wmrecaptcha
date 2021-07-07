<?php

namespace Drupal\wmrecaptcha\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 *
 * @Constraint(
 *   id = "recaptcha_trusted",
 *   label = @Translation("reCAPTCHA token validator", context = "Validation"),
 * )
 */
class Trusted extends Constraint
{
    public $message = 'Visitor is not trusted by reCAPTCHA.';
}
