<?php

namespace App\Logic\Template\Menu;

/**
 * Description of UserMenu
 *
 * @author thanasis
 */
class PageMenu {
    
    /**
     * @param int $menuId
     * @return MenuManager
     */
    public static function initMenu($menuId) {
        $menuManager = MenuManager::getter($menuId);
        return $menuManager;
    }
}
