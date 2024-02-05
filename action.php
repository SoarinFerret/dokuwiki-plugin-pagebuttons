<?php
/**
 * Page Buttons plugin
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

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

use dokuwiki\plugin\pagebuttons\DeletePageButton;
use dokuwiki\plugin\pagebuttons\NewPageButton;
use dokuwiki\plugin\pagebuttons\NewFolderButton;

/**
 * Class action_plugin_pagebuttons
 *
 * @package dokuwiki\plugin\pagebuttons
 */
class action_plugin_pagebuttons extends DokuWiki_Action_Plugin {

    /**
     * Register event handlers.
     *
     * @param Doku_Event_Handler $controller The plugin controller
     */
    public function register(Doku_Event_Handler $controller) {
        $controller->register_hook('DOKUWIKI_STARTED', 'AFTER', $this, 'addjsinfo');
        $controller->register_hook('MENU_ITEMS_ASSEMBLY', 'AFTER', $this, 'addNewPageButton' );
        $controller->register_hook('MENU_ITEMS_ASSEMBLY', 'AFTER', $this, 'addNewFolderButton' );
        $controller->register_hook('MENU_ITEMS_ASSEMBLY', 'AFTER', $this, 'addDeleteButton' );
        $controller->register_hook('ACTION_ACT_PREPROCESS', 'BEFORE', $this, 'actionPage' );
    }

    
     /**
     * Adds details to JSINFO
     *
     */
    function addjsinfo($event, $params){
        global $JSINFO;
        global $conf;
        $JSINFO['plugin_pagebuttons'] = array(
            'usePrompt' => $this->getConf('usePrompt'),
            'sepchar' => $conf['sepchar'],
            'useslash' => $conf['useslash'],
            'start' => $conf['start']
        );
    }

    /**
     * Hook for MENU_ITEMS_ASSEMBLY event.
     *
     * Adds 'Delete' button to DokuWiki's PageMenu.
     *
     * @param Doku_Event $event
     */
    public function addDeleteButton(Doku_Event $event) {
        global $ID;

        if (
            $event->data['view'] !== 'page'
            || $this->getConf('hideDelete')
            || !$this->canDelete($ID)
        ) {
            return;
        }

        array_splice($event->data['items'], -1, 0, array(new DeletePageButton($this->getLang('delete_menu_item'))));
    }

    /**
     * Hook for MENU_ITEMS_ASSEMBLY event.
     *
     * Adds 'New Page' button to DokuWiki's PageMenu.
     *
     * @param Doku_Event $event
     */
    public function addNewPageButton(Doku_Event $event) {
        global $ID;
        global $conf;

        if (
            $event->data['view'] !== 'page'
            || $this->getConf('hideNewPage')
            || !page_exists($ID)
            || ($this->getConf('onlyShowNewButtonsOnStart') && !(substr_compare($ID, ":".$conf['start'], -strlen(":".$conf['start'])) === 0))
        ) {
            return;
        }

        array_splice($event->data['items'], -1, 0, array(new NewPageButton($this->getLang('newpage_menu_item'))));
    }

    /**
     * Hook for MENU_ITEMS_ASSEMBLY event.
     *
     * Adds 'New Page' button to DokuWiki's PageMenu.
     *
     * @param Doku_Event $event
     */
    public function addNewFolderButton(Doku_Event $event) {
        global $ID;
        global $conf;

        if (
            $event->data['view'] !== 'page'
            || $this->getConf('hideNewFolder')
            || !page_exists($ID)
            || ($this->getConf('onlyShowNewButtonsOnStart') && !(substr_compare($ID, ":".$conf['start'], -strlen(":".$conf['start'])) === 0))
        ) {
            return;
        }

        array_splice($event->data['items'], -1, 0, array(new NewFolderButton($this->getLang('newfolder_menu_item'))));
    }

    /**
     * Determines whether the Delete button should be shown.
     *
     * @param $id
     * @return bool
     */
    protected function canDelete($id) {
        global $ACT;

        return ($ACT == 'show' || empty($ACT))
            && page_exists($id)
            && auth_quickaclcheck($id) >= AUTH_EDIT
            && checklock($id) === false && !@file_exists(wikiLockFN($id));
    }

    /**
     * Hook for ACTION_ACT_PREPROCESS event.
     *
     * Handles the plugin's custom page deletion action: deletes the page and
     * redirects to page view ('show' action).
     *
     * @param Doku_Event $event
     */
    public function actionPage(Doku_Event $event) {
        global $ID, $INFO, $lang;

        // Ignore other actions
        if ($event->data != 'deletepagebutton' && $event->data != 'newfolderbutton' && $event->data != 'newpagebutton') {
            return;
        };

        if(checkSecurityToken() && $INFO['exists']) {
            if($event->data == 'deletepagebutton'){
                // Save the page with empty contents to delete it
                saveWikiText($ID, null, $lang['deleted']);

                // Display confirmation message
                msg($this->getLang('deleted_ok'), 1);
            }
        }

        // Redirect to page view
        $event->data = 'redirect';
    }

}
