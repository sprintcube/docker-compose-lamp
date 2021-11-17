(function () {
    'use strict';

    var on = function (element, event, handler) {
        var i;
        if (null === element) {
            return;
        }
        if (element instanceof NodeList) {
            for (i = 0; i <  element.length; i++) {
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
    };

    var Timeline = function (options) {
        this.options = options;
        var self = this;
        this.init = function () {
            if (this.options.$focus) {
                this.options.$focus.focus();
                delete this.options.$focus;
            }
            var links = document.querySelectorAll('.debug-timeline-panel__item a');

            for (var i = 0, len = links.length; i < len; i++) {
                new Tooltip(links[i]);

                on(links[i], 'show.bs.tooltip', function() {
                    if (this.hasAttribute('data-memory')) {
                        var data = this.dataset.memory;
                        self.options.$memory.textContent = data[0];
                        self.options.$memory.style.bottom = data[1] + '%';
                    }
                });

            }
            return self;
        };
        this.setFocus = function ($elem) {
            this.options.$focus = $elem;
            return $elem;
        };

        on(document, 'pjax:success', function () {
            self.init();
        });

        on(self.options.$header, 'dblclick', function () {
            self.options.$timeline.classList.toggle('inline');
        });
        on(self.options.$header, 'click', function (e) {
            if (e.target.tagName.toLowerCase() === 'button') {
                self.options.$timeline.classList.toggle('inline');
            }
        });

        on(self.options.$search, 'change', function () {
            self.setFocus(this);
            this.form.submit();
        });

        this.init();
    };

    (new Timeline({
        '$timeline': document.querySelector('.debug-timeline-panel'),
        '$header': document.querySelector('.debug-timeline-panel__header'),
        '$search': document.querySelectorAll('.debug-timeline-panel__search input'),
        '$memory': document.querySelector('.debug-timeline-panel__memory .scale')
    }));
})();
