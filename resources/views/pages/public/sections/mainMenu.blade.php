<?php

use App\Logic\Template\Menu\MenuManager;
/* @var $mainMenu MenuManager */

$menuItems = $mainMenu->getTreeItems(true);
?>
<?php
$activeClass = 'active';
$maxLevel = 2;
$levelConfiguration = [
    1 => [
      'wrapperClass' => 'initialState',
      'itemClass' => '',
      'itemWithChildrenClass' => '',
      'itemLinkClass' => '',
      'itemLinkWithChildrenClass' => '',
    ],
];
?>

    <?= view("{$viewBasePath}.partials.menu", [
        'items' => $menuItems,
        'level' => 1,
        'maxLevel' => $maxLevel,
        'levelConfiguration' => $levelConfiguration,
        'activeClass' => $activeClass,
    ]); ?>
