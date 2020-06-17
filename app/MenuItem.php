<?php

namespace App;

use App\Logic\Base\BaseModel;
use App\Logic\Template\Menu\Menus;
use Illuminate\Database\Eloquent\Collection;

class MenuItem extends BaseModel {

    protected $table = 'menuItems';

    /** Displays a given page. */
    const TYPE_PAGE = 1;

    /** Displays a given link. */
    const TYPE_EXTERNAL_LINK = 2;

    protected $fillable = [
        'menu_id', 'parent_id', 'sort', 'type', 'content',
    ];

    /** Holds $menuItems cache.
     * @var collection|null */
    protected static $menuItems = null;

    /** Get the menuItems of a menu.
     * @param int $menuId
     * @return Collection */
    public static function menuItemsByMenu($menuId) {
        $filtered = static::allMenuItems()->filter(function($menuItem) use ($menuId) {
            return $menuItem->menu_id == $menuId;
        });
        return $filtered;
    }
    
    /**  */
    public function sourcePage() {
        return $this->belongsTo(Page::class, 'content', 'id');
    }

    protected static function allMenuItems() {
        if (is_null(static::$menuItems)) {
            static::$menuItems = static::orderBy('sort', 'asc')->get();
        }
        return static::$menuItems;
    }
    
    public function scopeMenuItems($query, $menuId) {
        return $query->where(static::getTableName().'.menu_id', '=', $menuId);
    }
    
    public function scopeOrdered($query, $dir = 'asc') {
        return $query->orderBy(static::getTableName().'.sort', $dir);
    }

    public function subItems() {
        return $this->hasMany(MenuItem::class, 'parent_id')->where('menu_id', $this->menu_id);
    }
    
    /**
     * @return Page */
    public function getLinkedPage() {
        $page = null;
        if($this->type === MenuItem::TYPE_PAGE){
            $page = Page::where('id', '=', $this->content)->first();
        }
        return $page;
    }

}
