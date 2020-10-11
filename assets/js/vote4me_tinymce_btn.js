(function() {
    tinymce.create('tinymce.plugins.vote4me', {
        init: function(ed, url) {
            ed.addButton('vote4me', {
                title: 'Insert Poll',
                image: url + '/epoll.png',
                onclick: function() {
                    var poll_id = prompt("Enter Poll ID", "");

                    if (poll_id != null && poll_id != '') {
                        ed.execCommand('mceInsertContent', false, '[VOTE4ME id="' + poll_id + '"][/VOTE4ME]');
                    } else {
                        ed.execCommand('mceInsertContent', false, '[VOTE4ME id="1"][/VOTE4ME]');
                    }
                }
            });
        },
        createControl: function(n, cm) {
            return null;
        },
        getInfo: function() {
            return {
                longname: "VOTE4ME WP VOTING",
                author: 'InfoTheme',
                authorurl: 'http://www.infotheme.in',
                infourl: 'http://infotheme.in/products/plugins/epoll-wp-voting-system/',
                version: "2.0"
            };
        }
    });
    tinymce.PluginManager.add('vote4me', tinymce.plugins.vote4me);
})();