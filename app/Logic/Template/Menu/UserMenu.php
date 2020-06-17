<?php

namespace App\Logic\Template\Menu;

use App\Http\Requests\Request as FormRequest;
use App\Logic\App\EntityManager;
use App\Logic\Locales\AppLocales;
use App\Logic\App\Permission;
use App\Setting;
use App\User;
use Illuminate\Http\Request;

/**
 * Description of UserMenu
 *
 * @author thanasis
 */

class UserMenu extends MenuAbstract {
    /**
     * @param User $user
     * @param Request|FormRequest $request
     * @return array
     */
    public static function getMainMenu($user, $request = null){
//        if(is_null($user) || $user->isManager()){
//            return static::getManagerMenu($user, $request);
//        } else if (!is_null($user) && ($user->isManager())) {
            return static::getAdminMenu($user, $request);
//        }
    }
    
    public static function getAdminMenu($user, $request = null){
        if(!($user instanceof User)){ return [];}
        $transBaseName = myApp()->getConfig('adminTransBaseName');
        $routeBaseName = myApp()->getConfig('adminRouteBaseName');
            
        $currentRouteName = '';
        $currentEntity = '';
        $currentSettingGroup = '';
        $currentEntityId = '';
        $currentEntityRelation = '';
        if(!is_null($request)){
            $currentEntity = $request->route('entity');
            $currentEntityId = $request->route('id');
            $currentEntityRelation = $request->route('relation');
            $currentSettingGroup = $request->route('settingGroup');
            $currentRoute = $request->route();
            if(!is_null($currentRoute)){
                $currentRouteName = $currentRoute->getName();
            }
        }
        $menuEntities = EntityManager::menuEntities();
        $allItems = [];
        foreach($menuEntities as $entityName) {
           $allItems[] = [
                'name' => EntityManager::entityLabel($entityName),
                'materialIconTag' => '',
                'fontAwesomeIconTag' => '',
                'url' => EntityManager::entityMenuLink($entityName),
                'targetWindow' => '',
                'active' => ($currentEntity == $entityName),
                'shown' => EntityManager::isEntityShowOnMenu($entityName)
            ]; 
        }

        $allItems[] = [
            'name' => trans($transBaseName.'.menu.settings'),
            'materialIconTag' => '',
            'fontAwesomeIconTag' => 'fa-fw fa-cogs',
            'url' => '',
            'targetWindow' => '',
            'active' => false,
            'shown' => false,
            'children' => [
                [
                    'name' => trans($transBaseName.'.menu.settingsGenaral'),
                    'materialIconTag' => '',
                    'fontAwesomeIconTag' => '',
                    'url' => route($routeBaseName.'.settings.edit', Setting::GROUP_GENERAL),
                    'targetWindow' => '',
                    'active' => ($currentRouteName == $routeBaseName.'.settings.edit' && $currentSettingGroup == Setting::GROUP_GENERAL),
                    'shown' => allow(Permission::EDIT_SETTING_GROUP, Setting::GROUP_GENERAL)
                ],
                [
                    'name' => trans($transBaseName.'.menu.settingsStartPage'),
                    'materialIconTag' => '',
                    'fontAwesomeIconTag' => '',
                    'url' => route($routeBaseName.'.settings.edit', Setting::GROUP_START_PAGE),
                    'targetWindow' => '',
                    'active' => ($currentRouteName == $routeBaseName.'.settings.edit' && $currentSettingGroup == Setting::GROUP_START_PAGE),
                    'shown' => allow(Permission::EDIT_SETTING_GROUP, Setting::GROUP_START_PAGE)
                ],
                [
                    'name' => trans($transBaseName.'.menu.settingsContactPage'),
                    'materialIconTag' => '',
                    'fontAwesomeIconTag' => '',
                    'url' => route($routeBaseName.'.settings.edit', Setting::GROUP_CONTACT_PAGE),
                    'targetWindow' => '',
                    'active' => ($currentRouteName == $routeBaseName.'.settings.edit' && $currentSettingGroup == Setting::GROUP_CONTACT_PAGE),
                    'shown' => allow(Permission::EDIT_SETTING_GROUP, Setting::GROUP_CONTACT_PAGE)
                ],
                [
                    'name' => trans($transBaseName.'.menu.settingsAdvanced'),
                    'materialIconTag' => '',
                    'fontAwesomeIconTag' => '',
                    'url' => route($routeBaseName.'.settings.edit', Setting::GROUP_ADVANCED),
                    'targetWindow' => '',
                    'active' => ($currentRouteName == $routeBaseName.'.settings.edit' && $currentSettingGroup == Setting::GROUP_ADVANCED),
                    'shown' => allow(Permission::EDIT_SETTING_GROUP, Setting::GROUP_ADVANCED)
                ],
            ],
        ];
//        [
//            'name' => trans($transBaseName.'.menu.myProfile'),
//            'materialIconTag' => 'person',
//            'fontAwesomeIconTag' => '',
//            'url' => route($routeBaseName.'.entity.edit', 'myProfile'),
//            'targetWindow' => '',
//            'active' => ($currentRouteName == $routeBaseName.'.entity.edit' && $currentEntity == 'myProfile'),
//            'shown' => allow(Permission::EDIT_ENTITY, 'myProfile')
//        ],
        $allItems[] = [
            'name' => trans($transBaseName.'.menu.homePage'),
            'materialIconTag' => '',
            'fontAwesomeIconTag' => 'fa-globe',
            'url' => route('public.home'),
            'targetWindow' => '_blank',
            'active' => false,
            'shown' => true
        ];
        
//        $allItems = array_merge($allItems, static::getLanguageMenu($request));
        $allItems = array_merge($allItems, static::getAuthMenu($request));
        
        return static::filterShownItems($allItems);
    }
    
    /** 
     * @param Request|FormRequest $request
     * @return array
     */
    public static function getLanguageMenu($request){
        $otherLocales = AppLocales::getOtherLanguages();
        $allItems = [];
        foreach($otherLocales as $localeCode => $localeData){
            $allItems[] = [
                'name' => $localeData['native'],
                'materialIconTag' => '',
                'fontAwesomeIconTag' => 'fa-globe',
                'targetWindow' => '',
                'url' => AppLocales::getLocalizedURL($localeCode),
                'active' => false,
                'shown' => true
            ];
        }
        
        return static::filterShownItems($allItems);
    }
    
    /** 
     * @param Request|FormRequest $request
     * @return array
     */
    public static function getAuthMenu($request){
        $transBaseName = myApp()->getConfig('adminTransBaseName');
            
        $currentRouteName = '';
        if(!is_null($request)){
            $currentRoute = $request->route();
            if(!is_null($currentRoute)){
                $currentRouteName = $currentRoute->getName();
            }
        }
        
        $allItems = [
            [
                'name' => trans($transBaseName.'.menu.logout'),
                'materialIconTag' => '',
                'fontAwesomeIconTag' => 'fa-power-off',
                'url' => AppLocales::getLocalizedURL(currentModelLocale(), route('logout')),
                'targetWindow' => '',
                'active' => false,
                'shown' => auth()->check()
            ],
            [
                'name' => trans('public.login.title'),
                'materialIconTag' => '',
                'fontAwesomeIconTag' => '',
                'url' => route('login'),
                'targetWindow' => '',
                'active' => ($currentRouteName == 'login'),
                'shown' => !auth()->check()
            ],
        ];
        
        return static::filterShownItems($allItems);
    }
}
