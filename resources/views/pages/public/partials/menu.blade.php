<?php

/* @var $items array */
/* @var $level int  */
/* @var $levelConfiguration array  */
/* @var $maxLevel int  */
$nextLevel = $level+1;
$allowSubMenu = ($nextLevel <= $maxLevel);
?>
<?php if(is_array($items) && count($items) > 0 ): ?>

    <?php foreach ($items as $item): ?>
    <?php
    $children = (!$allowSubMenu ? [] : $item->children());
    $hasChildren = !empty($children);
    $subMenu = '';
    if($nextLevel <= $maxLevel){
        $subMenu = view("{$viewBasePath}.partials.menu", [
                'items' => $children,
                'level' => $nextLevel,
                'maxLevel' => $maxLevel,
                'levelConfiguration' => $levelConfiguration,
                'activeClass' => $activeClass,
            ]);
    }
    ?>
    <?= view("{$viewBasePath}.partials.menuItem", [
        'item' => $item,
        'hasChildren' => $hasChildren,
        'subMenu' => $subMenu,
        'level' => $level,
        'finalLevel' => $maxLevel,
        'activeClass' => $activeClass,
        'itemClass' => $levelConfiguration[$level]['itemClass'],
        'itemLinkClass' => $levelConfiguration[$level]['itemLinkClass'],
        'itemWithChildrenClass' => $levelConfiguration[$level]['itemWithChildrenClass'],
        'itemLinkWithChildrenClass' => $levelConfiguration[$level]['itemLinkWithChildrenClass'],
    ]); ?>
    <?php endforeach; ?>

<?php endif; ?>
