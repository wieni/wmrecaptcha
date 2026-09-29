<?php

namespace Drupal\wmrecaptcha\Plugin\Validation\Constraint;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\wmrecaptcha\ReCaptcha;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidValidator extends ConstraintValidator implements ContainerInjectionInterface
{
    /** @var Recaptcha */
    protected $recaptcha;

    public static function create(ContainerInterface $container)
    {
        $instance = new static();
        $instance->recaptcha = $container->get('wmrecaptcha');

        return $instance;
    }

    public function validate($token, Constraint $constraint)
    {
        if ($token === null) {
            return;
        }

        $verification = $this->recaptcha->verify($token);

        if ($verification->isSuccess()) {
            return;
        }

        if ($messages = $verification->getErrorMessages()) {
            foreach ($messages as $code => $message) {
                $this->context->buildViolation($message)
                    ->setCode($code)
                    ->addViolation();
            }
        } else {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
