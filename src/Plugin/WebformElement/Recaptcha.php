<?php

namespace Drupal\wmrecaptcha\Plugin\WebformElement;

use Drupal\Core\Form\FormStateInterface;
use Drupal\webform\Plugin\WebformElementBase;

/**
 * Provides a 'recaptcha' element.
 *
 * @WebformElement(
 *     id = "recaptcha",
 *     default_key = "recaptcha",
 *     label = @Translation("reCAPTCHA"),
 *     description = @Translation("Provides a form element with a reCAPTCHA widget."),
 *     category = @Translation("Advanced elements"),
 *     states_wrapper = TRUE,
 * )
 */
class Recaptcha extends WebformElementBase
{
    public function isInput(array $element)
    {
        return false;
    }

    public function isContainer(array $element)
    {
        return false;
    }

    public function getItemDefaultFormat()
    {
        return null;
    }

    public function getItemFormats()
    {
        return [];
    }

    public function getDefaultProperties()
    {
        return [
            'recaptcha_type' => 'v2',
        ] + parent::getDefaultProperties();
    }

    public function form(array $form, FormStateInterface $formState)
    {
        $form = parent::form($form, $formState);

        $form['default']['#access'] = false;
        $form['display']['#access'] = false;
        $form['element']['title']['#disabled'] = true;
        $form['element']['title']['#value'] = 'reCAPTCHA';
        $form['element_description']['#access'] = false;
        $form['form']['#access'] = false;
        $form['label_attributes']['#access'] = false;
        $form['validation']['#access'] = false;

        $form['recaptcha'] = [
            '#type' => 'fieldset',
            '#title' => $this->t('reCAPTCHA settings'),
        ];

        $form['recaptcha']['recaptcha_type'] = [
            '#type' => 'select',
            '#title' => $this->t('Type'),
            '#options' => [
                'v2' => $this->t('reCAPTCHA v2 (Verify requests with the "I\'m not a robot" checkbox)'),
                'v2_invisible' => $this->t('reCAPTCHA v2 (Validate requests in the background)'),
            ],
            '#required' => TRUE,
        ];

        return $form;
    }
}
