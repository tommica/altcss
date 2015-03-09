'use strict';

(function() {
    var editor = CodeMirror.fromTextArea(document.getElementById("extracss"), {
        lineNumbers: true,
        mode: "css",
        theme: "mbo",
        autoCloseBrackets: true,
        styleActiveLine: true,
        extraKeys: {
            "Ctrl-Space": "autocomplete",

            "F11": function(cm) {
                cm.setOption("fullScreen", !cm.getOption("fullScreen"));
            },
            "Esc": function(cm) {
                if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
            }
        }
    });
}());