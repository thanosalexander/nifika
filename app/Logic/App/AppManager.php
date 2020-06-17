<?php

namespace App\Logic\App;

use App\Language;
use App\Logic\Locales\AppLocales;

/** Holds information of the App */
class AppManager {

    /** */
    public static function setupMyApp() {
        static::setupMyAppConfig();
    }

    /** Setup myApp`s config. */
    protected static function setupMyAppConfig() {
        //get myApp
        $myApp = myApp();
        static::setupMyAppLocales();
        static::setupAdminViewEnvironment();
        static::setupPublicViewEnvironment();
    }

    /** Setup myApp`s locales. */
    protected static function setupMyAppLocales() {
        //get myApp
        $myApp = myApp();
        
        if($myApp->hasLanguages()){
            $allLanguages = config('laravellocalization.supportedLocales');
            //get all available app's locales
            //get all frontend locales of myApp
            AppLocales::setFrontend($myApp->frontendLanguages()->groupBy('code')
                    ->map(function($item, $key) use ($allLanguages) {
                        return isset($allLanguages[$key]) ? $allLanguages[$key] : false;
                    })->toArray());
            //get all backend locales of myApp
            AppLocales::setBackend($myApp->backendLanguages()->groupBy('code')
                    ->map(function($item, $key) use ($allLanguages) {
                        return isset($allLanguages[$key]) ? $allLanguages[$key] : false;
                    })->toArray());
            //get all supported locales of myApp
            $supportedLocales = AppLocales::getFrontend();
            foreach (AppLocales::getBackend() as $localeCode => $localeData) {
                if (!isset($supportedLocales[$localeCode])) {
                    $supportedLocales[$localeCode] = $localeData;
                }
            }
            AppLocales::setSupported($supportedLocales);

            //set app locales
    //        dump(AppLocales::getSupported());
    //        dump(AppLocales::getFrontend());
    //        dump(AppLocales::defaultFrontend());
    //        dump(AppLocales::getBackend());
    //        dump(AppLocales::defaultBackend());
            //update locale config
            config([
                'app.locale' => AppLocales::defaultFrontend(),
                'app.modelLocale' => AppLocales::defaultFrontend(),
                'app.adminLocale' => AppLocales::defaultBackend(),
                'app.fallback_locale' => AppLocales::defaultFrontend(),
                'laravellocalization.supportedLocales' => AppLocales::getSupported(),
                'laravellocalization.localesOrder' => array_keys(AppLocales::getSupported()),
                'laravellocalization.useAcceptLanguageHeader' => false,
                'laravellocalization.hideDefaultLocaleInURL' => false,
            ]);
        } else {
            //get all available app's locales
            //get all frontend locales of myApp
            $allLanguages = config('laravellocalization.supportedLocales');
            $singleLocale = $myApp->getSingleLocale();
            $singleLocaleAsArray = [$singleLocale => $allLanguages[$singleLocale]];
            AppLocales::setFrontend($singleLocaleAsArray);
            //get all backend locales of myApp
            AppLocales::setBackend($singleLocaleAsArray);
            //get all supported locales of myApp
            AppLocales::setSupported($singleLocaleAsArray);
            $regional = AppLocales::getCurrentLocaleRegional();
            setlocale(LC_TIME, $regional.'.UTF-8');
            setlocale(LC_MONETARY, $regional.'.UTF-8');
            
        }
        
    }
    
    protected static function setupAdminViewEnvironment() {
        $myApp = myApp();
        $myApp->setConfig('adminRouteBaseName', config('myApp.admin.routeBaseName'));
        $myApp->setConfig('adminBaseUrl', config('myApp.admin.baseUrl'));
        $myApp->setConfig('adminTransBaseName', config('myApp.admin.transBaseName'));
        $myApp->setConfig('adminViewBasePath', config('myApp.admin.viewBasePath'));
        $myApp->setConfig('adminLayoutBasePath', config('myApp.admin.layoutBasePath'));
        $myApp->setConfig('adminAssetBasePath', config('myApp.admin.assetBasePath'));
    }
    
    protected static function setupPublicViewEnvironment() {
        $myApp = myApp();
        $myApp->setConfig('publicRouteBaseName', config('myApp.public.routeBaseName'));
        $myApp->setConfig('publicBaseUrl', config('myApp.public.baseUrl'));
        $myApp->setConfig('publicTransBaseName', config('myApp.public.transBaseName'));
        $myApp->setConfig('publicViewBasePath', config('myApp.public.viewBasePath'));
        $myApp->setConfig('publicLayoutBasePath', config('myApp.public.layoutBasePath'));
        $myApp->setConfig('publicAssetBasePath', config('myApp.public.assetBasePath'));
    }

}
