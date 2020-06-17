<?php

namespace App\Logic\Template;

use App\Logic\Template\Menu\MenuManagerItem;
use App\Logic\Template\Menu\MenuPageInterface;
use App\Logic\Pages\PageView;
use App\MenuItem;
use App\Page;
use App\Setting;

/** The contact page feature. */
class ContactPage extends StaticWebPageAbstract implements MenuPageInterface {
    
    /** @var \static */
    protected static $singleton = null;
    
    /** @var Page|null */
    protected $model = null;
    
    /** Set the model from which web page get data. */
    protected function setModel(Page $model = null) {
        $this->model = (!is_null($model)
                ? $model
                : Page::whereType(Page::TYPE_CONTACT)->first());
    }
    
    /** Check if web page is enabled
     * @return MenuManagerItem */
    public function getMenuManagerItem(MenuItem $menu){
        return $this->menuManagerItem($menu);
    }

    /** check if web page is enabled
     * @return boolean */
    public function enabled(){
        return (ss(Setting::SS_CONTACT_PAGE_ENABLED) == Page::ENABLED_YES);
    }

    /** Get web page`s title
     * @return string */
    public function title() {
        return is_null($this->model) ? trans('public.contact.pageTitle') : $this->model->title;
    }
    
    /** Get web page`s content
     * @return string */
    public function content() {
        return is_null($this->model) ? trans('public.contact.pageTitle') : $this->model->content;
    }

    /** Get web page`s url
     * @return string */
    public function url(array $parameters = []) {
        return is_null($this->model) ? '#' : $this->model->url($parameters);
    }
    
    /** Get page`s view name
     * @return string */
    public function viewName(){
        return (!is_null($this->model) 
            ? PageView::getViewNameByModel($this->model)
            : PageView::getViewNameByType(Page::TYPE_CONTACT)
        );
    }
    
    public function menuManagerItem(MenuItem $menuItem) {
        return MenuManagerItem::getter($menuItem, $this);
    }
    public static function menuManagerClass() {
        return static::class;
    }

}
