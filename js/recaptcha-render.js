(function ($, Drupal, drupalSettings) {

    'use strict';

    var onRecaptchaLoad = function () {
        if (typeof grecaptcha === "undefined") {
            return;
        }

        var element = document.querySelector('.g-recaptcha');

        if (element.classList.contains('is-rendered')) {
            return;
        }

        grecaptcha.render(element, {
            'sitekey' : drupalSettings.wmrecaptcha.siteKey,
        });

        element.classList.add('is-rendered');
    };

    Drupal.behaviors.reCaptchaRender = {
        attach: onRecaptchaLoad,
    };

}(jQuery, Drupal, drupalSettings));
