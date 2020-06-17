<?php

namespace App\Logic\Locales;

use App\Http\Controllers\AdminController;

/** It is used to Handle ModelLocale. Specifically, define the current locale
 * for translatable models. Basically, current data locale it is only for admin routes, 
 * in other routes it is the same with app.locale.
 * IMPORTANT: ModelLocale must be defined, otherwise any use of translatable models 
 * it breaks the process. */
class ModelLocale {

    const COOKIE_VERSION = '1.0';
    const COOKIE_NAME = 'modelLocale';

    /** @var static|null Holds singleton of modelLocale. */
    public static $cookie = null;

    /** Get the Checkout object.
     * @return static */
    public static function get() {
        if (is_null(static::$cookie)) {
            $obj = new static;
            //create cookie if does not exist
            $obj->createCookie();
        } else {
            $obj = static::$cookie;
        }
        return $obj;
    }
    
    /** Detect current data language */
    public static function detectCurrent(){
        
        if (app()->runningInConsole()) {
            $lang = config('app.modelLocale');
        } else if (app()->runningUnitTests()) {
            $lang = config('app.modelLocale');
        } else if (!myApp()->hasLanguages()) {
            $lang = config('app.modelLocale');
        } else {
            $route = request()->route();
            $routeBase = array_first(explode('.', $route ? $route->getName() : ''));
            $isAdminRoute = ($routeBase === myApp()->getConfig('adminRouteBaseName'));
            $lang = app()->getLocale();
            if($isAdminRoute){
                $lang = static::get()->getCurrent();
            }
        }
        return $lang;
    }

    /** Update cookie's value
     * @param string $value
     * @return static */
    public function setCurrent($value) {
        $cookieData = $this->getCookie();
        $cookieData['currentModelLocale'] = $value;
        $this->updateCookie($cookieData);
    }

    /** Get value from cookie 
     * @return string */
    public function getCurrent() {
        $cookieData = $this->getCookie();
        return $cookieData['currentModelLocale'];
        
    }

    /** Check if cookie with same version exists
     * @return boolean */
    protected function existsCookie() {
        $exists = false;
        if(request()->hasCookie(static::COOKIE_NAME)) {
            $cookie = $this->getCookie();
            $exists = isset($cookie['version']) && $cookie['version'] == static::COOKIE_VERSION;
        }
        return $exists;
    }

    /** Create cookie if does not exist
     * @return array */
    protected function createCookie() {
        if (!$this->existsCookie()) {
            $this->setCookie($this->defaultCookieData());
        }
    }

    /** Update cookie's data with given if cookie exists
     * @param array $cookieData the value of cookie
     * @return array */
    protected function updateCookie($cookieData) {
        if ($this->existsCookie()) {
            $this->setCookie($cookieData);
        }
    }

    /** Return cookie as array. if it is empty default structure returned
     * @return array */
    protected function getCookie() {
        return \Cookie::get(static::COOKIE_NAME, $this->defaultCookieData());
    }

    /** Save cookie's data
     * @param array $cookieData the value of cookie
     * @return array */
    protected function setCookie($cookieData) {
        $cookieData = empty($cookieData) ? $this->defaultCookieData(): $cookieData;
        \Cookie::queue(static::COOKIE_NAME, $cookieData, (60 * 24) * 60);//60 days
    }

    /** Default structure of cookie value
     * @return array */
    protected function defaultCookieData() {
        return [
          'version' => static::COOKIE_VERSION,
          'currentModelLocale' => config('app.modelLocale')
        ];
    }

}
