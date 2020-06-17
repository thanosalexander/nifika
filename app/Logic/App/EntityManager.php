<?php

namespace App\Logic\App;

use App\Logic\Pages\ArticleDatatable;
use App\Logic\Pages\PageDatatable;
use App\Page;
use App\User;

/** Holds information of the App */
class EntityManager {
    
    const PAGE = 'page';
    const ARTICLE = 'article';
    
    public static function entityLabel($entityName) {
        $entitySource = static::entitySources($entityName);
        $label = ((array_key_exists('label', $entitySource))
                ? $entitySource['label']
                : ucfirst($entityName));
        return $label;
    }
    
    public static function addEditPageTitle($entityName) {
        $entitySource = static::entitySources($entityName);
        $label = ((array_key_exists('addPageTitle', $entitySource))
                ? $entitySource['addPageTitle']
                : ucfirst($entityName));
        return $label;
    }
    public static function entityEditPageTitle($entityName) {
        $entitySource = static::entitySources($entityName);
        $label = ((array_key_exists('editPageTitle', $entitySource))
                ? $entitySource['editPageTitle']
                : ucfirst($entityName));
        return $label;
    }
    
    public static function entityDatatableManager($entityName) {
        $entitySource = static::entitySources($entityName);
        $class = ((array_key_exists('datatableManager', $entitySource)
                && !is_null($entitySource['datatableManager'])
                && class_exists($entitySource['datatableManager']))
                ? $entitySource['datatableManager']
                : null);
        return $class;
    }
    
    public static function entityRefererModel($entityName) {
        $entitySource = static::entitySources($entityName);
        $label = ((array_key_exists('refererModel', $entitySource))
                ? $entitySource['refererModel']
                : ucfirst($entityName));
        return $label;
    }
    
    public static function resolveModelToEntity($modelClass) {
        $entitiesSource = static::entitiesSources();
        $referedModels = array_filter(array_map(function($entitySource){
            return ((array_key_exists('refererModel', $entitySource))
                ? $entitySource['refererModel']
                : null);
        }, $entitiesSource));
        $entity = array_search($modelClass, $referedModels);
        
        return ($entity === false ? null : $entity);
    }
    
    public static function entityListUrl($entityName, $entityModel = null, $relationEntityName = null) {
        $routeBaseName = myApp()->getConfig('adminRouteBaseName');
        return route("{$routeBaseName}.entity.list", [$entityName, (!is_null($entityModel) ? $entityModel->id : null), $relationEntityName]);
    }
    
    public static function entityAddUrl($entityName, $entityModel = null, $relationEntityName = null) {
        $routeBaseName = myApp()->getConfig('adminRouteBaseName');
        return route("{$routeBaseName}.entity.create", [$entityName, (!is_null($entityModel) ? $entityModel->id : null), $relationEntityName]);
    }
    
    public static function entityStoreUrl($entityName, $entityModel = null, $relationEntityName = null) {
        $routeBaseName = myApp()->getConfig('adminRouteBaseName');
        return route("{$routeBaseName}.entity.store", [$entityName, (!is_null($entityModel) ? $entityModel->id : null), $relationEntityName]);
    }
    
    public static function entityEditUrl($entityName, $entityModel = null) {
        $routeBaseName = myApp()->getConfig('adminRouteBaseName');
        return route("{$routeBaseName}.entity.edit", [$entityName, (!is_null($entityModel) ? $entityModel->id : null)]);
    }
    public static function entityEditOrderUrl($entityName, $entityModel = null, $relationEntityName = null) {
        $routeBaseName = myApp()->getConfig('adminRouteBaseName');
        return route("{$routeBaseName}.entity.editOrder", [$entityName, (!is_null($entityModel) ? $entityModel->id : null), $relationEntityName]);
    }
    
    public static function entityUpdateUrl($entityName, $entityModel = null) {
        $routeBaseName = myApp()->getConfig('adminRouteBaseName');
        return route("{$routeBaseName}.entity.edit", [$entityName, (!is_null($entityModel) ? $entityModel->id : null)]);
    }
    
    public static function entityDeleteUrl($entityName, $entityModel = null) {
        $routeBaseName = myApp()->getConfig('adminRouteBaseName');
        return route("{$routeBaseName}.entity.edit", [$entityName, (!is_null($entityModel) ? $entityModel->id : null)]);
    }
    
    public static function isEntityShowOnMenu($entityName) {
        $entitySource = static::entitySources($entityName);
        return ((array_key_exists('showOnMenu', $entitySource))
                ? boolval($entitySource['showOnMenu'])
                : false);
    }
    
    public static function hasEntityJqueryValidation($entityName) {
        $entitySource = static::entitySources($entityName);
        return ((array_key_exists('enableJqueryValidation', $entitySource))
                ? boolval($entitySource['enableJqueryValidation'])
                : false);
    }
    
    public static function entityMenuLink($entityName) {
        $entitySource = static::entitySources($entityName);
        $label = ((array_key_exists('menuLink', $entitySource))
                ? $entitySource['menuLink']
                : '#');
        return $label;
    }
    
    public static function entityUserScope($entityName, $query, $user) {
        if(!$user->isAdmin()) {
            switch($entityName){
                case static::PAGE:
                    $query->where('parent_id', '=', Page::userTopLevelPage($user));
                    break;
                default:
                    break;
            }
        }
    }
    
    public static function menuEntities() {
        return [
            static::PAGE,
            static::ARTICLE,
        ];
    }
    
    /** Return assoc array with source data for all entities
     * @return array */
    public static function entitySources($entityName) {
        $entities = static::entitiesSources();
        return (array_key_exists($entityName, $entities) ? $entities[$entityName] : []);
    }
    
    /** Return assoc array with source data for all entities
     * @return array */
    public static function entitiesSources() {
        $transBaseName = myApp()->getConfig('adminTransBaseName');
        $sources = [
            static::PAGE => [
                'refererModel' => Page::class,
                'label' => trans("{$transBaseName}.menu.pages"),
                'editPageTitle' => trans("{$transBaseName}.pageTitle.editPages"),
                'showOnMenu' => allow(Permission::LIST_ENTITY, static::PAGE),
                'menuLink' => static::entityListUrl(static::PAGE),
                'enableJqueryValidation' => true,
                'datatableManager' => PageDatatable::class,
            ],
            static::ARTICLE => [
                'label' => trans("{$transBaseName}.menu.articles"),
                'refererModel' => Page::class,
                'label' => trans("{$transBaseName}.menu.articles"),
                'editPageTitle' => trans("{$transBaseName}.pageTitle.editArticles"),
                'showOnMenu' => allow(Permission::LIST_ENTITY, static::ARTICLE),
                'menuLink' => static::entityListUrl(static::ARTICLE),
                'enableJqueryValidation' => true,
                'datatableManager' => ArticleDatatable::class,
            ],
        ];
        return $sources;
    }
}
