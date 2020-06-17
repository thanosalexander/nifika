<?php

namespace App\Logic\Template;

use App\Logic\App\EntityManager;
use App\Logic\Base\BaseModel;
use App\Logic\Pages\PageView;
use App\Page;

/** The contact page feature. */
class BreadcrumbItem {

    /** @var \static */
    protected static $singleton = null;

    /** @var Page|null */
    protected $title = null;
    protected $isLink = false;
    protected $isCurrent = false;
    protected $url = null;

    /** @param Page $page
     * @return \static
     */
    public static function _get($title, $isLink = true, $url = null) {
        $obj = new static();
        $obj->title = $title;
        $obj->isLink = $isLink;
        $obj->url = $url;
        return $obj;
    }
    
    /** @param BaseModel $page
     * @return \static */
    public static function _getAdminEntityItem($entityName, $model) {
        $transBaseName = myApp()->getConfig('adminTransBaseName');
        switch ($entityName) {
            case EntityManager::ARTICLE:
            case EntityManager::PAGE:
            default:
                return static::_get($model->getMyName(), $model->canHaveSubPages(auth()->user()), PageView::adminListPageUrl($model));
        }
    }

    /** @return \static */
    public static function adminEntityRootItem($entityName) {
        $transBaseName = myApp()->getConfig('adminTransBaseName');
        $routeBaseName = myApp()->getConfig('adminRouteBaseName');

        switch ($entityName) {
            case EntityManager::PAGE:
            case EntityManager::ARTICLE:
            default:
                $title = EntityManager::entityLabel($entityName);
                $url = EntityManager::entityListUrl($entityName);
                break;
        }
        return static::_get($title, true, $url);
    }

    /** @return \static */
    public static function adminPageRootItem() {
        return static::adminEntityRootItem(EntityManager::PAGE);
    }
    
    /** @param Page $page
     * @return \static */
    public static function _getPublicPageItem(Page $page) {
        return static::_get($page->getMyName(), true, PageView::publicPageUrl($page));
    }
    
    /** @return \static */
    public static function publicPageRootItem() {
        $startPage = StartPage::get();
        return static::_get($startPage->title(), true, $startPage->url());
    }
    
    /** @return \static */
    public static function isPublicPageRoot($webPage) {
        return ($webPage instanceof StartPage);
    }

    /** 
     * @return string */
    public function title() {
        return $this->title;
    }

    /** 
     * @return boolean */
    public function isLink() {
        return $this->isLink;
    }
    
    /** 
     * @return boolean */
    public function isCurrent() {
        return $this->isCurrent;
    }

    /** 
     * @return string */
    public function url() {
        return $this->url;
    }

    /** Get web page`s content
     * @return string */
    public function getView($viewName) {
        $transBaseName = myApp()->getConfig('adminViewBasePath');
        return view($viewName, ['breadcrumbItem' => $this])->render();
    }

    public function disableLink() {
        $this->setIsLink(false);
    }

    public function enableLink() {
        $this->setIsLink(true);
    }
    
    public function setIsLink($boolean) {
        $this->isLink = boolval($boolean);
    }
    
    public function setIsCurrent($boolean) {
        $this->isCurrent = boolval($boolean);
    }

}
