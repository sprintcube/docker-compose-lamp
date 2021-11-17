(function () {
    'use strict';

    var ajax = function (url, settings) {
        var xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');

        if (typeof url === 'object' && url.hasOwnProperty('url')) {
            settings = url;
            url = settings.url;
            delete settings.url;
        }

        settings = settings || {};
        var method = settings.method || 'GET';
        xhr.open(method, url, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.setRequestHeader('Accept', 'text/html');
        if (method.toLowerCase() === 'post') {
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        }
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
    }, on = function (element, event, handler) {
        if (null === element) {
            return;
        }
        if (element instanceof NodeList) {
            element.forEach(function (value) {
                value.addEventListener(event, handler, false);
            });
            return;
        }
        if (!(element instanceof Array)) {
            element = [element];
        }
        for (var i in element) {
            if (typeof element[i].addEventListener !== 'function') {
                continue;
            }
            element[i].addEventListener(event, handler, false);
        }
    }, serialize = function (form) {
        if (!form || form.nodeName !== "FORM") {
            return;
        }
        var i, j, q = [];
        for (i = form.elements.length - 1; i >= 0; i = i - 1) {
            if (form.elements[i].name === "") {
                continue;
            }
            switch (form.elements[i].nodeName) {
                case 'INPUT':
                    switch (form.elements[i].type) {
                        case 'text':
                        case 'hidden':
                        case 'password':
                        case 'button':
                        case 'reset':
                        case 'submit':
                            q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
                            break;
                        case 'checkbox':
                        case 'radio':
                            if (form.elements[i].checked) {
                                q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
                            }
                            break;
                        case 'file':
                            break;
                    }
                    break;
                case 'TEXTAREA':
                    q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
                    break;
                case 'SELECT':
                    switch (form.elements[i].type) {
                        case 'select-one':
                            q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
                            break;
                        case 'select-multiple':
                            for (j = form.elements[i].options.length - 1; j >= 0; j = j - 1) {
                                if (form.elements[i].options[j].selected) {
                                    q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].options[j].value));
                                }
                            }
                            break;
                    }
                    break;
                case 'BUTTON':
                    switch (form.elements[i].type) {
                        case 'reset':
                        case 'submit':
                        case 'button':
                            q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
                            break;
                    }
                    break;
            }
        }
        return q.join("&");
    }, sendSetIdentity = function (url, data) {
        ajax({
            url: url,
            method: 'post',
            data: data,
            success: function () {
                window.top.location.reload();
            },
            error: function (data) {
                if (window.jQuery) {
                    window.jQuery(form).yiiActiveForm('updateMessages', data.responseJSON, true);
                }
            }
        });
    };

    on(document.getElementById('debug-userswitch__filter'), 'click', function (e) {
        var el;
        if (e.target.tagName.toLowerCase() === 'td' && e.target.parentElement.parentElement.tagName.toLowerCase() === 'tbody') {
            el = e.target.parentElement;
        } else if (e.target.tagName.toLowerCase() === 'tr' && e.target.parentElement.tagName.toLowerCase() === 'tbody') {
            el = e.target;
        } else {
            return;
        }

        var form = document.getElementById('debug-userswitch__set-identity');
        document.getElementById('user_id').value = el.dataset.key;
        sendSetIdentity(form.action, serialize(form));
        e.stopPropagation();
    });
    on(document.getElementById('debug-userswitch__reset-identity-button'), 'click', function (e) {
        var form = document.getElementById('debug-userswitch__reset-identity');

        e.preventDefault();
        e.stopPropagation();

        sendSetIdentity(form.action, serialize(form));
    })
})();
