<?php

namespace App\Logic\Template;

use App\Page;

/** Used from template classes for wen pages. */
interface WebPageInterface {

    /** check if web page is enabled
     * @return boolean */
    public function enabled();

    /** Get web page`s title
     * @return string */
    public function title();
    
    /** Get web page`s description
     * @return string */
    public function description();
    
    /** Get web page`s content
     * @return string */
    public function content();
    
    /** Get web page`s metaTitle
     * @return string */
    public function metaTitle();
    
    /** Get web page`s title
     * @return string */
    public function metaDescription();
    
    /** Get web page`s url
     * @return string */
    public function url(array $parameters = []);
    
    /** Get page`s displayed date
     * @return Carbon */
    public function displayedDate();
    
    /** Get page`s lastUpdated date
     * @return Carbon */
    public function lastUpdatedDate();
    
    /** Get page`s view name
     * @return string */
    public function viewName();
}
