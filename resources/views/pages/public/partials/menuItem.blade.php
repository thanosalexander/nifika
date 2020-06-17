<?php

use App\Logic\Template\Menu\MenuManagerItem;

/* @var $level int  */
/* @var $finalLevel int  */
/* @var $item MenuManagerItem */
/* @var $wrapperClass string */

if($level === 1 && $finalLevel > 1){
    $itemClass = ($hasChildren ? $itemWithChildrenClass: $itemClass);
    $itemLinkClass = ($hasChildren ? $itemLinkWithChildrenClass: $itemLinkClass);
    $itemActiveClass = ($item->isActive() ? $activeClass : '');
    $itemUrl = ($hasChildren ? '#' : e($item->url()));
} else if($level < $finalLevel){
    $itemClass = ($hasChildren ? $itemWithChildrenClass: $itemClass);
    $itemLinkClass = ($hasChildren ? $itemLinkWithChildrenClass: $itemLinkClass);
    $itemActiveClass = ($item->isActive() ? $activeClass : '');
    $itemUrl = ($hasChildren ? '#' : e($item->url()));
} else if($level === $finalLevel){
    $itemClass = '';
    $itemLinkClass = '';
    $itemActiveClass = ($item->isActive() ? $activeClass : '');
    $itemUrl = e($item->url());
}
?>

    <a class="<?= $itemClass ?> <?= $itemLinkClass ?> <?= $itemActiveClass ?>" href="<?= $itemUrl ?>"><?= e($item->name()); ?></a>
