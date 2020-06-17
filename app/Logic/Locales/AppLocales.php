<?php

namespace App\Logic\Locales;

/** Handle the application's locales. */
class AppLocales
{
    protected static $supported = null;
    protected static $frontend = null;
    protected static $backend = null;

    /** Set $supported */
    public static function setSupported($supported){
        static::$supported = $supported;
    }
    /** Get supported locales
     * @param boolean $forList if it is true convert array for select list as [code => name]...
     * @return array */
    public static function getSupported($forList = false){
        return $forList ? static::convertToSelectList(static::$supported) : static::$supported;
    }
    /** Set $frontend */
    public static function setFrontend($frontend){
        static::$frontend = $frontend;
    }
    /** Get frontend locales
     * @param boolean $forList if it is true convert array for select list as [code => name]...
     * @return array */
    public static function getFrontend($forList = false){
        return $forList ? static::convertToSelectList(static::$frontend) : static::$frontend;
    }
    /** Set $backend */
    public static function setBackend($backend){
        static::$backend = $backend;
    }
    /** Get backend locales
     * @param boolean $forList if it is true convert array for select list as [code => name]...
     * @return array */
    public static function getBackend($forList = false){
        return $forList ? static::convertToSelectList(static::$backend) : static::$backend;
    }

    public static function convertToSelectList($list){
        return array_map(function($item){return $item['native'];}, $list);
    }
    /** Check if given code is a supported locale
     * @param string $code
     * @return boolean
     */
    public static function isSupported($code){
        $locales = static::getSupported();
        return isset($locales[$code]);
    }
    /** Check if given code is a frontend locale
     * @param string $code
     * @return boolean
     */
    public static function isFrontend($code){
        $locales = static::getFrontend();
        return isset($locales[$code]);
    }
    /** Check if given code is a backend locale
     * @param string $code
     * @return boolean
     */
    public static function isBackend($code){
        $locales = static::getBackend();
        return isset($locales[$code]);
    }
    /** Return default frontend locale
     * @return string
     */
    public static function defaultFrontend(){
        return array_first(array_keys(static::getFrontend()));
    }
    /** Return default backend locale
     * @return string
     */
    public static function defaultBackend(){
        return array_first(array_keys(static::getBackend()));
    }

    /** Return default backend locale
     * @return string
     */
    public static function getCurrentLocale(){
        return myApp()->hasLanguages() ? \LaravelLocalization::getCurrentLocale() : app()->getLocale();
    }

    /** Return default backend locale
     * @return string
     */
    public static function getLocalizedURL($locale = null, $url = null, $attributes = array(), $forceDefaultLocation = false){
        return !myApp()->hasLanguages() ? $url : \LaravelLocalization::getLocalizedURL($locale, $url, $attributes, $forceDefaultLocation);
    }

    /** Return default backend locale
     * @return string
     */
    public static function getCurrentLocaleRegional(){
        $allLanguages = config('laravellocalization.supportedLocales');
        return !myApp()->hasLanguages()
                ? (!isset($allLanguages[static::getCurrentLocale()]) ? '': $allLanguages[static::getCurrentLocale()]['regional'])
                : \LaravelLocalization::getCurrentLocaleRegional();
    }

    /** Return default backend locale
     * @return string
     */
    public static function getCurrentLocaleName(){
        $allLanguages = config('laravellocalization.supportedLocales');
        return !myApp()->hasLanguages()
                ? (!isset($allLanguages[static::getCurrentLocale()]) ? '': $allLanguages[static::getCurrentLocale()]['name'])
                : \LaravelLocalization::getCurrentLocaleName();
    }

}
