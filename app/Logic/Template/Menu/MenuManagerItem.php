<?php

namespace App\Logic\Template\Menu;

use App\Logic\Template\ContactPage;
use App\Logic\Template\PageModelPage;
use App\Logic\Template\StartPage;
use App\MenuItem;

/** Represents a menu item used in the template. */
class MenuManagerItem implements MenuItemInterface {
    /** @var MenuItem */
    protected $menuItem = null;

    /** @var PageModelPage|StartPage|ContactPage */
    protected $sourceWebPage;

    /** @var bool */
    protected $active = false;
    /** @var bool */
    protected $visible = true;
    /** @var bool */
    protected $targetBlank = false;

    /** @var \static[] */
    protected $children = [];

    /** Create a MenuManagerItem
     * @param string $url The url to go to.
     * @param string|array $name The label of the item OR if it is array must have to items the is the name and the second is the descrition.
     * @param string $objectClass The class to know which class is used.
     * @param string $objectId The id to know which object is used.
     * @return \static */
    public static function getter(MenuItem $menuItem, $sourceWebPage) {
        $obj = new static;
        $obj->menuItem = $menuItem;
        $obj->sourceWebPage = $sourceWebPage;
        $obj->init();
        return $obj;
    }

    protected function __construct() {
        
    }
    
    protected function init() {
        $this->active = false;
        $this->visible = $this->sourceWebPage->enabled();
        $this->targetBlank = (intval($this->menuItem->type) === MenuItem::TYPE_EXTERNAL_LINK);
        
    }

    /** 
     * @return PageModelPage|StartPage|ContactPage */
    public function sourceWebPage() {
        return $this->sourceWebPage;
    }
    
    /** 
     * @return MenuItem */
    public function menuItem() {
        return $this->menuItem;
    }
    
    /** 
     * @return string */
    public function parentId() {
        return $this->menuItem->parent_id;
    }
    
    /** 
     * @return string */
    public function id() {
        return $this->menuItem->id;
    }

    /** 
     * @return string */
    public function name() {
        return $this->sourceWebPage->title();
    }

    /** 
     * @return string */
    public function description() {
        return $this->sourceWebPage->title();
    }

    /** 
     * @return string */
    public function url() {
        return $this->sourceWebPage->url();
    }

    /**  */
    public function children() {
        return $this->children;
    }

    /** @return bool */
    public function isVisible() {
        return $this->visible;
    }
    /** @return bool */
    public function isActive() {
        return $this->active;
    }

    /** @return bool */
    public function isTargetBlank() {
        return $this->targetBlank;
    }

    /** @return bool */
    public function hasChildren() {
        return (is_array($this->children) && count($this->children) > 0);
    }

    /**
     * @param boolean $value */
    public function setVisible($value) {
        $this->visible = boolval($value);
    }
    /**
     * @param boolean $value */
    public function setActive($value) {
        $this->active = boolval($value);
    }

    /** Enables the menu`s link targetBlank. */
    public function enableTargetBlank() {
        $this->targetBlank = true;
    }

    /** Disables the menu`s link targetBlank. */
    public function disableTargetBlank() {
        $this->targetBlank = false;
    }

    /** */
    public function setChildren(array $children) {
        $this->children = $children;
    }

}
