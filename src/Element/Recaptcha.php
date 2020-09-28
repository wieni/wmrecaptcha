<?php

namespace Drupal\wmrecaptcha\Element;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Render\Element\FormElement;
use Drupal\wmrecaptcha\ReCaptcha as ReCaptchaService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Defines the reCAPTCHA form element with default properties.
 *
 * @FormElement("recaptcha")
 */
class Recaptcha extends FormElement implements ContainerFactoryPluginInterface
{
    /** @var RequestStack */
    protected $requestStack;
    /** @var ReCaptchaService */
    protected $reCaptcha;

    public static function create(
        ContainerInterface $container,
        array $configuration,
        $pluginId, $pluginDefinition
    ) {
        $instance = new static($configuration, $pluginId, $pluginDefinition);
        $instance->requestStack = $container->get('request_stack');
        $instance->reCaptcha = $container->get('wmrecaptcha');

        return $instance;
    }

    public function getInfo()
    {
        return [
            '#type' => 'item',
            '#markup' => sprintf('<div class="g-recaptcha" data-sitekey="%s"></div>', $this->reCaptcha->getSiteKey()),
            '#input' => true,
            '#process' => [[$this, 'process']],
            '#element_validate' => [[$this, 'validate']],
            '#default_value' => '',
        ];
    }

    public function process(array &$element)
    {
        $element['#attached']['library'][] = 'wmrecaptcha/recaptcha';
        $element['#attached']['drupalSettings']['wmrecaptcha']['siteKey'] = $this->reCaptcha->getSiteKey();

        return $element;
    }

    public function validate(array $element, FormStateInterface $formState)
    {
        $token = $this->getResponseToken();

        if (!$token || !$this->reCaptcha->verify($token)) {
            $formState->setErrorByName('recaptcha', $this->t("Please check the <i>I'm not a robot</i> box"));
        }
    }

    protected function getResponseToken(): ?string
    {
        if (!$request = $this->requestStack->getCurrentRequest()) {
            return null;
        }

        return $request->request->get('g-recaptcha-response');
    }
}
