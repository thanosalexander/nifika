<?php

namespace App\Logic\Template\Menu;

/** Implement this ensure that your object can be used as menu item. */
interface MenuItemInterface {
//    protected $id;
//    protected $description;
//    protected $name;
//    protected $url;
//    /** @var bool */
//    protected $active = false;
//    protected $menuItem = null;
//    /** @var type */
//    protected $sourceWebPage;
//    /** @var bool */
//    protected $targetBlank = false;
//    protected $children = [];
    
    public function id();
    public function name();
    public function description();
    public function url();
    public function children();
    public function isVisible();
    public function isActive();
    public function isTargetBlank();
    public function hasChildren();
    public function setActive($value);
    public function setVisible($value);
    public function enableTargetBlank();
    public function disableTargetBlank();
    public function setChildren(array $menuItem);
    
}