(function($) {
    var scripts = [],
        usageOutput = null,
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
        };

    $(document).on('ready', function() {
        usageOutput = $('#usageOutput');
        usageOutput.on('focus', function(event) {
            event.currentTarget.select();
        });

        var scriptElements = $('#scripts').find('.script');
        initializeScripts(scriptElements);
        generateOutput(scripts, usageOutput);

        scriptElements.on('click', function(event) {
            var script = $(event.currentTarget);
            scripts[script.data('script')] = !scripts[script.data('script')];
            script.find('input[type=checkbox]').first().prop('checked', scripts[script.data('script')]);
            script.toggleClass('checked', scripts[script.data('script')]);
            generateOutput(scripts, usageOutput);
        });
    });


})(jQuery);