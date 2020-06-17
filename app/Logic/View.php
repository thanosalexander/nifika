<?php

namespace App\Logic;

use App\Page;

/** Usefull view functions. */
class View {
    
    /** Defines if should be shown on frond end. */
    public static function getViewNameByModel(Page $model) {
        $viewBaseName = 'pages.public';
        $customViewBaseName = "{$viewBaseName}.custom";
        if(!empty($model->customView)){
            return $customViewBaseName. '.' . $model->customView;
        }
        
        return static::getViewNameByType($model->type);
    }
    
    /** Defines if should be shown on frond end. */
    public static function getViewNameByType($type) {
        $viewBaseName = 'pages.public';
        switch($type){
            case Page::TYPE_HOME:
                $viewFileName = 'index';
                break;
            case Page::TYPE_ARTICLE:
                $viewFileName = 'article';
                break;
            case Page::TYPE_PAGE_LIST:
                $viewFileName = 'articleCategory';
                break;
            case Page::TYPE_CONTACT:
                $viewFileName = 'contact';
                break;
            case Page::TYPE_PAGE:
            default:
                $viewFileName = 'page';
                break;
        }
        
        return "{$viewBaseName}.{$viewFileName}";
    }

}
