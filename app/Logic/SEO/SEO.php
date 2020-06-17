<?php

namespace App\Logic\SEO;

use App\Logic\App\Assets;
use App\Logic\Base\BaseFileModel;
use App\Logic\Base\BaseModel;
use App\Logic\Locales\AppLocales;
use App\Page;
use App\Setting;
use App\Store;

/** Creates title and description for each page. */
class SEO {

    /** @var string The variable to pass from controller to view. */
    const VAR_NAME_TITLE = 'seoTitle';

    /** @var string The variable to pass from controller to view. */
    const VAR_NAME_DESCRIPTION = 'seoDescription';
    
    /** @var string The variable to pass from controller to view. */
    const VAR_NAME_KEYWORDS = 'seoKeywords';
    
    /** @var string The variable to pass from controller to view. */
    const VAR_NAME_CANONICAL_URL = 'seoCanonicalUrl';

    /** @var string The variable to pass from controller to view. */
    const VAR_NAME_OGDATA = 'seoOGData';

    /** @var string The variable to pass from controller to view. */
    const VAR_NAME_SCHEMA_DATA = 'seoSchemaData';

    /** @var string The left delimiter for parsing. */
    const DELIMITER_LEFT = '{';

    /** @var string The right delimiter for parsing. */
    const DELIMITER_RIGHT = '}';

    /** Populate SEO variables and share them with the views.
     * @param object|null $resource An object to use for SEO data.
     * @return void */
    public static function run($resource = null) {
        \View::share(static::VAR_NAME_TITLE, static::getTitle(\Request::route()->getName(), $resource));
        \View::share(static::VAR_NAME_DESCRIPTION, static::getDescription(\Request::route()->getName(), $resource));
        \View::share(static::VAR_NAME_KEYWORDS, static::getKeywords(\Request::route()->getName(), $resource));
        \View::share(static::VAR_NAME_CANONICAL_URL, static::getCanonicalUrl(\Request::route()->getName(), $resource));
        \View::share(static::VAR_NAME_OGDATA, static::getOGData(\Request::route()->getName(), $resource));
//        \View::share(static::VAR_NAME_SCHEMA_DATA, 
//                      static::getSchemaData(\Request::route()->getName(), $resource));
    }

    protected static function routes() {
        return [
            'public.home' => [
                'title' => '{sitename} - {page.metaTitle}',
                'description' => '{page.metaDescription}',
                'keywords' => '{page.metaKeywords}',
                'canonicalUrl' => '{page.canonicalUrl}',
            ],
            'public.page.show' => [
                'title' => '{sitename} - {page.metaTitle}',
                'description' => '{page.metaDescription}',
                'keywords' => '{page.metaKeywords}',
                'canonicalUrl' => '{page.canonicalUrl}',
            ],
        ];
    }

    /** Build an array with ogData
     * @param string $routeName
     * @param BaseModel|null $resource
     * @return array */
    protected static function buildOGData($routeName, $resource) {
        $data = [];
        switch ($routeName) {
            case 'public.home':
                $data = static::buildPageOGData($resource, Page::TYPE_HOME);
                break;
            case 'public.page.show':
                if (!empty($resource)) {
                    $data = static::buildPageOGData($resource, $resource->type);
                }
                break;
        }
        return $data;
    }

    /** Defines what should happen when the corresponding string is found.
     *  @return array */
    protected static function parsable() {
        return [
            'sitename' => function() {
                return static::sitename();
            },
            'page.metaTitle' => function($resource) {
                $res = '';
                if(is_null($resource)){ return $res; }
                $request = request();
                $res = $resource->getMyWebPage()->metaTitle();
                if(($request->has('page') && $request->get('page') > 1)) {
                   $res .= ' - Σελίδα '. $request->get('page');
                }
                return $res;
            },
            'page.metaDescription' => function($resource) {
                $res = '';
                if(is_null($resource)){ return $res; }
                $res = $resource->getMyWebPage()->metaDescription();
                return $res;
            },
            'page.metaKeywords' => function($resource) {
                $res = '';
                if(is_null($resource)){ return $res; }
                $res = $resource->getMyWebPage()->metaKeywords();
                return $res;
            },
            'page.canonicalUrl' => function($resource) {
                $res = '';
                $request = request();
                if(($request->has('page') && $request->get('page') > 1)) {
                   $res = $resource->url();
                }
                return $res;
            },
        ];
    }

    /** Parses a parsable string.
     * @param string $forParsing
     * @param object|null $resource
     * @return string */
    protected static function parse($forParsing, $resource) {
        $res = '';
        $parameters = static::getStringParameters($forParsing);
        if (!empty($parameters)) {
            $parsable = static::parsable();
            foreach ($parameters as $param) {
                if (isset($parsable[$param])) {
                    $parseRes = $parsable[$param]($resource);
                    $forParsing = str_replace_first(static::DELIMITER_LEFT . $param . static::DELIMITER_RIGHT, $parseRes, $forParsing);
                }
            }
        }
        $res = $forParsing;
        return $res;
    }

    protected static function get($type, $routeName, $resource = null) {
        $routeName = \Request::route()->getName();
        $res = static::defaultTitle();
        $routes = static::routes();
        if (isset($routes[$routeName], $routes[$routeName][$type])) {
            $res = static::parse($routes[$routeName][$type], $resource);
        }
        return $res;
    }

    /** Get the page title.
     * @param string $routeName The name of the route.
     * @param object $resource Pass a resource (ex. Ad) to be used to build the title.
     * @return string */
    protected static function getTitle($routeName, $resource = null) {
        return static::get('title', $routeName, $resource);
    }
    
    /** Get the page description.
     * @param string $routeName The name of the route.
     * @param object $resource Pass a resource (ex. Ad) to be used to build the description.
     * @return string */
    protected static function getDescription($routeName, $resource = null) {
        return static::get('description', $routeName, $resource);
    }

    /** Get the page keywords.
     * @param string $routeName The name of the route.
     * @param object $resource Pass a resource (ex. Ad) to be used to build the description.
     * @return string */
    protected static function getKeywords($routeName, $resource = null) {
        return static::get('keywords', $routeName, $resource);
    }
    
    /** Get the page keywords.
     * @param string $routeName The name of the route.
     * @param object $resource Pass a resource (ex. Ad) to be used to build the description.
     * @return string */
    protected static function getCanonicalUrl($routeName, $resource = null) {
        return static::get('canonicalUrl', $routeName, $resource);
    }

    /** Get the OG data.
     * @param string $routeName The name of the route.
     * @param object $resource Pass a resource (ex. Ad) to be used to build the OG data.
     * @return array */
    protected static function getOGData($routeName, $resource = null) {
        return static::buildOGData($routeName, $resource);
    }

    /** Get the Schema data.
     * @param string $routeName The name of the route.
     * @param object $resource Pass a resource (ex. Ad) to be used to build the OG data.
     * @return array */
    protected static function getSchemaData($routeName, $resource = null) {
        $data = static::buildSchemaData($routeName, $resource);
        return !empty($data) ? json_encode($data, JSON_UNESCAPED_SLASHES) : '';
    }

    /** The default site name.
     * @return string */
    protected static function sitename() {
        return ss(Setting::SS_SEO_SITENAME);
    }

    /** The default site title.
     * @return string */
    protected static function defaultTitle() {
        return static::sitename();
    }

    /** Build an array with ogData
     * @param Page $resource
     * @return array */
    protected static function buildPageOGData($resource, $pageType) {
        $data = [];
        $logoOGData = static::buildLogoOGData(Assets::logoUrl());
        switch ($pageType) {
            case Page::TYPE_HOME:
                $pageResource = $resource->getMyWebPage();
                $ogType = 'website';
                $ogImage = !empty($logoOGData) ? $logoOGData : [];
                break;
            case Page::TYPE_CONTACT:
                $pageResource = $resource->getMyWebPage();
                $ogType = 'page';
                $ogImage = !empty($logoOGData) ? $logoOGData : [];
                break;
            case Page::TYPE_PAGE:
            case Page::TYPE_PAGE_LIST:
            case Page::TYPE_ARTICLE:
                $pageResource = $resource->getMyWebPage();
                $ogType = 'article';
                $imageOGData = static::buildFileModelOGData($resource);
                $ogImage = (!empty($imageOGData) 
                        ? $imageOGData
                        : (!empty($logoOGData) ? $logoOGData : [] )
                        );
                break;
        }

        $data['og:url'] = $pageResource->url();
        $data['og:type'] = $ogType;
        $data['og:locale'] = AppLocales::getCurrentLocaleRegional();
        $data['og:title'] = $pageResource->metaTitle();
        $data['og:description'] = $pageResource->metaDescription();
        $data = array_merge($data, $ogImage);
        $data['fb:app_id'] = ss(Setting::SS_FACEBOOK_GLOBAL_APP_ID);
        $data['og:site_name'] = static::sitename();
        
        return $data;
    }

    /** Validate image for og data
     * @param BaseFileModel $image
     * @return boolean */
    protected static function buildFileModelOGData($image) {
        $data = [];
        if (empty($image) || !($image instanceof BaseFileModel) && is_null($image->filePath())) {
            return $data;
        }

        $url = $image->filePath();
        //get file`s info
        $imageInfo = $image->getFileInfo();
        //if info is null request them
        if (empty($imageInfo)) {
            //request file`s info 
            $newImageInfo = $image->requestFileInfo();
            //set the results to model`s attribute
            $image->setFileInfo($newImageInfo);
            //save model with new file`s info
            $image->save();
            $imageInfo = $newImageInfo;
        }
        $data = static::buildImageOGData($url, $imageInfo);
        
        return $data;
    }
    
    /** Validate image for og data
     * @param BaseFileModel $image
     * @return boolean */
    protected static function buildLogoOGData() {
        $data = [];
        $filePath = Assets::logoFullPath();
        if(!empty($filePath) && file_exists($filePath)){
            $imageInfo = getimagesize($filePath);
            $data = static::buildImageOGData(Assets::logoUrl(), $imageInfo);
        }
        return $data;
    }
    
    /** Validate image for og data
     * @param string $url an htpp url
     * @param array $imageInfo return value from getimagesize()
     * @return array */
    protected static function buildImageOGData($url, $imageInfo) {
        $data = [];
        if (is_array($imageInfo)) {
            list($imageInfo[0], $imageInfo[1]) = $imageInfo;
            if ($imageInfo[0] >= 200 && $imageInfo[1] >= 200) {
                $data['og:image'] = $url;
                $data['og:image:type'] = $imageInfo['mime'];
                $data['og:image:width'] = $imageInfo[0];
                $data['og:image:height'] = $imageInfo[1];
            }
        }
        
        return $data;
    }

    /** Build an array with schema data
     * @param string $routeName
     * @param BaseModel|null $resource
     * @return array */
    protected static function buildSchemaData($routeName, $resource) {
        $data = [];
        switch ($routeName) {
            case 'public.home':
                $frontEndStores = Store::getFrontEndStores();
                $storeOrdering = 0;
                $data = [];
                foreach ($frontEndStores as $store) {
                    if ($storeOrdering === 0) { //main store
                        $data = static::buildStoreSchemaData($store);
                    } else { //additional store
                        $data['department'][] = static::buildStoreSchemaData($store);
                    }
                    $storeOrdering++;
                }
                break;
        }

        return $data;
    }

    /** Searches the given string for any parameters within.
     * A parameters is any string between the left and right delimiters.
     * example: array(  0=>'PRODUCT NAME',
     *                  1=>'PRODUCT TYPE', )
     * @param string $string
     * @return array */
    protected static function getStringParameters($string) {
        $subject = $string;
        $pattern = '/\\%s([^\\%s]*)\\%s/i';
        $pattern = sprintf($pattern, static::DELIMITER_LEFT, static::DELIMITER_RIGHT, static::DELIMITER_RIGHT);
        $matches = [];
        preg_match_all($pattern, $subject, $matches);
        return $matches[1];
    }

}
