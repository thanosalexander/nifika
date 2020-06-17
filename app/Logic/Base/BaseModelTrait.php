<?php

namespace App\Logic\Base;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

trait BaseModelTrait
{
    
    /** Get a new empty Model.
     * @return  static */
    public static function _get(){
        return new static();
    }
    /** Get a new empty Model.
     * @return string */
    public static function getTableName(){
        return with(new static)->getTable();
    }
    
    /** 
     * 
     * @return string
     */
    public static function getMyNameColumn() {
        $possibleNames = ['name', 'title'];
        foreach($possibleNames as $attributeName){
            if(\Schema::hasColumn(static::getTableName(), $attributeName)){
                return '`'.static::getTableName().'`.`'.$attributeName.'`';
            }
        }
        return false;
    }
    /** get entity's name */
    public function getMyName() {
        $possibleNames = ['name', 'title'];
        $name = '';
        foreach ($possibleNames as $attributeName) {
            if (!is_null($this->$attributeName)) {
                $name = $this->$attributeName;
            }
        }
        if (empty($name)) {
            $name = '<<name>>';
        }
        return $name;
    }
    
    /** 
     * 
     * @param Collection $collection
     * @param bool $showEmpty
     * @return Collection
     */
    public static function convertToSelectList($collection, $showEmpty = false) {
        $list = $collection->values()->each(function($item){
                $item->myName = $item->getMyName();
            })->pluck('myName', 'id');
        if($showEmpty) {
           $list->prepend(trans(myApp()->getConfig('adminTransBaseName').'.form.selectPlaceholder'), '');
        }
            
        return $list;
    }

    /** 
     * 
     * @param Builder $query
     * @param string $direction asc|desc
     * @return type
     */
    public function scopeOrderByMyName(Builder $query, $direction = 'asc') {
        return $query->orderByRaw(static::getMyNameColumn() . ' ' . $direction);
    }
    /** 
     * 
     * @param Builder $query
     * @param string $value 
     * @return type
     */
    public function scopeLikeMyName(Builder $query, $value = '') {
        return $query->like(static::getMyNameColumn(), $value);
    }
    /** 
     * Append query where clause with condition that match string %like% given value
     * @param Builder $query
     * @param string $column
     * @param string $value
     * @return type
     */
    public function scopeLike(Builder $query, $column, $value) {
        $escapedValue = \DB::connection()->getPdo()->quote("%{$value}%");
        return !empty($value) ? $query->whereRaw("{$column} LIKE {$escapedValue}") : $query;
    }
}
