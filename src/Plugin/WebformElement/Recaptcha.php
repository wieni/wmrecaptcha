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

    public function form(array $form, FormStateInterface $formState)
    {
        $form = parent::form($form, $formState);

        $form['element_description']['#access'] = false;
        $form['form']['#access'] = false;
        $form['validation']['#access'] = false;

        return $form;
    }
}
