<?php

namespace App\Logic\App;

use Jenssegers\Agent\Agent;

/** Assets */
class Assets{

    const _PATH_LOGO = '/public/images/logos/logoBlack.svg';
    const _PATH_WHITE_LOGO = '/public/images/logos/logo.svg';
    const _PATH_NO_IMAGE = '/public/images/no_image.png';

    /** Get the app logo.
     * @return string */
    public static function defaultLogoPath(){
        return static::_PATH_LOGO;
    }

    /** Get the app logo.
     * @return string */
    public static function logoUrl(){
        if(request()->route()->getName()==='public.home'){
            return asset(static::whiteLogoUrl());
        }

        return asset(static::defaultLogoPath());
    }

    /** Get the app logo.
     * @return string */
    public static function logoFullPath(){
        return public_path(static::defaultLogoPath());
    }

    public static function whiteLogoUrl(){
        $logo = asset(static::_PATH_WHITE_LOGO);
        return $logo;
    }

    /** Get the app logo.
     * @return string */
    public static function noImageUrl(){
        $logo = asset(static::_PATH_NO_IMAGE);
        return $logo;
    }

    /** Get the app logo.
     * @return string */
    public static function deviceAsset($path){
        $agent = new Agent();
        $deviceBasePath = ($agent->isPhone() ? '/images-sm' : '');
        $logo = asset("{$deviceBasePath}{$path}");
        return $logo;
    }

}
