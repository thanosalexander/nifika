<?php

namespace App\Logic\Template\Menu;

/**
 * Description of UserMenu
 *
 * @author thanasis
 */

abstract class MenuAbstract {

    protected static function filterShownItems($allItems){
        $checkActive = function($item, $checkActive){
            if(isset($item['children'])){
                $activeSubItems = array_filter($item['children'], function($subItem) use ($checkActive){
                    return $checkActive($subItem, $checkActive);
                });
                $item['active'] = (count($activeSubItems) > 0);
            }
            return $item['active'];
        };

        $filterShown = function($items, $filterShown){
            $shownItems = [];
            foreach($items as $itemKey => $item){
                if(isset($item['children'])){
                    $item['children'] = $filterShown($item['children'], $filterShown);
                    $shown = $item['children'] || [];
                    $item['shown'] = (count($shown) > 0);
                }
                if($item['shown']){
                    $shownItems[] = $item;
                }
            }
            return $shownItems;
        };

        $filterItems = function($items, $filterShown, $checkActive){
            $shownItems = [];
            $filteredItems = [];
            foreach($items as $item){
                if(isset($item['children'])){
                    $item['children'] = $filterShown($item['children'], $filterShown);
                    $children = $item['children'] || [];
                    $item['shown'] = (count($children) > 0);
                }
                if($item['shown']){
                    $shownItems[] = $item;
                }
            }
            foreach($shownItems as $item){
                $item['active'] = $checkActive($item, $checkActive);
                $filteredItems[] = $item;
            }
            return $filteredItems;
        };

        return $filterItems($allItems, $filterShown, $checkActive);

    }


}
