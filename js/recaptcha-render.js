var onRecaptchaLoad = function () {
    if (typeof grecaptcha === "undefined") {
        return;
    }

    var element = document.querySelector('.wmrecaptcha');
    var type = window.drupalSettings.wmrecaptcha.type;
    var siteKey = window.drupalSettings.wmrecaptcha.siteKey;

    if (element.classList.contains('is-rendered')) {
        return;
    }

    if (type === 'v2_invisible') {
        var form = element.closest('form');
        var submitButton = form.querySelector('input[type="submit"]');

        grecaptcha.render(submitButton, {
            sitekey: siteKey,
            callback: function () {
                form.submit();
            },
        });
    }

    if (type === 'v2') {
        grecaptcha.render(element, {
            sitekey: siteKey,
        });
    }

    element.classList.add('is-rendered');
};

(function (Drupal) {

    'use strict';

    Drupal.behaviors.reCaptchaRender = {
        attach: onRecaptchaLoad,
    };

}(Drupal));
