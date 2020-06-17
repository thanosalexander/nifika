<?php

namespace App\Logic\Template\Menu;

use App\MenuItem;

/** Implement this to have a menuManagerItem for a page. */
interface MenuPageInterface {
    
    /**
     * @return MenuManagerItem */
    public function menuManagerItem(MenuItem $menuItem);
    /**
     * @return string */
    public static function menuManagerClass();
    
}