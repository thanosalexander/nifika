<?php

namespace App;

use App\Logic\Base\BaseFileModel;

class PageImage extends BaseFileModel
{
    
    const FIRST = 1;
    const SECOND = 2;
    const THIRD = 3;
    const FOURTH = 4;
    
    CONST FILES_PATH = 'storage/images/';
    const FILE_ATTRIBUTE_NAME = 'filename';
    const FILE_ATTRIBUTE_REQUIRED = true;
    const FILE_WATERMARKING = false;
    
    const __DESCRIPTION = 2;
    protected $appends = ['description'];
    
    /** The table associated with the model.
     * @var string */
    protected $table = 'pageImages';

    /** The attributes that are mass assignable.
     * @var array */
    protected $fillable = ['sort', 'enabled', 'filename',  'description'];
    
    public static function tColumnMap(){ return ['description' => static::__DESCRIPTION];}
    /** Setter/Getter for description attribute */
    function setDescriptionAttribute($value) {$this->setTranslationAttribute('description', $value);}
    function getDescriptionAttribute() {return $this->getTranslationAttribute('description');}
    
    public function shouldUploadFileBeResized(){
        return false;
    }
    
    /** Get properties of the table. */
    public function page(){
        return $this->belongsTo(Page::class);
    }
    /** Front end filtering and sorting.
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder */
    public function scopeFrontEndVisible($query){
        return $query->where($this->getTable().'.enabled', '=', static::ENABLED_YES )
                     ->orderBy($this->getTable().'.sort', 'asc');
    }
}
