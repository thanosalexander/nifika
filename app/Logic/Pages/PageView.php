<?php

namespace App\Logic\Pages;

use App\Logic\App\EntityManager;
use App\Page;

/** Helper for page view. 
 */
class PageView {
    
    public static $defaultPageViewName = 'page';

    /**  */
    public static function pageViewBasePath() {
        return 'pages.public';
    }
    
    /**  */
    public static function pageCustomViewDirectory() {
        return 'custom';
    }
    
    /** Defines if should be shown on frond end. */
    public static function getViewNameByModel(Page $model) {
        if(!empty($model->customView)){
            $viewCustomBaseName = static::pageCustomViewBasePath();
            return "{$viewCustomBaseName}.{$model->customView}";
        }
        
        return static::getViewNameByType($model->type);
    }
    
    /** Defines if should be shown on frond end. */
    public static function getViewNameByType($type) {
        $viewBaseName = static::pageViewBasePath();
        $viewName = PageType::pageTypeDefaultViewName($type);
        return "{$viewBaseName}.{$viewName}";
    }
    
    /**  */
    public static function pageCustomViewBasePath() {
        $viewBaseName = static::pageViewBasePath();
        $customViewDirectory = static::pageCustomViewDirectory();
        return "{$viewBaseName}.{$customViewDirectory}";
    }
    
    /**
     * @param Page $page
     * @return string */
    public static function adminListPageUrl(Page $page) {
        $routeBaseName = \View::shared('routeBaseName');
        return EntityManager::entityListUrl(EntityManager::PAGE, $page, EntityManager::PAGE);
    }

    /**
     * @param Page $page
     * @return string */
    public static function publicPageUrl(Page $page) {
        $webPage = $page->getMyWebPage();
        return $webPage->url();
    }
    
    /** Return all Page views.
     * @param boolean $asSelectList if true return array {view => label, ...} else {view, ...}
     * @return array */
    public static function allPageCustomViews($asSelectList = false) {
        $viewsSources = static::pageViewsSources();
        return (!$asSelectList ? array_keys($viewsSources) : static::convertPageViewSourcesToSelectList($viewsSources));
    }

    /** Return Page views as select list {view => label, ...}.
     * @param array $views
     * @param boolean $ordered if it is true add the order number before label
     * @return array */
    public static function convertPageViewSourcesToSelectList($views, $ordered = false) {
        return array_map(function($source) use ($ordered) {
            $prefix = ($ordered ? "{$source['listOrder']}. " : '');
            return "{$prefix}{$source['label']}";
        }, $views);
    }
    
    /** Return assoc array with source data for all page views
     * @return array */
    public static function pageViewsSources() {
        $transBaseName = \View::shared('transBaseName');
        $views = [
            'project' => [
                'label' => trans("{$transBaseName}.page.pageView.project"),
                'listOrder' => 1,
            ],
            'about' => [
                'label' => trans("{$transBaseName}.page.pageView.about"),
                'listOrder' => 2,
            ],
            'services' => [
                'label' => trans("{$transBaseName}.page.pageView.services"),
                'listOrder' => 3,
            ],
            'construction' => [
                'label' => trans("{$transBaseName}.page.pageView.construction"),
                'listOrder' => 4,
            ],
            'conctructionService' => [
                'label' => trans("{$transBaseName}.page.pageView.conctructionService"),
                'listOrder' => 5,
            ],
            'cosmetics' => [
                'label' => trans("{$transBaseName}.page.pageView.cosmetics"),
                'listOrder' => 6,
            ],
            'cosmeticService' => [
                'label' => trans("{$transBaseName}.page.pageView.cosmeticService"),
                'listOrder' => 7,
            ],
            'projects' => [
                'label' => trans("{$transBaseName}.page.pageView.projects"),
                'listOrder' => 8,
            ],
        ];
        return $views;
    }
}
