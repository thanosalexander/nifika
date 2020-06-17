<?php

namespace App\Logic\Base;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Logic\Base\BaseModel;
 *
 * @method \Illuminate\Database\Query\Builder like($column, $value)
 * @method \Illuminate\Database\Query\Builder joinRelation($relationName, $operator = '=', $type = 'left', $where = false)
 * @mixin \Eloquent
 */
abstract class BaseModel extends Model
{
    use BaseModelTrait;
    
    const ENABLED_YES = 1;
    const ENABLED_NO = 0;
    
    const DEFAULT_SORT_DATE_FIELD = 'created_at';
    
    /**
     * The attributes that are mass assignable.
     * @var array */
    protected $fillable = ['name'];

}
