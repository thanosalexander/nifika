<?php

namespace App\Logic\App;

use App\Language;
use Illuminate\Database\Eloquent\Collection;

class MyApp{
    
    protected $languages = null;
    protected $singleLocale = null;
    
    protected static $singleton = null;
    
    protected $config = [];
    
    /** To prevent multiple instances */
    protected function __construct(){
    }
    
    /** @return \static */
    public static function _get(){
        if(is_null(static::$singleton)){
            $obj = new static();
            $obj->setupAppLocales();
            static::$singleton = $obj;
        } else {
            $obj = static::$singleton;
        }
        return $obj;
    }
    
    /** */
    public function setupAppLocales(){
        $dbLanguages = ((class_exists('App\\Language') && Language::tableExists()) ? Language::all(): collect([]));
        $appLanguage = app()->getLocale();
        
        if($dbLanguages->count() === 0){
            $this->singleLocale = $appLanguage;
            $this->languages = null;
        } else if ($dbLanguages->count() === 1){
            $appLanguage = $dbLanguages->first()->code;
            $this->singleLocale = $appLanguage;
            $this->languages = null;
        } else {
            $this->singleLocale = null;
            $this->loadLanguages();
        }
    }
    
    /** 
     * @param string $name
     * @param mixed $value */
    public function setConfig($name, $value){
        $this->config[$name] = $value;
    }
    
    /**
     * @param string $name
     * @param mixed $default
     * @return mixed */
    public function getConfig($name, $default = null){
        return (array_key_exists($name, $this->config) ? $this->config[$name] : $default);
    }
    
    /** @return boolean */
    public function hasSingleLocale(){
        return !is_null($this->singleLocale);
    }
    
    /** @return array */
    public function getSingleLocale(){
        return $this->singleLocale;
    }
    
    /** @return boolean */
    public function hasLanguages(){
        return !is_null($this->languages);
    }
    
    /** 
     * @return Collection
     */
    protected function loadLanguages($reload = false){
        if($reload || is_null($this->languages)){
            $this->languages = Language::all();
        }
    }
    
    /** Get all enabled backend's languages sorted
     * @return \Illuminate\Database\Eloquent\Collection */
    public function usedLanguages(){
        return is_null($this->languages) ? null : $this->languages
                ->where('enabled', Language::ENABLED_YES)->values();
    }
    
    /** Get all enabled backend's languages sorted
     * @return \Illuminate\Database\Eloquent\Collection */
    public function backendLanguages(){
        return is_null($this->languages) ? null : $this->languages
                ->where('enabled', Language::ENABLED_YES)
                ->where('type', Language::TYPE_BACKEND)
                ->sortBy('sort')->sortBy('id')->values();
    }
    /** Get all enabled frontend's languages sorted
     * @return \Illuminate\Database\Eloquent\Collection */
    public function frontendLanguages(){
        return is_null($this->languages) ? null : $this->languages
                ->where('enabled', Language::ENABLED_YES)
                ->where('type', Language::TYPE_FRONTEND)
                ->sortBy('sort')->sortBy('id')->values();
    }
}