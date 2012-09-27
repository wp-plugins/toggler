/* CSS for Toggler Plugin
 * Written by Nimrod Tsabari
 * Since Version 0.1b
 */
(function() {
    tinymce.create('tinymce.plugins.togglerplugin', {
        init : function(ed, url) {
		   ed.addCommand('togglerAct', function() {
                ed.windowManager.open({
                        file : url + '/toggler.htm',
                        width : 465,
                        height : 450,
                        inline : 1
                }, {
                        plugin_url : url, // Plugin absolute URL
                        some_custom_arg : 'custom arg' // Custom argument
                });
	        });          
	        ed.addButton('togglerplugin', {
                title : 'Add A Toggler Box',
                image : url + '/img/toggler.png',
                cmd : 'togglerAct',
            });
        },
        createControl : function(n, cm) {
            return null;
        },
        getInfo : function() {
            return {
                longname : "Toggler",
                author : 'Nimrod Tsabari',
                authorurl : 'http://dev.nimrodtsabari.com/',
                version : "1.0"
            };
        }
    });
    tinymce.PluginManager.add('togglerplugin', tinymce.plugins.togglerplugin);
})();