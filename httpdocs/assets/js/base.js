(function($) {
    var scripts = [],
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
            var code = '<include url="http://maniascript-collection.mania-community.de/',
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

        filterInput.on('keyup', function(event) {
            filter(scriptElements, $(event.currentTarget).val());
        });
        filterInput.on('search', function(event) {
            filter(scriptElements, $(event.currentTarget).val());
        });

        scriptElements.on('click', function(event) {
            var script = $(event.currentTarget);
            scripts[script.data('script')] = !scripts[script.data('script')];
            script.find('input[type=checkbox]').first().prop('checked', scripts[script.data('script')]);
            script.toggleClass('checked', scripts[script.data('script')]);
            generateOutput(scripts, usageOutput);
        });
        scriptElements.find('.info-icon').on('click', function(event) {
            var legend = $(event.currentTarget).parent().find('.legend');
            if (legend.hasClass('hidden')) {
                legend.slideDown();
                legend.removeClass('hidden');
            } else {
                legend.slideUp();
                legend.addClass('hidden');
            }
            event.stopPropagation();
        });
        scriptElements.find('a').on('click', function(event) {
            event.stopPropagation();
        });
    });
})(jQuery);