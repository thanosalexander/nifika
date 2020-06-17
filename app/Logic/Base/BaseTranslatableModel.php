<?php

namespace App\Logic\Base;

/** App\Logic\Base\BaseTranslatableModel */
abstract class BaseTranslatableModel extends BaseModel
{
    use BaseTranslatableModelTrait;
    
    /** The relations to eager load on every query.
     * @var array */
    protected $with = ['translations'];
    
    /** Register event actions. */
    public static function boot(){
        parent::boot();
        static::translatableboot();
    }
    
}
