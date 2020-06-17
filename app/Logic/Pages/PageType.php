<?php

namespace App\Logic\Pages;

use App\Page;
use App\Logic\App\Permission;

/** Helper for page view. 
 */
class PageType {
    
    /** Return all Page types.
     * @param boolean $asSelectList if true return array {type => label, ...} else {type, ...}
     * @return array */
    public static function allPageTypes($asSelectList = false) {
        $typesSources = static::pageTypesSources();
        return (!$asSelectList ? array_keys($typesSources) : static::convertPageTypeSourcesToSelectList($typesSources));
    }

    /**
     * @param int $type
     * @param boolean $ordered idf it si true return format "{listOrder}. {label}"
     * @return string */
    public static function pageTypeLabel($type, $ordered = false) {
        $typesSources = static::pageTypesSources();
        $prefix = (($ordered && array_key_exists($type, $typesSources))
                ? "{$typesSources[$type]['listOrder']}. "
                : '');
        return (array_key_exists($type, $typesSources)
                ? "{$prefix}{$typesSources[$type]['label']}"
                : "{$prefix}PAGE_TYPE_{$type}");
    }

    /**
     * @param int $type
     * @return string */
    public static function orderedPageTypes($type) {
        $typesSources = static::pageTypesSources();
        return (array_key_exists($type, $typesSources) ? $typesSources[$type]['label'] : "PAGE_TYPE_{$type}");
    }

    /** Return all Page types that must be exist once.
     * @param boolean $asSelectList if true return array {type => label, ...} else {type, ...}
     * @return array */
    public static function uniquePageTypes($asSelectList = false) {
        $typesSources = static::pageTypesSources();
        $filtered = array_filter($typesSources, function($source) {
            return $source['unique'];
        });
        return (!$asSelectList ? array_keys($filtered) : static::convertPageTypeSourcesToSelectList($filtered));
    }

    /** Return all Page types that can be assigned to menu.
     * @param boolean $asSelectList if true return array {type => label, ...} else {type, ...}
     * @return array */
    public static function menuablePageTypes($asSelectList = false) {
        $typesSources = static::pageTypesSources();
        $filtered = array_filter($typesSources, function($source) {
            return $source['menuable'];
        });
        return (!$asSelectList ? array_keys($filtered) : static::convertPageTypeSourcesToSelectList($filtered));
    }

    /** Return all Page types that can have sub pages.
     * @param boolean $asSelectList if true return array {type => label, ...} else {type, ...}
     * @return array */
    public static function subpagablePageTypes($asSelectList = false) {
        $typesSources = static::pageTypesSources();
        $filtered = array_filter($typesSources, function($source) {
            return $source['subpagable'];
        });
        return (!$asSelectList ? array_keys($filtered) : static::convertPageTypeSourcesToSelectList($filtered));
    }

    /**
     * @param type $asSelectList
     * @return array */
    public static function creatablePageTypes($asSelectList = false) {
        $typesSources = static::pageTypesSources();
        //filter permitted and exclude unique types that already exists
        $userPermittedTypes = Permission::creatablePageTypes(auth()->user());
        $usedUniqueTypes = static::usedUniqueTypes();
        $filtered = array_filter($typesSources, function($source, $type) use ($userPermittedTypes, $usedUniqueTypes) {
            return ($source['creatable'] && in_array($type, $userPermittedTypes) && !in_array($type, $usedUniqueTypes));
        }, ARRAY_FILTER_USE_BOTH);

        return (!$asSelectList ? array_keys($filtered) : static::convertPageTypeSourcesToSelectList($filtered));
    }

    /**
     * @param type $asSelectList
     * @return array */
    public static function usedUniqueTypes($asSelectList = false) {
        $allUniqueTypes = static::uniquePageTypes($asSelectList);
        $usedUniqueTypes = Page::whereIn('type', $allUniqueTypes)->get()->pluck('type')->toArray();
        $filtered = array_filter($allUniqueTypes, function($type) use ($usedUniqueTypes) {
            return in_array($type, $usedUniqueTypes);
        }, ($asSelectList ? ARRAY_FILTER_USE_KEY : 0));

        return $filtered;
    }

    /** Return Page types as select list {type => label, ...}.
     * @param array $types
     * @param boolean $ordered if it is true add the order number before label
     * @return array */
    public static function convertPageTypeSourcesToSelectList($types, $ordered = false) {
        return array_map(function($source) use ($ordered) {
            $prefix = ($ordered ? "{$source['listOrder']}. " : '');
            return "{$prefix}{$source['label']}";
        }, $types);
    }

    /**
     * @param int $type
     * @return string */
    public static function pageTypeDefaultViewName($type) {
        $typesSources = static::pageTypesSources();
        return (array_key_exists($type, $typesSources)
                ? $typesSources[$type]['defaultView']
                : PageView::$defaultPageViewName);
    }
    
    /**
     * @param string $tableName
     * @param string $columnName
     * @return string */
    public static function pageTypeLabelOrderByColumn($tableName, $columnName) {
        $pageTypes = static::allPageTypes();
        //IF(`type` = {type1}, '{orderLabel1}',
        //IF(`type` = {type2}, '{orderLabel2}',
        //...
        //IF(`type` = {type2}, '{orderLabelN}',
        //'a'
        //)))
        $typeCount = count($pageTypes);
        $columnFullName = "`{$tableName}`.`{$columnName}`";
        if($typeCount === 0){
            $column = $columnFullName;
        } else {
            $columnSufix = '';
            foreach($pageTypes as $type){
                $orderedLabel = static::pageTypeLabel($type, true);
                $columnConditions[] = "IF({$columnFullName} = {$type}, '{$orderedLabel}'";
                $columnSufix .= ")";
            }
            array_push($columnConditions, "'a'");
            $column = implode(', ', $columnConditions).str_pad('', $typeCount, ')');
        }
        
        return $column;
    }

    /**
     * @param string $tableName
     * @param string $columnName
     * @return string */
    public static function pageTypeLabelWhereColumn($tableName, $columnName) {
        $pageTypes = static::allPageTypes();
        //IF(`type` = {type1}, '{label1}',
        //IF(`type` = {type2}, '{label2}',
        //...
        //IF(`type` = {typeN}, '{labelN}',
        //''
        //)))
        $typeCount = count($pageTypes);
        $columnFullName = "`{$tableName}`.`{$columnName}`";
        if($typeCount === 0){
            $column = $columnFullName;
        } else {
            $columnSufix = '';
            foreach($pageTypes as $type){
                $label = static::pageTypeLabel($type);
                $columnConditions[] = "IF({$columnFullName} = {$type}, '{$label}'";
                $columnSufix .= ")";
            }
            $columnConditions[] = "''";
            $column = implode(', ', $columnConditions).str_pad('', $typeCount, ')');
        }
        
        return $column;
    }
    
    /** Return assoc array with source data for all page types
     * @return array */
    public static function pageTypesSources() {
        $transBaseName = \View::shared('transBaseName');
        $types = [
            Page::TYPE_HOME => [
                'label' => trans("{$transBaseName}.page.pageType.home"),
                'defaultView' => 'index',
                'listOrder' => 1,
                'unique' => true, 'creatable' => true, 'menuable' => true, 'subpagable' => false,
            ],
            Page::TYPE_CONTACT => [
                'label' => trans("{$transBaseName}.page.pageType.contact"),
                'defaultView' => 'contact',
                'listOrder' => 2,
                'unique' => true, 'creatable' => true, 'menuable' => true, 'subpagable' => false,
            ],
            Page::TYPE_PAGE => [
                'label' => trans("{$transBaseName}.page.pageType.simplePage"),
                'defaultView' => 'page',
                'listOrder' => 5,
                'unique' => false, 'creatable' => true, 'menuable' => true, 'subpagable' => true,
            ],
            Page::TYPE_PAGE_LIST => [
                'label' => trans("{$transBaseName}.page.pageType.pageList"),
                'defaultView' => 'pageList',
                'listOrder' => 4,
                'unique' => false, 'creatable' => true, 'menuable' => true, 'subpagable' => true,
            ],
            Page::TYPE_ARTICLE => [
                'label' => trans("{$transBaseName}.page.pageType.article"),
                'defaultView' => 'article',
                'listOrder' => 6,
                'unique' => false, 'creatable' => true, 'menuable' => false, 'subpagable' => false,
            ],
        ];
        return $types;
    }
}
