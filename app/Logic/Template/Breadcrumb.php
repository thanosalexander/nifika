<?php

namespace App\Logic\Template;

use App\Logic\App\EntityManager;
use App\Page;
use Illuminate\Support\Collection;
use function collect;

/** . */
class Breadcrumb {
    /**
     *
     * @var Collection
     */
    protected $items;
    
    protected $firstLinkOn = true;
    protected $lastLinkOn = true;
    protected $firstItemVisible = true;
    protected $lastItemVisible = true;

    /**
     * @return \static
     */
    public static function _get() {
        $obj = new static();
        return $obj;
    }
    
    protected function initPageAdminBreadcrumbConfiguration() {
        $this->firstLinkOn = true;
        $this->lastLinkOn = false;
        $this->firstItemVisible = true;
        $this->lastItemVisible = true;
    }
    
    protected function initPagePublicBreadcrumbConfiguration() {
        $this->firstLinkOn = true;
        $this->lastLinkOn = false;
        $this->firstItemVisible = true;
        $this->lastItemVisible = true;
    }
    
    /**
     * @param type $entityName
     * @param type $model
     * @param type $actionTitle
     */
    public function createAdminEntityBreadcrumb($entityName, $model = null, $actionTitle = null) {
        switch($entityName){
            case EntityManager::PAGE:
            case EntityManager::ARTICLE:
                $this->createPageAdminBreadcrumb($entityName, $model, $actionTitle);
                break;
        }
    }
    
    /** Set the model from which web page get data. */
    public function createPageAdminBreadcrumb($entityName, Page $page = null, $actionTitle = null) {
        $this->initPageAdminBreadcrumbConfiguration();
        $breadcrumbItems = collect();
        $rootItem = BreadcrumbItem::adminEntityRootItem($entityName);
        $breadcrumbItems->push($rootItem);
        if (!is_null($page)) {
            $ancestors = Page::getPageAncestors($page);
            foreach ($ancestors as $ancestorPage) {
                $breadcrumbItems->push(BreadcrumbItem::_getAdminEntityItem($entityName, $ancestorPage));
            }
            $breadcrumbItems->push(BreadcrumbItem::_getAdminEntityItem($entityName, $page));
        }
        
        if (!is_null($actionTitle)) {
            $actionItem = BreadcrumbItem::_get($actionTitle, true, null);
            $breadcrumbItems->push($actionItem);
            $this->lastLinkOn = false;
        }
        
        if($breadcrumbItems->count() === 1){
            $this->lastLinkOn = false;
        }
        $this->items = $breadcrumbItems;
        $this->applyConfiguration();
        
    }
    
    /** Set the model from which web page get data. */
    public function createPublicPageBreadcrumb(WebPageInterface $webPage = null, $actionTitle = null) {
        $this->initPagePublicBreadcrumbConfiguration();
        $isRoot = (is_null($webPage) || BreadcrumbItem::isPublicPageRoot($webPage));
        $breadcrumbItems = collect();
        $rootItem = BreadcrumbItem::publicPageRootItem();
        $breadcrumbItems->push($rootItem);
        $page = $webPage->model();
        if (!$isRoot && !is_null($page)) {
            $ancestors = Page::getPageAncestors($page);
            foreach ($ancestors as $ancestorPage) {
                $breadcrumbItems->push(BreadcrumbItem::_getPublicPageItem($ancestorPage));
            }
            $currentItem = BreadcrumbItem::_getPublicPageItem($page);
            $currentItem->setIsCurrent(true);
            $breadcrumbItems->push($currentItem);
        }
        
        if (!is_null($actionTitle)) {
            $actionItem = BreadcrumbItem::_get($actionTitle, true, null);
            $breadcrumbItems->push($actionItem);
            $this->lastLinkOn = false;
        }
        
        if($breadcrumbItems->count() === 1){
            $this->lastLinkOn = false;
        }
        $this->items = $breadcrumbItems;
        $this->applyConfiguration();
    }

    /** Set the model from which web page get data. */
    public function applyConfiguration() {
        if(!$this->firstItemVisible){
            $this->items->shift();
        }
        if(!$this->firstLinkOn && !is_null($this->items->first())){
            $this->items->first()->disableLink();
        }
        if(!$this->lastItemVisible){
            $this->items->pop();
        }
        if(!$this->lastLinkOn && !is_null($this->items->last())){
            $this->items->last()->disableLink();
        }
    }

    /** Set the model from which web page get data. */
    public function hideFirstItem() {
        $this->firstItemVisible = false;
    }
    
    public function showFirstItem() {
        $this->firstItemVisible = true;
    }
    public function hideLastItem() {
        $this->lastItemVisible = false;
    }
    
    public function showLastItem() {
        $this->lastItemVisible = true;
    }
    public function enableFirstItemLink() {
        $this->firstLinkOn = true;
    }
    public function disableFirstItemLink() {
        $this->firstLinkOn = false;
    }
    public function enableLastItemLink() {
        $this->lastLinkOn = true;
    }
    public function disableLastItemLink() {
        $this->lastLinkOn = false;
    }
    
    public function getItems(){
        return $this->items;
    }

}
