<?php

namespace App;

use App\Logic\Base\BaseAuthenticatableModel;

class User extends BaseAuthenticatableModel
{

    const TYPE_ADMIN = 1;
    const TYPE_USER = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'username', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    
    public function isAdmin(){
        return (intval($this->type) === static::TYPE_ADMIN);
    }
    
    public function isManager(){
        return (intval($this->type) === static::TYPE_USER);
    }
    
}
