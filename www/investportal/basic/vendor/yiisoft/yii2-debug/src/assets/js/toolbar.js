(function () {
    'use strict';

    var findToolbar = function () {
            return document.querySelector('#yii-debug-toolbar');
        },
        ajax = function (url, settings) {
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
        },
        url,
        div,
        toolbarEl = findToolbar(),
        toolbarAnimatingClass = 'yii-debug-toolbar_animating',
        barSelector = '.yii-debug-toolbar__bar',
        viewSelector = '.yii-debug-toolbar__view',
        blockSelector = '.yii-debug-toolbar__block',
        toggleSelector = '.yii-debug-toolbar__toggle',
        externalSelector = '.yii-debug-toolbar__external',

        CACHE_KEY = 'yii-debug-toolbar',
        ACTIVE_STATE = 'active',

        animationTime = 300,

        activeClass = 'yii-debug-toolbar_active',
        iframeActiveClass = 'yii-debug-toolbar_iframe_active',
        iframeAnimatingClass = 'yii-debug-toolbar_iframe_animating',
        titleClass = 'yii-debug-toolbar__title',
        blockClass = 'yii-debug-toolbar__block',
        ignoreClickClass = 'yii-debug-toolbar__ignore_click',
        blockActiveClass = 'yii-debug-toolbar__block_active',
        requestStack = [];

    if (toolbarEl) {
        url = toolbarEl.getAttribute('data-url');

        ajax(url, {
            success: function (xhr) {
                div = document.createElement('div');
                div.innerHTML = xhr.responseText;

                toolbarEl.parentNode && toolbarEl.parentNode.replaceChild(div, toolbarEl);

                showToolbar(findToolbar());

                var event;
                if (typeof(Event) === 'function') {
                    event = new Event('yii.debug.toolbar_attached', {'bubbles': true});
                } else {
                    event = document.createEvent('Event');
                    event.initEvent('yii.debug.toolbar_attached', true, true);
                }

                div.dispatchEvent(event);
            },
            error: function (xhr) {
                toolbarEl.innerText = xhr.responseText;
            }
        });
    }

    function showToolbar(toolbarEl) {
        var barEl = toolbarEl.querySelector(barSelector),
            viewEl = toolbarEl.querySelector(viewSelector),
            toggleEl = toolbarEl.querySelector(toggleSelector),
            externalEl = toolbarEl.querySelector(externalSelector),
            blockEls = barEl.querySelectorAll(blockSelector),
            blockLinksEls = document.querySelectorAll(blockSelector + ':not(.' + titleClass + ') a'),
            iframeEl = viewEl.querySelector('iframe'),
            iframeHeight = function () {
                return (window.innerHeight * (toolbarEl.dataset.height / 100) - barEl.clientHeight) + 'px';
            },
            isIframeActive = function () {
                return toolbarEl.classList.contains(iframeActiveClass);
            },
            resizeIframe = function(mouse) {
                var availableHeight = window.innerHeight - barEl.clientHeight;
                viewEl.style.height = Math.min(availableHeight, availableHeight - mouse.y) + "px";
            },
            showIframe = function (href) {
                toolbarEl.classList.add(iframeAnimatingClass);
                toolbarEl.classList.add(iframeActiveClass);

                iframeEl.src = externalEl.href = href;
                iframeEl.removeAttribute('tabindex');

                viewEl.style.height = iframeHeight();
                setTimeout(function () {
                    toolbarEl.classList.remove(iframeAnimatingClass);
                }, animationTime);
            },
            hideIframe = function () {
                toolbarEl.classList.add(iframeAnimatingClass);
                toolbarEl.classList.remove(iframeActiveClass);
                iframeEl.setAttribute("tabindex", "-1");
                removeActiveBlocksCls();

                externalEl.href = '#';
                viewEl.style.height = '';
                setTimeout(function () {
                    toolbarEl.classList.remove(iframeAnimatingClass);
                }, animationTime);
            },
            removeActiveBlocksCls = function () {
                [].forEach.call(blockEls, function (el) {
                    el.classList.remove(blockActiveClass);
                });
            },
            toggleToolbarClass = function (className) {
                toolbarEl.classList.add(toolbarAnimatingClass);
                if (toolbarEl.classList.contains(className)) {
                    toolbarEl.classList.remove(className);
                    [].forEach.call(blockLinksEls, function (el) {
                        el.setAttribute('tabindex', "-1");
                    });
                } else {
                    [].forEach.call(blockLinksEls, function (el) {
                        el.removeAttribute('tabindex');
                    });
                    toolbarEl.classList.add(className);
                }
                setTimeout(function () {
                    toolbarEl.classList.remove(toolbarAnimatingClass);
                }, animationTime);
            },
            toggleStorageState = function (key, value) {
                if (window.localStorage) {
                    var item = localStorage.getItem(key);

                    if (item) {
                        localStorage.removeItem(key);
                    } else {
                        localStorage.setItem(key, value);
                    }
                }
            },
            restoreStorageState = function (key) {
                if (window.localStorage) {
                    return localStorage.getItem(key);
                }
            },
            togglePosition = function () {
                if (isIframeActive()) {
                    hideIframe();
                } else {
                    toggleToolbarClass(activeClass);
                    toggleStorageState(CACHE_KEY, ACTIVE_STATE);
                }
            };

        if (restoreStorageState(CACHE_KEY) === ACTIVE_STATE) {
            var transition = toolbarEl.style.transition;
            toolbarEl.style.transition = 'none';
            toolbarEl.classList.add(activeClass);
            setTimeout(function () {
                toolbarEl.style.transition = transition;
            }, animationTime);
        } else {
            [].forEach.call(blockLinksEls, function (el) {
                el.setAttribute('tabindex', "-1");
            });
        }

        toolbarEl.style.display = 'block';

        window.onresize = function () {
            if (toolbarEl.classList.contains(iframeActiveClass)) {
                viewEl.style.height = iframeHeight();
            }
        };

        toolbarEl.addEventListener("mousedown", function(e) {
            if (isIframeActive() && (e.y - toolbarEl.offsetTop < 4 /* 4px click zone */)) {
                document.addEventListener("mousemove", resizeIframe, false);
            }
        }, false);

        document.addEventListener("mouseup", function(){
            if (isIframeActive()) {
                document.removeEventListener("mousemove", resizeIframe, false);
            }
        }, false);

        barEl.onclick = function (e) {
            var target = e.target,
                block = findAncestor(target, blockClass);

            if (block
                && !block.classList.contains(titleClass)
                && !block.classList.contains(ignoreClickClass)
                && e.which !== 2 && !e.ctrlKey // not mouse wheel and not ctrl+click
            ) {
                while (target !== this) {
                    if (target.href) {
                        removeActiveBlocksCls();
                        block.classList.add(blockActiveClass);
                        showIframe(target.href);
                    }
                    target = target.parentNode;
                }

                e.preventDefault();
            }
        };

        toggleEl.onclick = togglePosition;
    }

    function findAncestor(el, cls) {
        while ((el = el.parentElement) && !el.classList.contains(cls)) ;
        return el;
    }

    function renderAjaxRequests() {
        var requestCounter = document.getElementsByClassName('yii-debug-toolbar__ajax_counter');
        if (!requestCounter.length) {
            return;
        }
        var ajaxToolbarPanel = document.querySelector('.yii-debug-toolbar__ajax');
        var tbodies = document.getElementsByClassName('yii-debug-toolbar__ajax_requests');
        var state = 'ok';
        if (tbodies.length) {
            var tbody = tbodies[0];
            var rows = document.createDocumentFragment();
            if (requestStack.length) {
                var firstItem = requestStack.length > 20 ? requestStack.length - 20 : 0;
                for (var i = firstItem; i < requestStack.length; i++) {
                    var request = requestStack[i];
                    var row = document.createElement('tr');
                    rows.appendChild(row);

                    var methodCell = document.createElement('td');
                    methodCell.innerHTML = request.method;
                    row.appendChild(methodCell);

                    var statusCodeCell = document.createElement('td');
                    var statusCode = document.createElement('span');
                    if (request.statusCode < 300) {
                        statusCode.setAttribute('class', 'yii-debug-toolbar__ajax_request_status yii-debug-toolbar__label_success');
                    } else if (request.statusCode < 400) {
                        statusCode.setAttribute('class', 'yii-debug-toolbar__ajax_request_status yii-debug-toolbar__label_warning');
                    } else {
                        statusCode.setAttribute('class', 'yii-debug-toolbar__ajax_request_status yii-debug-toolbar__label_error');
                    }
                    statusCode.textContent = request.statusCode || '-';
                    statusCodeCell.appendChild(statusCode);
                    row.appendChild(statusCodeCell);

                    var pathCell = document.createElement('td');
                    pathCell.className = 'yii-debug-toolbar__ajax_request_url';
                    pathCell.innerHTML = request.url;
                    pathCell.setAttribute('title', request.url);
                    row.appendChild(pathCell);

                    var durationCell = document.createElement('td');
                    durationCell.className = 'yii-debug-toolbar__ajax_request_duration';
                    if (request.duration) {
                        durationCell.innerText = request.duration + " ms";
                    } else {
                        durationCell.innerText = '-';
                    }
                    row.appendChild(durationCell);
                    row.appendChild(document.createTextNode(' '));

                    var profilerCell = document.createElement('td');
                    if (request.profilerUrl) {
                        var profilerLink = document.createElement('a');
                        profilerLink.setAttribute('href', request.profilerUrl);
                        profilerLink.innerText = request.profile;
                        profilerCell.appendChild(profilerLink);
                    } else {
                        profilerCell.innerText = 'n/a';
                    }
                    row.appendChild(profilerCell);

                    if (request.error) {
                        if (state !== "loading" && i > requestStack.length - 4) {
                            state = 'error';
                        }
                    } else if (request.loading) {
                        state = 'loading'
                    }
                    row.className = 'yii-debug-toolbar__ajax_request';
                }
                while (tbody.firstChild) {
                    tbody.removeChild(tbody.firstChild);
                }
                tbody.appendChild(rows);
            }
            ajaxToolbarPanel.style.display = 'block';
        }
        requestCounter[0].innerText = requestStack.length;
        var className = 'yii-debug-toolbar__label yii-debug-toolbar__ajax_counter';
        if (state === 'ok') {
            className += ' yii-debug-toolbar__label_success';
        } else if (state === 'error') {
            className += ' yii-debug-toolbar__label_error';
        }
        requestCounter[0].className = className;
    }

    /**
     * Should AJAX request to be logged in debug panel
     *
     * @param requestUrl
     * @returns {boolean}
     */
    function shouldTrackRequest(requestUrl) {
        var a = document.createElement('a');
        a.href = requestUrl;
        var skipAjaxRequestUrls = JSON.parse(toolbarEl.getAttribute('data-skip-urls'));
        if (Array.isArray(skipAjaxRequestUrls) && skipAjaxRequestUrls.length && skipAjaxRequestUrls.includes(requestUrl)) {
            return false;
        }
        return a.host === location.host;
    }

    var proxied = XMLHttpRequest.prototype.open;

    XMLHttpRequest.prototype.open = function (method, url, async, user, pass) {
        var self = this;

        if (shouldTrackRequest(url)) {
            var stackElement = {
                loading: true,
                error: false,
                url: url,
                method: method,
                start: new Date()
            };
            requestStack.push(stackElement);
            this.addEventListener('readystatechange', function () {
                if (self.readyState === 4) {
                    stackElement.duration = self.getResponseHeader('X-Debug-Duration') || new Date() - stackElement.start;
                    stackElement.loading = false;
                    stackElement.statusCode = self.status;
                    stackElement.error = self.status < 200 || self.status >= 400;
                    stackElement.profile = self.getResponseHeader('X-Debug-Tag');
                    stackElement.profilerUrl = self.getResponseHeader('X-Debug-Link');
                    renderAjaxRequests();
                }
            }, false);
            renderAjaxRequests();
        }
        proxied.apply(this, Array.prototype.slice.call(arguments));
    };

    // catch fetch AJAX requests
    if (window.fetch) {
        var originalFetch = window.fetch;

        window.fetch = function (input, init) {
            var method;
            var url;
            if (typeof input === 'string') {
                method = (init && init.method) || 'GET';
                url = input;
            } else if (window.URL && input instanceof URL) { // fix https://github.com/yiisoft/yii2-debug/issues/296
                method = (init && init.method) || 'GET';
                url = input.href;
            } else if (window.Request && input instanceof Request) {
                method = input.method;
                url = input.url;
            }
            var promise = originalFetch(input, init);

            if (shouldTrackRequest(url)) {
                var stackElement = {
                    loading: true,
                    error: false,
                    url: url,
                    method: method,
                    start: new Date()
                };
                requestStack.push(stackElement);
                promise.then(function (response) {
                    stackElement.duration = response.headers.get('X-Debug-Duration') || new Date() - stackElement.start;
                    stackElement.loading = false;
                    stackElement.statusCode = response.status;
                    stackElement.error = response.status < 200 || response.status >= 400;
                    stackElement.profile = response.headers.get('X-Debug-Tag');
                    stackElement.profilerUrl = response.headers.get('X-Debug-Link');
                    renderAjaxRequests();

                    return response;
                }).catch(function (error) {
                    stackElement.loading = false;
                    stackElement.error = true;
                    renderAjaxRequests();

                    throw error;
                });
                renderAjaxRequests();
            }

            return promise;
        };
    }

})();
