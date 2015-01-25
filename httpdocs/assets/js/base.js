(function($) {
    var scripts = [],
        addressTimeout = null,
        initializeScripts = function(elements) {
            elements.each(function(index, element) {
                var script = $(element),
                    scriptName = script.data('script'),
                    value = script.find('input[type=checkbox]').first().prop('checked');
                scripts[scriptName] = value;
                script.toggleClass('checked', value);
            });
        },
        generateOutput = function(scripts, target) {
            var scriptUrl = target.data('script-url'),
                code = '<include url="' + scriptUrl,
                first = true;
            for (var key in scripts) {
                if (scripts.hasOwnProperty(key) && scripts[key] === true) {
                    if (first) {
                        first = false;
                        code += '?';
                    } else {
                        code += ',';
                    }
                    code += key;
                }
            }
            code += '" />';
            target.val(code);
        },
        filter = function(scriptElements, term) {
            scriptElements.each(function(index, element) {
                var script = $(element),
                    scriptName = script.data('script'),
                    isFiltered = scriptName.indexOf(term) == -1;

                if (isFiltered) {
                    script.slideUp();
                } else {
                    script.slideDown();
                }
            });
        },
        changeFilter = function(value) {
            if (addressTimeout !== null) {
                window.clearTimeout(addressTimeout);
            }
            addressTimeout = window.setTimeout(function() {
                $.address.value(value);
            }, 250);
        },
        toggleInfo = function(target) {
            var legend = target.find('.legend');
            if (legend.hasClass('hidden')) {
                legend.slideDown();
                legend.removeClass('hidden');
            } else {
                legend.slideUp();
                legend.addClass('hidden');
            }
        };

    $(document).on('ready', function() {
        var usageOutput = $('#usageOutput'),
            filterInput = $('#filterInput'),
            scriptElements = $('#scripts').find('.script');

        initializeScripts(scriptElements);
        generateOutput(scripts, usageOutput);

        usageOutput.on('focus', function(event) {
            event.currentTarget.select();
        });

        $.address.change(function(event) {
            var value = event.value.replace(/^\/*/, '');
            filter(scriptElements, value);
            filterInput.val(value);
        });

        filterInput.on('keyup', function(event) {
            changeFilter($(event.currentTarget).val());
        });
        filterInput.on('search', function(event) {
            changeFilter($(event.currentTarget).val());
        });

        scriptElements.on('click', function(event) {
            var script = $(event.currentTarget);
            if (event.shiftKey) {
                toggleInfo(script);
            } else {
                scripts[script.data('script')] = !scripts[script.data('script')];
                script.find('input[type=checkbox]').first().prop('checked', scripts[script.data('script')]);
                script.toggleClass('checked', scripts[script.data('script')]);
                generateOutput(scripts, usageOutput);
            }
        });
        scriptElements.find('.info-icon').on('click', function(event) {
            toggleInfo($(event.currentTarget).parent());
            event.stopPropagation();
        });
        scriptElements.find('a').on('click', function(event) {
            event.stopPropagation();
        });
    });
})(jQuery);