yii.gii = (function ($) {

    var $clipboardContainer = $("#clipboard-container"),
    valueToCopy = '',
    ajaxRequest = null,

    onKeydown = function(e) {
        var $target;
        $target = $(e.target);

        if ($target.is("input:visible, textarea:visible")) {
            return;
        }

        if (typeof window.getSelection === "function" && window.getSelection().toString()) {
            return;
        }

        if (document.selection != null && document.selection.createRange().text) {
            return;
        }

        $clipboardContainer.empty().show();
        return $("<textarea id='clipboard'></textarea>").val(valueToCopy).appendTo($clipboardContainer).focus().select();
    },

    onKeyup = function(e) {
        if ($(e.target).is("#clipboard")) {
            $("#clipboard-container").empty().hide();
        }
        return true;
    };

    var initStickyInputs = function () {
        $('.sticky:not(.error)').find('input[type="text"],select,textarea').each(function () {
            var value,
                element = document.createElement('div');
            if (this.tagName === 'SELECT') {
                value = this.options[this.selectedIndex].text;
            } else if (this.tagName === 'TEXTAREA') {
                value = $(this).html();
            } else {
                value = $(this).val();
            }
            if (value === '') {
                value = '[empty]';
            }
            element.classList.add('sticky-value');
            element.title = value;
            element.innerHTML = value;
            new Tooltip(element, {placement: 'right'});
            $(this).before(element).hide();
        });
        $('.sticky-value').on('click', function () {
            $(this).hide();
            $(this).next().show().get(0).focus();
        });
    };

    var fillModal = function($link, data) {
        var $modal = $('#preview-modal'),
         $modalBody = $modal.find('.modal-body');
        if (!$link.hasClass('modal-refresh')) {
            var filesSelector = 'a.' + $modal.data('action') + ':visible';
            var $files = $(filesSelector);
            var index = $files.filter('[href="' + $link.attr('href') + '"]').index(filesSelector);
            var $prev = $files.eq(index - 1);
            var $next = $files.eq((index + 1 == $files.length ? 0 : index + 1));
            $modal.data('current', $files.eq(index));
            $modal.find('.modal-previous').attr('href', $prev.attr('href')).data('title', $prev.data('title'));
            $modal.find('.modal-next').attr('href', $next.attr('href')).data('title', $next.data('title'));
        }
        $modalBody.html(data);
        valueToCopy = $("<div/>").html(data.replace(/(<(br[^>]*)>)/ig, '\n').replace(/&nbsp;/ig, ' ')).text().trim() + '\n';
        $modal.find('.content').css('max-height', ($(window).height() - 200) + 'px');
    };

    var initPreviewDiffLinks = function () {
        $('.preview-code, .diff-code, .modal-refresh, .modal-previous, .modal-next').on('click', function () {
            if (ajaxRequest !== null) {
                if ($.isFunction(ajaxRequest.abort)) {
                    ajaxRequest.abort();
                }
            }
            var that = this;
            var $modal = $('#preview-modal');
            var $link = $(this);
            $modal.find('.modal-refresh').attr('href', $link.attr('href'));
            if ($link.hasClass('preview-code') || $link.hasClass('diff-code')) {
                $modal.data('action', ($link.hasClass('preview-code') ? 'preview-code' : 'diff-code'))
            }
            $modal.find('.modal-title').text($link.data('title'));
            $modal.find('.modal-body').html('Loading ...');

            var modalInitJs = new Modal($modal[0]);
            modalInitJs.show();

            var checkbox = $('a.' + $modal.data('action') + '[href="' + $link.attr('href') + '"]').closest('tr').find('input').get(0);
            var checked = false;
            if (checkbox) {
                checked = checkbox.checked;
                $modal.find('.modal-checkbox').removeClass('disabled');
            } else {
                $modal.find('.modal-checkbox').addClass('disabled');
            }
            $modal.find('.modal-checkbox').toggleClass('checked', checked).toggleClass('unchecked', !checked);

            ajaxRequest = $.ajax({
                type: 'POST',
                cache: false,
                url: $link.prop('href'),
                data: $('.default-view form').serializeArray(),
                success: function (data) {
                    fillModal($(that), data);
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    $modal.find('.modal-body').html('<div class="error">' + XMLHttpRequest.responseText + '</div>');
                }
            });
            return false;
        });

        $('#preview-modal').on('keydown', function (e) {
            if (e.keyCode === 37) {
                $('.modal-previous').trigger('click');
            } else if (e.keyCode === 39) {
                $('.modal-next').trigger('click');
            } else if (e.keyCode === 82) {
                $('.modal-refresh').trigger('click');
            } else if (e.keyCode === 32) {
                $('.modal-checkbox').trigger('click');
            }
        });

        $('.modal-checkbox').on('click', checkFileToggle);
    };

    var checkFileToggle = function () {
        var $modal = $('#preview-modal');
        var $checkbox = $modal.data('current').closest('tr').find('input');
        var checked = !$checkbox.prop('checked');
        $checkbox.trigger('click');
        $modal.find('.modal-checkbox').toggleClass('checked', checked).toggleClass('unchecked', !checked);
        return false;
    };

    var checkAllToggle = function () {
        $('#check-all').prop('checked', !$('.default-view-files table .check input:enabled:not(:checked)').length);
    };

    var initConfirmationCheckboxes = function () {
        var $checkAll = $('#check-all');
        $checkAll.click(function () {
            $('.default-view-files table .check input:enabled').prop('checked', this.checked);
        });
        $('.default-view-files table .check input').click(function () {
            checkAllToggle();
        });
        checkAllToggle();
    };

    var initToggleActions = function () {
        $('#action-toggle').find(':input').change(function () {
            $(this).parent('label').toggleClass('active', this.checked);
            var $rows = $('.' + this.value, '.default-view-files table').toggleClass('action-hidden', !this.checked);
            if (this.checked) {
                $rows.not('.filter-hidden').show();
            } else {
                $rows.hide();
            }
            $rows.find('.check input').attr('disabled', !this.checked);
            checkAllToggle();
        });
    };

    var initFilterRows = function () {
        $('#filter-input').on('input', function () {
            var that = this,
            $rows = $('#files-body').find('tr');

            $rows.hide().toggleClass('filter-hidden', true).filter(function () {
                return $(this).text().toUpperCase().indexOf(that.value.toUpperCase()) > -1;
            }).toggleClass('filter-hidden', false).not('.action-hidden').show();

            $rows.find('input').each(function(){
                $(this).prop('disabled', $(this).is(':hidden'));
            });
        });
    };

    $(document).on("keydown", function(e) {
        if (valueToCopy && (e.ctrlKey || e.metaKey) && (e.which === 67)) {
            return onKeydown(e);
        }
    }).on("keyup", onKeyup);

    return {
        init: function () {
            initStickyInputs();
            initPreviewDiffLinks();
            initConfirmationCheckboxes();
            initToggleActions();
            initFilterRows();

            // model generator: hide class name inputs and show psr class name checkbox
            // when table name input contains *
            $('#model-generator #generator-tablename').change(function () {
                var show = ($(this).val().indexOf('*') === -1);
                $('.field-generator-modelclass').toggle(show);
                if ($('#generator-generatequery').is(':checked')) {
                    $('.field-generator-queryclass').toggle(show);
                }
                $('.field-generator-caseinsensitive').toggle(!show);
            }).change();

            // model generator: translate table name to model class
            $('#model-generator #generator-tablename').on('blur', function () {
                var tableName = $(this).val();
                var tablePrefix = $(this).attr('table_prefix') || '';
                if (tablePrefix.length) {
                    // if starts with prefix
                    if (tableName.slice(0, tablePrefix.length) === tablePrefix) {
                        // remove prefix
                        tableName = tableName.slice(tablePrefix.length);
                    }
                }
                if ($('#generator-modelclass').val() === '' && tableName && tableName.indexOf('*') === -1) {
                    var modelClass = '';
                    $.each(tableName.split(/\.|\_/), function() {
                        if(this.length>0)
                            modelClass+=this.substring(0,1).toUpperCase()+this.substring(1);
                    });
                    $('#generator-modelclass').val(modelClass).blur();
                }
            });

            // model generator: translate model class to query class
            $('#model-generator #generator-modelclass').on('blur', function () {
                var modelClass = $(this).val();
                if (modelClass !== '') {
                    var queryClass = $('#generator-queryclass').val();
                    if (queryClass === '') {
                        queryClass = modelClass + 'Query';
                        $('#generator-queryclass').val(queryClass);
                    }
                }
            });

            // model generator: synchronize query namespace with model namespace
            $('#model-generator #generator-ns').on('blur', function () {
                var stickyValue = $('#model-generator .field-generator-queryns .sticky-value');
                var input = $('#model-generator #generator-queryns');
                if (stickyValue.is(':visible') || !input.is(':visible')) {
                    var ns = $(this).val();
                    stickyValue.html(ns);
                    input.val(ns);
                }
            });

            // model generator: toggle query fields
            $('form #generator-generatequery').change(function () {
                $('form .field-generator-queryns').toggle($(this).is(':checked'));
                $('form .field-generator-queryclass').toggle($(this).is(':checked'));
                $('form .field-generator-querybaseclass').toggle($(this).is(':checked'));
                $('#generator-queryclass').prop('disabled', $(this).is(':not(:checked)'));
            }).change();

            // hide message category when I18N is disabled
            $('form #generator-enablei18n').change(function () {
                $('form .field-generator-messagecategory').toggle($(this).is(':checked'));
            }).change();

            // hide Generate button if any input is changed
            $('#form-fields').find('input,select,textarea').change(function () {
                $('.default-view-results,.default-view-files').hide();
                $('.default-view button[name="generate"]').hide();
            });

            $('.module-form #generator-moduleclass').change(function () {
                var value = $(this).val().match(/(\w+)\\\w+$/);
                var $idInput = $('#generator-moduleid');
                if (value && value[1] && $idInput.val() === '') {
                    $idInput.val(value[1]);
                }
            });
        }
    };
})(jQuery);
