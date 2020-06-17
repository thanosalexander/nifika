<?php
namespace App\Logic\Template\Menu;

use App\Logic\Template\ContactPage;
use App\Logic\Template\Menu\MenuManagerItem;
use App\Logic\Template\Menu\Menus;
use App\Logic\Template\PageModelPage;
use App\Logic\Template\StartPage;
use App\MenuItem;
use Illuminate\Database\Eloquent\Collection;

/** MenuManager is used to build-print the menus. */
class MenuManager {

    /** @var int Holds the menu id. */
    protected $menuId;
    /** @var MenuManagerItem[] Holds the menu items. */
    protected $menuItems = null;

    /** Get the MenuManager.
     * @param int $menuId
     * @return static */
    public static function getter($menuId){
        if( !isset( Menus::$menus[$menuId] ) ){
            $menu = new static;
            $menu->menuId = $menuId;
            $menu->initMenuItems();
            Menus::$menus[$menuId] = $menu;
        }
        return Menus::$menus[$menuId];
    }

    /**
     * @param boolean $frontentVisible
     * @return MenuManagerItem[] */
    public function getTreeItems($frontentVisible = false){
        $treeItems = static::toTree($this->menuItems);
        if($frontentVisible) {
            $treeItems = static::filterItems($treeItems, $frontentVisible);
        }
        return $treeItems;
    }

    /** */
    protected function initMenuItems() {
        $items = $this->buildMenuItems();
        $this->menuItems = $items;
    }

    /**
     * @return MenuManagerItem[]
     */
    public function buildMenuItems() {
        //get menu item models
        $models = $this->getMenuItemModels();
        $menuItems = [];
        foreach ($models as $menuItem) {
            $menuItems[] = $this->initMenuItem($menuItem);
        }
        return $menuItems;
    }

    /**
     * @return Collection
     */
    protected function getMenuItemModels() {
        return MenuItem::with(['sourcePage'])->menuItems($this->menuId)->ordered()->get();
    }

    /**
     * @param MenuItem $itemModel
     * @return MenuManagerItem
     */
    public  function initMenuItem(MenuItem $itemModel) {
        switch ($itemModel->type) {
            case MenuItem::TYPE_PAGE:
                if(!is_null($itemModel->sourcePage)){
                    $webPage = $itemModel->sourcePage->getMyWebPage();
                    $menuManageItem = $webPage->getMenuManagerItem($itemModel);
                }
                return $menuManageItem;
            case MenuItem::TYPE_EXTERNAL_LINK:
            default:
                throw new \Exception('Unhandle menu item type ('.$itemModel->type.')!');
        }
    }

    /**
     * @param PageModelPage|StartPage|ContactPage $sourcePage
     */
    public function checkActiveFromSourcePage($sourcePage){
        $items = $this->menuItems;
        foreach($items as $item) {
            /* @var $item MenuManagerItem */
            if(get_class($sourcePage) === get_class($item->sourceWebPage())
               && $sourcePage->model()->id === $item->sourceWebPage()->model()->id){
                $item->setActive(true);
            } else {
                $item->setActive(false);
            }
        }
    }

    /**
     * @param MenuManagerItem[] $menuItems
     * @return MenuManagerItem[]
     */
    public static function toTree($menuItems) {
        $extractChildren = function ($parentId, $menuItems, $extractChildren) {
            $childrenItems = $menuItems->filter(function($item) use($parentId) {
                return (is_null($parentId) ? is_null($item->parentId()) : intval($item->parentId()) === intval($parentId));
            })
            ->values()
            ->map(function(MenuManagerItem $item) use($menuItems, $extractChildren) {
                $item->setChildren($extractChildren($item->id(), $menuItems, $extractChildren));
                return $item;
            })->toArray();
            return $childrenItems;
        };
        $menuTree = $extractChildren(null, collect($menuItems), $extractChildren);
        return $menuTree;
    }

    /** Filters items, and recursively its children, depends on given parameters.
     * @param array $itemsTree array with items
     * @param array $frontendVisible filters items to show only visible
     * @param boolean $showItemIfHasVisibleChildren if it is true makes item visible if has visible children
     * @param boolean $bubbleActive if it is true marks as active all anscestors of the active item
     * @return array
     */
    public static function filterItems($itemsTree, $frontendVisible = false, $showItemIfHasVisibleChildren = false, $bubbleActive = true) {
        $checkActive = function($item, $checkActive, $bubbleActive = true){
            if($item->hasChildren()){
                $activeSubItems = array_filter($item->children(), function($subItem) use ($checkActive, $bubbleActive){
                    return $checkActive($subItem, $checkActive, $bubbleActive);
                });
                if($bubbleActive && !$item->isActive() && count($activeSubItems) > 0 ){
                    $item->setActive(true);
                }
            }
            return $item->isActive();
        };

        $filterShown = function($items, $filterShown, $showItemIfHasVisibleChildren = true){
            $shownItems = [];
            foreach($items as $item){
                if($item->hasChildren()){
                    $shownChildren = $filterShown($item->children(), $filterShown, $showItemIfHasVisibleChildren);
                    $item->setChildren($shownChildren);
                    if($showItemIfHasVisibleChildren && !$item->isVisible() && $item->hasChildren()){
                        $item->setVisible(true);
                    }
                }
                if($item->isVisible()){
                    $shownItems[] = $item;
                }
            }
            return $shownItems;
        };

        $filteredItems = $itemsTree;
        if($frontendVisible){
            $filteredItems = $filterShown($itemsTree, $filterShown, $showItemIfHasVisibleChildren);
        }
        foreach($filteredItems as $item){
            $item->setActive($checkActive($item, $checkActive, $bubbleActive));
        }
        return $filteredItems;
    }

}
