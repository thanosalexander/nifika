<?php

namespace App\Logic\Pages;

use App\Page;
use App\Logic\App\Permission;

/** Helper for page view. 
 */
class PageOrderType {
    
    const CREATED_DATE_ASC = 1;
    const CREATED_DATE_DESC = 2;
    const DISPLAY_ORDER_ASC = 5;
    const DISPLAY_ORDER_DESC = 6;
    
    
    /** Return all Page types.
     * @param boolean $asSelectList if true return array {type => label, ...} else {type, ...}
     * @return array */
    public static function allPageOrderTypes($asSelectList = false) {
        $typesSources = static::pageOrderTypesSources();
        return (!$asSelectList ? array_keys($typesSources) : static::convertPageTypeSourcesToSelectList($typesSources));
    }

    /**
     * @param int $type
     * @param boolean $ordered idf it si true return format "{listOrder}. {label}"
     * @return string */
    public static function pageOrderTypeLabel($type, $ordered = false) {
        $typesSources = static::pageOrderTypesSources();
        $prefix = (($ordered && array_key_exists($type, $typesSources))
                ? "{$typesSources[$type]['listOrder']}. "
                : '');
        return (array_key_exists($type, $typesSources)
                ? "{$prefix}{$typesSources[$type]['label']}"
                : "{$prefix}PAGE_ORDER_TYPE_{$type}");
    }
    
    /**
     * @param int $type
     * @return string|null
     */
    public static function column($type) {
        $typesSources = static::pageOrderTypesSources();
        $typeSources = (array_key_exists($type, $typesSources) ? $typesSources[$type]: null);
        return !is_null($typeSources) ? $typeSources['column'] : null;
    }
    
    /**
     * @param int $type
     * @return string asc|desc
     */
    public static function direction($type) {
        $typesSources = static::pageOrderTypesSources();
        $typeSources = (array_key_exists($type, $typesSources) ? $typesSources[$type]: null);
        return !is_null($typeSources) ? $typeSources['direction'] : 'asc';
    }
    
    /**
     * @return string|null
     */
    public static function defaultColumn() {
        return static::column(static::DISPLAY_ORDER_ASC);
    }
    
    /**
     * @return string asc|desc
     */
    public static function defaultDirection() {
        return static::direction(static::DISPLAY_ORDER_ASC);
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
    
    /** Return assoc array with source data for all page types
     * @return array */
    public static function pageOrderTypesSources() {
        $transBaseName = \View::shared('transBaseName');
        $acendingLabel = trans("{$transBaseName}.sortDirection.ascending");
        $descendingLabel = trans("{$transBaseName}.sortDirection.descending");
        $types = [
            static::CREATED_DATE_ASC => [
                'label' => trans("{$transBaseName}.page.field.createdDate")." - {$acendingLabel}",
                'listOrder' => 1,
                'column' => 'created_at', 'direction' => 'asc',
            ],
            static::CREATED_DATE_DESC => [
                'label' => trans("{$transBaseName}.page.field.createdDate")." - {$descendingLabel}",
                'listOrder' => 1,
                'column' => 'created_at', 'direction' => 'desc',
            ],
            static::DISPLAY_ORDER_ASC => [
                'label' => trans("{$transBaseName}.page.field.sort")." - {$acendingLabel}",
                'listOrder' => 1,
                'column' => 'sort', 'direction' => 'asc',
            ],
            static::DISPLAY_ORDER_DESC => [
                'label' => trans("{$transBaseName}.page.field.sort")." - {$descendingLabel}",
                'listOrder' => 1,
                'column' => 'sort', 'direction' => 'desc',
            ],
        ];
        return $types;
    }
}
