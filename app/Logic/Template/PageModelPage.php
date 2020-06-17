<?php

namespace App\Logic\Template;

use App\Logic\Template\Menu\MenuManagerItem;
use App\Logic\Template\Menu\MenuPageInterface;
use App\MenuItem;
use App\Page;

/**
 * @property Page $model
 */
class PageModelPage extends WebPageAbstract implements MenuPageInterface {

    /** @var Page */
    protected $model;
    protected static $singletons = [];
    
    /**
     * @param MenuItem $menuItem
     * @return MenuManagerItem
     */
    public function menuManagerItem(MenuItem $menuItem) {
        return MenuManagerItem::getter($menuItem, $this);
    }
    
    /** Check if web page is enabled
     * @return MenuManagerItem */
    public function getMenuManagerItem(MenuItem $menu){
        return $this->menuManagerItem($menu);
    }

    public static function menuManagerClass() {
        return static::class;
    }

}
