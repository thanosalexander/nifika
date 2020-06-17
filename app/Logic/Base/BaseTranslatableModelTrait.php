<?php

namespace App\Logic\Base;

use App\Scopes\TranslatableColumnsScope;
use App\Translation;
use App\Logic\Locales\AppLocales;

trait BaseTranslatableModelTrait {
    
    abstract protected static function tColumnMap();
    
    /** Get translations of parent model
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany */
    public function translations(){
        return $this->morphMany(Translation::class, 'translationable');
    }
    
    /** Get related translation model
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany */
    public function translation($columnId, $lang){
        return $this->translations->where('lang', $lang)->where('column', $columnId)->first();
    }
    
    /** Setter for translatable attribute with given columnName in given language */
    public function setLocalized($columnName, $lang, $value) {
        $this->setTranslationAttribute($columnName, $value, $lang);
    }
    
    /** Getter for translatable attribute with given columnName in given language */
    public function getLocalized($columnName, $lang) {
        return $this->getTranslationAttribute($columnName, $lang);
    }
    
    /** Getter for translatable attribute with given columnName in given language */
    public function fillLocalizedAttributes($data) {
        $locales = array_keys(AppLocales::getFrontend());
        foreach($locales as $lang){
            if(isset($data[$lang]) && is_array($data[$lang])){
                $columns = array_keys(static::tColumnMap());
                foreach($columns as $columnName){
                    if(isset($data[$lang][$columnName])){
                        $this->setLocalized($columnName, $lang, $data[$lang][$columnName]);
                    }
                }
            }
        }
    }
    
    /** Return the current model locale */
    protected static function currentLanguage(){
        return currentModelLocale();
    }
    
    /** Return translation of given column in given language
     * @param string $columnName
     * @param string $lang If it is null get default it from currentLanguage
     * @return string|null */
    protected function getTranslationAttribute($columnName, $lang = null){
        $columns = static::tColumnMap();
        $columnId = $columns[$columnName];
        $lang = empty($lang) ? static::currentLanguage() : $lang;
        return ($translation = $this->translation($columnId, $lang)) ? $translation->value: null;
    }
    
    /** Return translation of given column in given language
     * @param string $columnName
     * @param string $value
     * @param string $lang If it is null get default it from currentLanguage
     * @return string|null */
    protected function setTranslationAttribute($columnName, $value, $lang = null){
        $columns = static::tColumnMap();
        $columnId = $columns[$columnName];
        $lang = empty($lang) ? static::currentLanguage() : $lang;
        $translation = $this->translation($columnId, $lang);
        if($translation) {
            $translation->value = $value;
        } else {
            $this->makeNewTranslation($columnId, $lang)->value = $value;
        }
    }
    
    /** Make new translation of model. Set language, column and 
     * push them to model's relations.
     * @param type $columnId
     * @param type $lang
     * @param type $value
     * @return Translation
     */
    public function makeNewTranslation($columnId, $lang, $value = null){
        //create new translation model
        $translation = Translation::_get();
        //set translation's language and column
        $translation->fill(['lang' => $lang, 'column' => $columnId, 'value' => $value]);
        //add new traslation to parent's translations relations
        $this->translations->push($translation);
        
        return $translation;
    }

    /** Get translations column map [columnName => id, ...]
     * @return array */
    public function getTColumnMap(){
        return static::tColumnMap();
    }
    
    /** Return translatableColumns table name as it is used in query with translatableColumns
     * @param string $columnName
     * @return string */
    protected static function tColumnTable($columnName){
        $columns = static::tColumnMap();
        $lang = static::currentLanguage();
        return "trans_column_{$lang}_{$columns[$columnName]}";
    }
    
    /** Return translation column name as it is used in query with translatableColumns
     * @param string $columnName
     * @return string */
    public static function tColumn($columnName){
        $prefix = static::tColumnTable($columnName);
        return "{$prefix}_value";
    }

    /** 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $columnName
     * @param string $direction asc(default)|desc
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByTColumn(\Illuminate\Database\Eloquent\Builder $query, $columnName, $direction = 'asc') {
        return $query->orderByRaw(static::tColumn($columnName) . ' ' . $direction);
    }
    
    /** 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $columnName
     * @param string $value 
     * @return \Illuminate\Database\Eloquent\Builder */
    public function scopeLikeTColumn(\Illuminate\Database\Eloquent\Builder $query, $columnName, $value = '') {
        return $query->like(static::tColumn($columnName), $value);
    }
    
    /** Register event actions.*/
    public static function translatableboot(){
        //add a global scope that it is applied for each query builder
        static::addGlobalScope(new TranslatableColumnsScope);
        //after save
        static::saved( function($item){
//            dump('TranslatableTrait::saved');
            $item->translations->each(function($translation) use ($item){
                $item->translations()->save($translation);
            });            
        });
        //before delete
        static::deleting( function($item){
//            dump('TranslatableTrait::deleting');
            $item->translations->each(function($translation){//delete $translations
                $translation->delete();
            });
        });
    }
}
