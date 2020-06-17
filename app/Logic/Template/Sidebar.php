<?php

namespace App\Logic\Template;

use App\Logic\Locales\AppLocales;
use App\Logic\Pages\PageOrderType;
use App\Page;
use App\Setting;

/** . */
class Sidebar {
    /** @var \static */
    protected static $singleton = null;
    
    /** Get the Sidebar.
     * @return \static */
    public static function get(){
        $obj = new static();
        if (is_null(static::$singleton)) {
            //init something
        }
        return $obj;
    }
    
}
