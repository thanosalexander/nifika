<?php

namespace App\Logic\App;

use App\Logic\App\EntityManager;
use App\Logic\Pages\PageType;
use App\Page;
use App\Setting;
use App\User;
use Illuminate\Support\Facades\Gate;

/**
 * Handle all permissions
 * @author thanasis
 */
class Permission {

    const LIST_ENTITY = 'listEntity';
    const CREATE_ENTITY = 'createEntity';
    const EDIT_ENTITY = 'editEntity';
    const EDIT_ENTITY_ORDER = 'editEntityOrder';
    const DELETE_ENTITY = 'deleteEntity';
    const EDIT_SETTING_GROUP = 'editSettingGroup';
    const SWITCH_ENTITY_STATUS = 'switchEntityStatus';
    const CHANGE_MODEL_LOCALE = 'changeModelLocale';
    const CHANGE_ADMIN_LOCALE = 'changeAdminLocale';
    
    protected static $disabledEntities = [
        EntityManager::ARTICLE
    ];

    public static function loadGates() {

        Gate::define(Permission::LIST_ENTITY,
            function ($user, $entityName, $parentModel = null, $relationEntityName = null) {
                /* @var $user User */
                /* @var $entityName string */ 
                /* @var $parentModel Page */
                /* @var $relationEntityName string */
                if (is_null($user)) {return false;}
                if (in_array($entityName, Permission::$disabledEntities)) {return false;}
                if ($user->isAdmin()) {return true;}
                switch ($entityName) {
                    case EntityManager::PAGE: return ($user->isAdmin() || $user->isManager());
                    case EntityManager::ARTICLE: return ($user->isAdmin() || $user->isManager());
                    default: return false;
                }
        });

        Gate::define(Permission::CREATE_ENTITY,
            function ($user, $entityName, $parentModel = null, $relationEntityName = null) {
                /* @var $user User */
                /* @var $entityName string */ 
                /* @var $parentModel Page */
                /* @var $relationEntityName string */
                if (is_null($user)) {return false;}
                if (in_array($entityName, Permission::$disabledEntities)) {return false;}
                if ($user->isAdmin()) {return true;}
                switch ($entityName) {
                    case EntityManager::PAGE: return ($user->isAdmin() || $user->isManager());
                    case EntityManager::ARTICLE: return ($user->isAdmin() || $user->isManager());
                    default: return false;
                }
        });

        Gate::define(Permission::EDIT_ENTITY,
            function ($user, $entityName, $model = null) {
                /** @var $user User */
                /** @var $entityName string */ 
                /** @var $model Page */
                if (is_null($user)) {return false;}
                if (in_array($entityName, Permission::$disabledEntities)) {return false;}
                if ($user->isAdmin()) {return true;}
                switch ($entityName) {
                    case EntityManager::PAGE: return ($user->isAdmin() || $user->isManager());
                    case EntityManager::ARTICLE: return ($user->isAdmin() || $user->isManager());
                    default: return false;
                }
        });
        
        Gate::define(Permission::EDIT_ENTITY_ORDER,
            function ($user, $entityName, $model = null) {
                /** @var $user User */
                /** @var $entityName string */ 
                /** @var $model Page */
                if (is_null($user)) {return false;}
                if (in_array($entityName, Permission::$disabledEntities)) {return false;}
                if ($user->isAdmin()) {return true;}
                switch ($entityName) {
                    case EntityManager::PAGE: return ($user->isAdmin() || $user->isManager());
                    case EntityManager::ARTICLE: return ($user->isAdmin() || $user->isManager());
                    default: return false;
                }
        });
        
        Gate::define(Permission::DELETE_ENTITY,
            function ($user, $entityName, $model = null) {
                /** @var $user User */
                /** @var $entityName string */ 
                /** @var $model Page */
                if (is_null($user)) {return false;}
                if ($user->isAdmin()) {return true;}
                switch ($entityName) {
                    case EntityManager::PAGE: return ($user->isAdmin() || $user->isManager());
                    case EntityManager::ARTICLE: return ($user->isAdmin());
                    default: return false;
                }
        });

        Gate::define(Permission::EDIT_SETTING_GROUP,
            function ($user, $settingGroup) {
                /** @var $user User */
                /** @var $settingGroup string */ 
                if (is_null($user)) {return false;}
                if ($user->isAdmin()) {return true;}
                switch ($settingGroup) {
                    case Setting::GROUP_ADVANCED: return ($user->isAdmin());
                    case Setting::GROUP_GENERAL: return ($user->isAdmin());
                    case Setting::GROUP_START_PAGE: return ($user->isAdmin());
                    case Setting::GROUP_CONTACT_PAGE: return ($user->isAdmin());
                    default: return ($user->isAdmin());
                }
        });

        Gate::define(Permission::CHANGE_MODEL_LOCALE,
            function ($user) {
                /** @var $user User */
                if (is_null($user)) {return false;}
                return true;
        });
        
        Gate::define(Permission::CHANGE_ADMIN_LOCALE,
            function ($user) {
                /** @var $user User */
                if (is_null($user)) {return false;}
                return true;
        });
    }
    
    /**
     * @param Page $model
     * @return boolean */
    public static function canModelBeDeleted($model){
        if (is_null($model)) {return false;}
        $modelClassName = get_class($model);
        
        switch($modelClassName){
            case Page::class:
                return true;
            default:
                return false;
        }
    }
    
    /**
     * @param User $user
     * @return boolean */
    public static function creatablePageTypes($user){
        $types = [];
        if(is_null($user)){
            return $types;
        }
        if($user->isAdmin()){
            $types = PageType::allPageTypes();
        } else if($user->isManager()){
            $types = [
                Page::TYPE_PAGE,
            ];
        }
        return $types;
    }
    
    /**
     * @param User $user
     * @return boolean */
    public static function canMangeMenu($user){
        return $user->isAdmin();
    }
    
    /**
     * @param User $user
     * @return boolean */
    public static function canMangePageImages($user){
        return (ss(Setting::SS_ADMIN_PAGE_IMAGES_ENABLED)
//                && $user->isAdmin()
                );
    }
}
