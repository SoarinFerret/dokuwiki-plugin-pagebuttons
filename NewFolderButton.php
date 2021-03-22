<?php
/**
 * New Folder Button plugin
 *
 * @copyright (c) 2020 Cody Ernesti
 * @license GPLv2 or later (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @author  Cody Ernesti
 */

namespace dokuwiki\plugin\pagebuttons;
use dokuwiki\Menu\Item\AbstractItem;

/**
 * Class NewFolderButton
 *
 * Implements the plugin's NewFolder button for DokuWiki's menu system
 *
 * @package dokuwiki\plugin\pagebuttons
 */
class NewFolderButton extends AbstractItem {

    /** @var string icon file */
    protected $svg = __DIR__ . '/images/folder-plus-outline.svg';

    /** @inheritdoc */
    public function __construct() {
        parent::__construct();
        $this->params['sectok'] = getSecurityToken();
    }

    /**
     * Get label from plugin language file
     *
     * @return string
     */
    public function getLabel() {
        $plugin = plugin_load('action', $this->type);
        return "New Folder";
        //return $plugin->getLang('newfolder_menu_item');
    }

    public function getLinkAttributes($classprefix = 'menuitem ') {
        $attr = parent::getLinkAttributes($classprefix);
        if (empty($attr['class'])) {
            $attr['class'] = '';
        }
        $attr['class'] .= ' plugin_pagebuttons_newfolder ';
        return $attr;
    }

}
