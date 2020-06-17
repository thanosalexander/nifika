<?php

namespace App\Logic\Template;

use App\Logic\Pages\PageOrderType;
use App\Logic\Pages\PageView;
use App\Logic\Template\Menu\MenuManagerItem;
use App\Logic\Template\Menu\MenuPageInterface;
use App\MenuItem;
use App\Page;
use App\Setting;

/** The start page feature. */
class StartPage extends StaticWebPageAbstract implements MenuPageInterface {
    
    /** @var \static */
    protected static $singleton = null;
    
    /** @var Page|null */
    protected $model = null;
    
    /** Set the model from which get data. */
    protected function setModel(Page $model = null) {
        $this->model = (!is_null($model)
                ? $model
                : Page::whereType(Page::TYPE_HOME)->first());
    }
    
    /** Check if web page is enabled
     * @return MenuManagerItem */
    public function getMenuManagerItem(MenuItem $menu){
        return $this->menuManagerItem($menu);
    }

    /** Get web page`s title
     * @return string */
    public function title() {
        return is_null($this->model) ? trans('public.startPageTitle') : $this->model->title;
    }
    
    /** Get web page`s url
     * @return string */
    public function url(array $parameters = []) {
        return (is_null($this->model) ? route('public.home', $parameters) : parent::url($parameters));
    }
    
    /** Get page`s view name
     * @return string */
    public function viewName(){
        return (!is_null($this->model) 
            ? PageView::getViewNameByModel($this->model)
            : PageView::getViewNameByType(Page::TYPE_HOME)
        );
    }

    public function menuManagerItem(MenuItem $menuItem) {
        return MenuManagerItem::getter($menuItem, $this);
    }

    public static function menuManagerClass() {
        return static::class;
    }
    
}
