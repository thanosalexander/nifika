<?php

namespace App\Logic\Template;

use App\Logic\Pages\PageView;
use App\Page;
use App\PageImage;
use Carbon\Carbon;

/** Used from template classes for wen pages. */
abstract class WebPageAbstract implements WebPageInterface {
    /** @var Page */
    protected $model = null;
    
    /** Get the WebPage.
     * @param Page $model
     * @return \static */
    public static function get(Page $model){
        
        if (!array_key_exists($model->id, static::$singletons)) {
            $obj = new static;
            $obj->model = $model;
            static::$singletons[$model->id] = $obj;
        }
        
        return static::$singletons[$model->id];
    }
    
    /** Check if web page is enabled
     * @return boolean */
    public function enabled(){
        return $this->model->isEnabled();
    }
    
    /** Get web page`s title
     * @return string */
    public function title(){
        return $this->model->title;
    }
    
    /** Get web page`s description
     * @return string */
    public function description(){
        $value = $this->model->description;
        if(empty($value)){
            $value = str_limit(strip_tags($this->content()), 100);
        }
        return $value;
    }
    
    /** Get web page`s content
     * @return string */
    public function content(){
        return $this->model->content;
    }
    
    /** Get web page`s meta title
     * @return string */
    public function metaTitle() {
        return empty($this->model->metaTitle) ? $this->title() : $this->model->metaTitle;
    }
    
    /** Get web page`s meta description
     * @return string */
    public function metaDescription() {
        return empty($this->model->metaDescription) ? $this->model->description : $this->model->metaDescription;
    }
    
    /** Get web page`s meta keywords
     * @return string */
    public function metaKeywords() {
        return empty($this->model->metaKeywords) ? '' : $this->model->metaKeywords;
    }
    
    /** Get page`s resource model
     * @return Page */
    public function model(){
        return $this->model;
    }
    
    /** Get page`s url
     * @return string */
    public function url(array $parameters = []){
        return $this->model->url($parameters);
    }
    
    /** Get page`s displayed date
     * @return Carbon */
    public function displayedDate() {
        $createdAt = $this->model->created_at;
        $updatedAt = $this->model->updated_at;
        
        return $createdAt;
    }
    
    /** Get page`s lastUpdated date
     * @return Carbon */
    public function lastUpdatedDate() {
        $createdAt = $this->model->created_at;
        $updatedAt = $this->model->updated_at;

        return (!empty($updatedAt) ? $updatedAt : $createdAt);
    }
    
    /** Get page`s view name
     * @return string */
    public function viewName(){
        return PageView::getViewNameByModel($this->model);
    }

    public function hasMedia() {
        return ($this->hasVideo() || $this->hasImage());
    }

    public function hasVideo() {
        return !empty($this->model->video);
    }

    public function hasImage() {
        return !empty($this->image());
    }
    
    public function hasImageGallery() {
        return ($this->imageGallery()->count() > 0);
    }
    
    public function imageGallery() {
        return $this->model->imagesFrontendVisible;
    }
    
    /** Get the nth image from the image gallery.
     * First displayOrder is 1.
     * @param int $displayOrder if it is <=0 get the first image
     * @return PageImage
     */
    public function imageGalleryNthImage($displayOrder) {
        $image = null;
        if($this->hasImageGallery() && $displayOrder <= $this->imageGallery()->count()) {
            $index = ($displayOrder <= 0) ? 0 : $displayOrder-1;
            $image = $this->imageGallery()->get($index);
        }
        return $image;
    }

    public function video() {
        return $this->model->video;
    }

    public function image() {
        $image = $this->model->filePath();
        if(is_null($image) && $this->hasImageGallery()) {
            $image = $this->imageGallery()->first()->filePath();
        }
        return $image;
    }
    
    public function imageServerPath() {
        $image = $this->model->fileServerPath();
        if(is_null($image) && $this->hasImageGallery()) {
            $image = $this->imageGallery()->first()->fileServerPath();
        }
        return $image;
    }
    
}
