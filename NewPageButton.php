<?php
/**
 * Page Buttons plugin
 *
 * @copyright (c) 2020 Cody Ernesti
 * @license GPLv2 or later (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @author  Cody Ernesti
 */

namespace dokuwiki\plugin\pagebuttons;
use dokuwiki\Menu\Item\AbstractItem;

/**
 * Class NewPageButton
 *
 * Implements the plugin's new button for DokuWiki's menu system
 *
 * @package dokuwiki\plugin\pagebuttons
 */
class NewPageButton extends AbstractItem {

    /** @var string icon file */
    protected $svg = __DIR__ . '/images/file-plus-outline.svg';

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
        return "New Page";
        //return $plugin->getLang('newpage_menu_item');
    }

}
