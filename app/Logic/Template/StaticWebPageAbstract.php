<?php

namespace App\Logic\Template;

use App\Logic\Pages\PageView;
use App\Page;
use Carbon\Carbon;

/** Used from template classes for wen pages. */
abstract class StaticWebPageAbstract extends WebPageAbstract {
    
    /** Get the WebPage.
     * @return \static */
    public static function get(Page $model = null){
        if (is_null(static::$singleton)) {
            $obj = new static();
            $obj->setModel($model);
            static::$singleton = $obj;
        }
        return static::$singleton;
    }
    
    /** Set the model from which get data. */
    protected abstract function setModel(Page $model = null);
    
    /** Check if web page is enabled
     * @return boolean */
    public function enabled() {
        return is_null($this->model) ? false : $this->model->isFrontendVisible();
    }

    /** Get web page`s title
     * @return string */
    public function title() {
        return is_null($this->model) ? '' : $this->model->title;
    }
    
    /** Get web page`s description
     * @return string */
    public function description() {
        return is_null($this->model) ? '' : $this->model->description;
    }
    
    /** Get web page`s content
     * @return string */
    public function content() {
        return is_null($this->model) ? '' : $this->model->content;
    }
    
    /** Get web page`s meta title
     * @return string */
    public function metaTitle() {
        return (is_null($this->model) ? '': parent::metaTitle());
    }
    
    /** Get web page`s meta description
     * @return string */
    public function metaDescription() {
        return (is_null($this->model) ? '': parent::metaDescription());
    }
    
    /** Get web page`s meta keywords
     * @return string */
    public function metaKeywords() {
        return (is_null($this->model) ? '': parent::metaKeywords());
    }
    
    /** Get web page`s url
     * @return string */
    public function url(array $parameters = []) {
        return (is_null($this->model) ? '' : parent::url($parameters));
    }
    
    /** Get page`s displayedDate
     * @return Carbon */
    public function displayedDate(){
        return (is_null($this->model) ? Carbon::now() : parent::displayedDate());
    }
    
    public function lastUpdatedDate() {
        return (is_null($this->model) ? Carbon::now() : parent::displayedDate());
    }
    
    public function viewName(){
        return (is_null($this->model)
                ? PageView::getViewNameByType(Page::TYPE_PAGE)
                : ageView::getViewNameByModel($this->model));
    }

    public function hasMedia() {
        return (is_null($this->model) ? false : parent::hasMedia());
    }

    public function hasVideo() {
        return (is_null($this->model) ? false : parent::hasVideo());
    }

    public function hasImage() {
        return (is_null($this->model) ? false : parent::hasImage());
    }
    
    public function hasImageGallery() {
        return (is_null($this->model) ? false : parent::hasImageGallery());
    }
    
    public function imageGallery() {
        return (is_null($this->model) ? collect() : parent::imageGallery());
    }

    public function video() {
        return (is_null($this->model) ? null : parent::video());
    }

    public function image() {
        return (is_null($this->model) ? null : parent::image());
    }
    
    public function imageServerPath() {
        return (is_null($this->model) ? null : parent::imageServerPath());
    }
    
}
