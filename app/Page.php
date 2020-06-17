<?php

namespace App;

use App\Logic\Base\BaseFileModel;
use App\Logic\Pages\PageOrderType;
use App\Logic\Pages\PageType;
use App\Logic\Template\ContactPage;
use App\Logic\Template\Menu\Menus;
use App\Logic\Template\PageModelPage;
use App\Logic\Template\StartPage;
use Illuminate\Support\Collection;

class Page extends BaseFileModel
{

    const PAGE_HOME_ID = 10;
    const PAGE_NEWS_ID = 50;
    const PAGE_ABOUT_US_ID = 101;

    const TYPE_PAGE = 1;
    const TYPE_ARTICLE = 2;
    const TYPE_PAGE_LIST = 3;
    const TYPE_CONTACT = 4;
    const TYPE_HOME = 5;
    const TYPE_EXTERNAL = 6;

    const FILES_PATH = 'storage/images/';
    const FILE_ATTRIBUTE_NAME = 'image';
    const FILE_ATTRIBUTE_REQUIRED = false;
    const FILE_WATERMARKING = false;

    const __TITLE = 1;
    const __DESCRIPTION = 2;
    const __CONTENT = 3;
    const __META_TITLE = 4;
    const __META_DESCRIPTION = 5;
    const __META_KEYWORDS = 6;
    const __VIDEO = 7;
    protected $appends = [
        'title', 'description', 'content',
        'metaTitle', 'metaDescription', 'metaKeywords',
        'video'
        ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'slug', 'description', 'content', 'image', 'video',
        'metaTitle', 'metaDescription', 'metaKeywords',
        'type', 'customView', 'sort', 'sortType', 'enabled',
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function shouldUploadFileBeResized(){
        return false;
    }

    public static function tColumnMap(){
        return [
            'title' => static::__TITLE,
            'description' => static::__DESCRIPTION,
            'content' => static::__CONTENT,
            'metaTitle' => static::__META_TITLE,
            'metaDescription' => static::__META_DESCRIPTION,
            'metaKeywords' => static::__META_KEYWORDS,
            'video' => static::__VIDEO,
        ];
    }
    /** Setter/Getter for title attribute */
    function setTitleAttribute($value) {$this->setTranslationAttribute('title', $value);}
    function getTitleAttribute() {return $this->getTranslationAttribute('title');}
    /** Setter/Getter for description attribute */
    function setDescriptionAttribute($value) {$this->setTranslationAttribute('description', $value);}
    function getDescriptionAttribute() {return $this->getTranslationAttribute('description');}
    /** Setter/Getter for content attribute */
    function setContentAttribute($value) {$this->setTranslationAttribute('content', $value);}
    function getContentAttribute() {return $this->getTranslationAttribute('content');}
    /** Setter/Getter for content attribute */
    function setMetaTitleAttribute($value) {$this->setTranslationAttribute('metaTitle', $value);}
    function getMetaTitleAttribute() {return $this->getTranslationAttribute('metaTitle');}
    /** Setter/Getter for metaDescription attribute */
    function setMetaDescriptionAttribute($value) {$this->setTranslationAttribute('metaDescription', $value);}
    function getMetaDescriptionAttribute() {return $this->getTranslationAttribute('metaDescription');}
    /** Setter/Getter for metaKeywords attribute */
    function setMetaKeywordsAttribute($value) {$this->setTranslationAttribute('metaKeywords', $value);}
    function getMetaKeywordsAttribute() {return $this->getTranslationAttribute('metaKeywords');}
    /** Setter/Getter for video attribute */
    function setVideoAttribute($value) {$this->setTranslationAttribute('video', $value);}
    function getVideoAttribute() {return $this->getTranslationAttribute('video');}

    public function parentPage() {
        return $this->belongsTo(Page::class, 'parent_id');
    }

    public function subPages() {
        return $this->hasMany(Page::class, 'parent_id');
    }

    public function images(){
        return $this->hasMany(PageImage::class);
    }

    public function imagesOrdered(){
        return $this->images()->orderBy('sort', 'asc');
    }

    public function imagesFrontendVisible(){
        return $this->hasMany(PageImage::class)->frontendVisible();
    }

    public function mainMenuItem(){
        return $this->hasOne(MenuItem::class, 'content')
                ->where(MenuItem::getTableName().'.menu_id', '=', Menus::MENU_ID_MAIN)
                ->where(MenuItem::getTableName().'.type', '=', MenuItem::TYPE_PAGE)
                ;
    }

    public function isArticle() {
        return $this->type == static::TYPE_ARTICLE;
    }

    public function isHomePage() {
        return $this->type == static::TYPE_HOME;
    }

    public function isEnabled() {
        return $this->enabled == static::ENABLED_YES;
    }

    public static function findPage($id) {
        return static::whereNotIn('type', [static::TYPE_ARTICLE])->whereId($id)->first();
    }

    public static function findArticle($id) {
        return static::whereIn('type', [static::TYPE_ARTICLE])->whereId($id)->first();
    }

    public function url(array $paramaters = []) {

        if(substr( $this->slug, 0, 1 ) === "#"){
            $pageUrl = '#';
        } else {
            switch($this->type) {
                case static::TYPE_HOME:
                    $pageUrl = route('public.home', $paramaters);
                    break;
                default:
                    $allowSlugUrls = ss(Setting::SS_PUBLIC_PAGE_URL_SLUG_BASED_ENABLED);
                    $routeParameters = $allowSlugUrls ? ['slug' => $this->slug]: ['id' => $this->id];
                    $pageUrl = route('public.page.show', array_merge($routeParameters, $paramaters));
                    break;
            }
        }

        return $pageUrl;
    }

    /** Defines if should be shown on frond end. */
    public function scopeFrontEndVisible($query) {
        return $query->where($this->getTable() . '.enabled', '=', static::ENABLED_YES);
    }
    /** Defines if should be shown on frond end. */
    public function scopeType($query, $type) {
        return $query->whereType($type);
    }
    /** Defines if should be shown on frond end. */
    public function scopeSlug($query, $slug) {
        return $query->whereSlug($slug);
    }

    /**  */
    public static function userTopLevelPage($user) {
        $userType = (!is_null($user) ? $user->type : User::TYPE_USER);
        switch($userType) {
            case User::TYPE_USER:
            case User::TYPE_ADMIN:
            default:
                return null;
        }
    }

    /** Defines if should be shown on frond end. */
    public function scopeTopLevel($query, $user) {
        return $query->where('parent_id', '=', static::userTopLevelPage($user));
    }

    /** Defines if should be shown on frond end. */
    public function scopeUserScope($query, User $user = null) {
        $userType = (!is_null($user) ? $user->type : User::TYPE_USER);
        switch($userType){
            case User::TYPE_USER:
                break;
        }
        return $query->where('parent_id', '=', null);
    }

    /** . */
    public function scopeSubPagesSort($query, $column, $direction) {
        $column = (empty($column) ? PageOrderType::defaultColumn() : $column);
        $direction = (empty($direction) ? PageOrderType::defaultDirection() : $direction);
        return $query->orderByRaw("{$column} {$direction}");
    }

    /** . */
    public function scopeMyPaginate($query, $page) {
        switch($page->customView) {
//            case 'showMySubPagesAsGallery':
//            case 'showMySubPagesAsVideoGallery':
//                return $query->get();
            default:
                return $query->paginate(ss(Setting::SS_ARTICLE_CATEGORY_LIMIT));
        }
    }

    /**
     * @return PageModelPage|StartPage|ContactPage */
    public function getMyWebPage() {
        switch($this->type){
            case static::TYPE_HOME:
                return StartPage::get($this);
            case static::TYPE_CONTACT:
                return ContactPage::get($this);
            default:
                switch($this->id){
                    default:
                        return PageModelPage::get($this);
                }
        }
    }

    public function isList() {
        return in_array($this->type, [static::TYPE_PAGE_LIST]);
    }

    public function isFrontEndVisible() {
        return (intval($this->enabled) === static::ENABLED_YES);
    }

    public function canHaveSubPages($user = null) {
        return (!is_null($user) && $user->isAdmin() && in_array($this->type, PageType::subpagablePageTypes()));
    }

    public function canBeAssignedOnMenu() {
        return in_array($this->type, PageType::menuablePageTypes());
    }

    /** Return if the model can be deleted
     * @return boolean */
    public function canBeDeleted() {
        $canBeDeleted = false;
        if (in_array($this->type, PageType::creatablePageTypes())
                && $this->subPages->count() === 0) {
                $canBeDeleted = true;
        }

        return $canBeDeleted;
    }

    /**
     * @param Page $page
     * @return Collection */
    public static function getPageAncestors(Page $page = null) {
        $ancestors = collect();
        if(is_null($page)){
            return $ancestors;
        }
//        $ancestors->prepend($page);
        do {
            $parentPage = $page->parentPage;
            if(!is_null($parentPage)) {
                $ancestors->prepend($parentPage);
            }
            $page = $parentPage;
        } while (!is_null($parentPage));

        return $ancestors;
    }

    /** Extends parent boot
     * Define callback for CRUD events */
    public static function boot(){
        parent::boot();

        //set callback function for 'deleting' event that is triggered before db deletion
        static::deleting(function($item){
            //delete images
            $item->images()->each(function(PageImage $image){
                //delete page image
                $image->delete();
            });
            if(!is_null($item->mainMenuItem)){
                $item->mainMenuItem->delete();
            }
        });
    }

}
