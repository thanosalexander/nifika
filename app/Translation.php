<?php

namespace App;

use App\Logic\Base\BaseModel;

class Translation extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     * @var array */
    protected $fillable = ['lang', 'column', 'value'];
    public $timestamps = false;
    
    /** Get all of the owning translationable models. */
    public function translationable()
    {
        return $this->morphTo();
    }
}
