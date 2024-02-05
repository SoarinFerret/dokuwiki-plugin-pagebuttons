/**
 * Page Buttons plugin script
 * 
 * @copyright (c) 2020 Cody Ernesti
 * @license GPLv2 or later (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @author  Cody Ernesti
 *
 *  Modified from: https://github.com/dregad/dokuwiki-plugin-deletepagebutton
 *
 *   Original license info:
 *
 * @copyright (c) 2020 Damien Regad
 * @license GPLv2 or later (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @author  Damien Regad
 */
jQuery(function() {
    var usePrompt = 0;
    if(JSINFO && JSINFO['plugin_pagebuttons']){
        var usePrompt = JSINFO['plugin_pagebuttons']['usePrompt'];
        var sepchar = JSINFO['plugin_pagebuttons']['sepchar'];
        var start = JSINFO['plugin_pagebuttons']['start'];
        var useSlash = JSINFO['plugin_pagebuttons']['useslash'];
        var urlSeparator = useSlash ? "/" : ":";
    }

    jQuery('.plugin_pagebuttons_deletepage').click(function(d) {
        d.preventDefault();

        var submit_url = this.href;
        if(usePrompt){
            var page = window.confirm(LANG.plugins.pagebuttons.delete_confirm);
            if(page == null || page == ''){} 
            else{ 
                window.location.href = submit_url;
            }
        }else{
            var $dialog = jQuery(
                '<div><span>'
                + LANG.plugins.pagebuttons.delete_confirm
                + '</span></div>'
            );
            $dialog.dialog({
                title: LANG.plugins.pagebuttons.delete_title,
                resizable: true,
                width: "auto",
                height: "auto",
                modal: true,
                buttons: [
                    {
                        text: LANG.plugins.pagebuttons.btn_ok,
                        click: function () {
                            $dialog.dialog("close");
                            window.location.href = submit_url
                        }
                    },
                    {
                        text: LANG.plugins.pagebuttons.btn_cancel,
                        click: function () {
                            $dialog.dialog("close");
                        }
                    }
                ],
                close: function () {
                    // remove the dialog's HTML
                    jQuery(this).remove();
                    // Due to the preventDefault() call, the "Delete page" span
                    // remains active when the dialog is closed, so we need to
                    // manually remove focus from it.
                    document.activeElement.blur();
                }
            });
        }
    });

    jQuery('.plugin_pagebuttons_newfolder').click(function(f) {
        f.preventDefault();

        var pre_url = useSlash
            ? window.location.href.substring(0, window.location.href.indexOf(JSINFO['id'].replace(/:/g, '/'))) + JSINFO['namespace'].replace(/:/g, '/')
            : window.location.href.substring(0, window.location.href.indexOf(JSINFO['id'])) + JSINFO['namespace'];

        if(usePrompt){
            var page = window.prompt(LANG.plugins.pagebuttons.newfolder_prompt);
            if(page == null || page == ''){} 
            else{
                var submit_url = pre_url + urlSeparator + page + urlSeparator + start + "&do=edit";
                window.location.href = submit_url;
            }
        }else{
            var $dialog = jQuery(
                '<div><span>'
                + LANG.plugins.pagebuttons.newfolder_prompt
                + '<br /><input type="text" style="z-index:10000" name="new_folder_name"><br />'
                + '</span></div>'
            );
            $dialog.dialog({
                title: LANG.plugins.pagebuttons.newfolder_title,
                resizable: true,
                width: "auto",
                height: "auto",
                modal: true,
                buttons: [
                    {
                        text: LANG.plugins.pagebuttons.btn_ok,
                        click: function () {
                            var folder = document.getElementsByName("new_folder_name")[0].value;
                            folder = folder.replace(" ", sepchar);
                            folder = folder.replace(/:+/g, ":");
                            folder = folder.replace(/^:+/, "");
                            folder = folder.replace(/:+$/, "");
                            folder = folder.replace(/:/g, urlSeparator);
                            $dialog.dialog("close");
                            var submit_url = pre_url + urlSeparator + folder + urlSeparator + start + "&do=edit";
                            window.location.href = submit_url
                        }
                    },
                    {
                        text: LANG.plugins.pagebuttons.btn_cancel,
                        click: function () {
                            $dialog.dialog("close");
                        }
                    }
                ],
                close: function () {
                    // remove the dialog's HTML
                    jQuery(this).remove();
                    // Due to the preventDefault() call, the "Delete page" span
                    // remains active when the dialog is closed, so we need to
                    // manually remove focus from it.
                    document.activeElement.blur();
                }
            });
        }
    });

    jQuery('.plugin_pagebuttons_newpage').click(function(p) {
        p.preventDefault();
        
        var pre_url = useSlash
            ? window.location.href.substring(0, window.location.href.indexOf(JSINFO['id'].replace(/:/g, '/'))) + JSINFO['namespace'].replace(/:/g, '/')
            : window.location.href.substring(0, window.location.href.indexOf(JSINFO['id'])) + JSINFO['namespace'];

        if(usePrompt){
            var page = window.prompt(LANG.plugins.pagebuttons.newpage_prompt);
            if(page == null || page == ''){} 
            else{
                var submit_url = pre_url + urlSeparator + page + "&do=edit";
                window.location.href = submit_url;
            }
        }else{
            var $dialog = jQuery(
                '<div><span>'
                + LANG.plugins.pagebuttons.newpage_prompt
                + '<br /><input type="text" style="z-index:10000" name="new_page_name"><br />'
                + '</span></div>'
            );
            $dialog.dialog({
                title: LANG.plugins.pagebuttons.newpage_title,
                resizable: true,
                width: "auto",
                height: "auto",
                modal: true,
                buttons: [
                    {
                        text: LANG.plugins.pagebuttons.btn_ok,
                        click: function () {
                            var newpage = document.getElementsByName("new_page_name")[0].value;
                            newpage = newpage.replace(" ", sepchar);
                            newpage = newpage.replace(/:+/g, ":");
                            newpage = newpage.replace(/^:+/, "");
                            newpage = newpage.replace(/:+$/, "");
                            newpage = newpage.replace(/:/g, urlSeparator);
                            $dialog.dialog("close");
                            var submit_url = pre_url + urlSeparator + newpage + "&do=edit";
                            window.location.href = submit_url
                        }
                    },
                    {
                        text: LANG.plugins.pagebuttons.btn_cancel,
                        click: function () {
                            $dialog.dialog("close");
                        }
                    }
                ],
                close: function () {
                    // remove the dialog's HTML
                    jQuery(this).remove();
                    // Due to the preventDefault() call, the "Delete page" span
                    // remains active when the dialog is closed, so we need to
                    // manually remove focus from it.
                    document.activeElement.blur();
                }
            });
        }
    });
});
