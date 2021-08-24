(function () {
    'use strict';

    var on = function (element, event, handler) {
        var i;
        if (null === element) {
            return;
        }
        if (element instanceof NodeList) {
            for (i = 0; i < element.length; i++) {
                element[i].addEventListener(event, handler, false);
            }
            return;
        }
        if (!(element instanceof Array)) {
            element = [element];
        }
        for (i in element) {
            if (typeof element[i].addEventListener !== 'function') {
                continue;
            }
            element[i].addEventListener(event, handler, false);
        }
    }, ajax = function (url, settings) {
        var xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        settings = settings || {};
        xhr.open(settings.method || 'GET', url, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.setRequestHeader('Accept', 'text/html');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200 && settings.success) {
                    settings.success(xhr);
                } else if (xhr.status !== 200 && settings.error) {
                    settings.error(xhr);
                }
            }
        };
        xhr.send(settings.data || '');
    };

    on(document.querySelectorAll('.db-explain a'), 'click', function (e) {
        if (e.target.tagName.toLowerCase() !== 'a') {
            return;
        }

        e.preventDefault();

        var $explain = e.target.parentElement.parentElement.querySelector('.db-explain-text'),
            self = this;

        // hidden (see https://developer.mozilla.org/en-US/docs/Web/API/HTMLElement/offsetParent)
        if ($explain.offsetParent === null) {
            ajax(this.href, {
                success: function (xhr) {
                    $explain.innerHTML = xhr.responseText;
                    $explain.style.display = 'block';
                    self.textContent = '[-] Explain';
                }
            })
        } else {
            $explain.style.display = 'none';
            this.textContent = '[+] Explain';
        }
    });

    on(document.getElementById('db-explain-all').querySelector('a'), 'click', function () {
        var event = new MouseEvent('click', {
            cancelable: true,
            bubbles: true
        });

        var elements = document.querySelectorAll('.db-explain a');
        for (var i = 0, len = elements.length; i < len; i++) {
            elements[i].dispatchEvent(event);
        }
    });
})();
