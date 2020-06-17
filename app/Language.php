<?php

namespace App;

use App\Logic\Base\BaseModel;

class Language extends BaseModel
{    
    const TYPE_FRONTEND = 1;
    const TYPE_BACKEND = 2;
    
    public $timestamps = false;
    
    /** The attributes that are mass assignable.
     * @var array */
    protected $fillable = ['type', 'code', 'sort', 'enabled'];
    
    /** 
     * @return Collection
     */
    public static function tableExists(){
        return \Schema::hasTable(static::getTableName());
    }

    public function translations() {
        return $this->hasMany(Translation::class, 'lang', 'code');
    }
    
    /** Extends parent boot
     * Define callback for CRUD events */
    public static function boot(){
        parent::boot();
        
        //set callback function for 'deleting' event that is triggered before db deletion
        static::deleting(function($item){
            //delete images
            $item->translations()->each(function(Translation $translation){
                //delete translation
                $translation->delete();
            });
        });
    }
}
