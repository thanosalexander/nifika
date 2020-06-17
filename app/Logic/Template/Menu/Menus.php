<?php
namespace App\Logic\Template\Menu;

/** Handles the MenuManagers for actions 
 * that have to handle all menus. */
class Menus {
    
    /** The id of the main menu */
    const MENU_ID_MAIN = 1;
    /** The id of the top menu */
    const MENU_ID_TOP = 2;
    
    /** @var array Holds menu objects based on type. */
    public static $menus = [];
    
    /** Sets the active menu Item using the class and id.
     * @param string $objectClass ex: StartPage , Product
     * @param string $objectId ex: '' , 1  
     * @return boolean Whether the menuItem was found or not. */
    public static function setActive($objectClass, $objectId=''){
        foreach (static::$menus as $menuId => $menu){
            $res = MenuManager::getter($menuId)->setActive($objectClass, $objectId);
            if($res){
                return true;
            }
        }
        return false;
    }
}
