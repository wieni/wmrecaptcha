var onRecaptchaLoad = function () {
    if (typeof grecaptcha === "undefined") {
        return;
    }

    var element = document.querySelector('.g-recaptcha');

    if (element.classList.contains('is-rendered')) {
        return;
    }

    grecaptcha.render(element, {
        'sitekey' : window.drupalSettings.wmrecaptcha.siteKey,
    });

    element.classList.add('is-rendered');
};

(function (Drupal) {

    'use strict';

    Drupal.behaviors.reCaptchaRender = {
        attach: onRecaptchaLoad,
    };

}(Drupal));
