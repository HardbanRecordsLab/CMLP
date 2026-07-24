/**
 * HRL — dostępny akordeon FAQ.
 * Zastępuje inline onclick z motywu nadrzędnego: aktualizuje aria-expanded
 * i atrybut hidden, dzięki czemu czytniki ekranu poprawnie odczytują stan.
 */
(function () {
    'use strict';

    function init() {
        var buttons = document.querySelectorAll('.hrl-faq-question');
        if (!buttons.length) { return; }

        Array.prototype.forEach.call(buttons, function (btn) {
            btn.addEventListener('click', function () {
                var expanded = btn.getAttribute('aria-expanded') === 'true';
                var panel = document.getElementById(btn.getAttribute('aria-controls'));

                btn.setAttribute('aria-expanded', expanded ? 'false' : 'true');
                if (panel) { panel.hidden = expanded; }

                var icon = btn.querySelector('.hrl-faq-icon');
                if (icon) { icon.textContent = expanded ? '+' : '−'; }
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
