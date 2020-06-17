<?php

namespace App\Logic\Base;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * App\Logic\Base\BaseModel;
 *
 * @method Builder like($column, $value)
 * @method Builder likeMyName($value = '')
 * @method Builder orderByMyName($direction = 'asc')
 * @mixin \Illuminate\Database\Eloquent
 */
class BaseAuthenticatableModel extends Authenticatable
{
    use BaseModelTrait;
    use Notifiable;
    
    const ENABLED_YES = 1;
    const ENABLED_NO = 0;
    
    /**
     * The attributes that are mass assignable.
     * @var array */
    protected $fillable = ['name'];
    
    /**
     * The attributes that should be hidden for arrays.
     * @var array*/
    protected $hidden = [
        'password', 'remember_token'
    ];

}
