<?php

use App\Logic\Template\Menu\MenuManagerItem;

/* @var $parentItemId int  */
/* @var $items MenuManagerItem[] */
?>
<?php if(is_array($items) && count($items) > 0 ): ?>
<ul class="list-group">
<?php foreach($items as $item): /* @var $item MenuManagerItem */?>
    <li class="list-group-item list-group-item-action" data-id="<?= $item->id() ?>">
        <i class="glyphicon glyphicon-move"></i>
        <?= $item->name() ?>
        <button type="button" class="btn btn-danger removeMe"><i class="glyphicon glyphicon-trash"></i></button>
        <input type="hidden" class="activeMenuInput" name="menu[active][<?= intval($parentItemId)?>][<?= $item->id() ?>]" value="1"/>
        <input type="hidden" class="deletedMenuInput" disabled="" name="menu[deleted][<?= intval($parentItemId)?>][<?= $item->id() ?>]" value="1"/>
        <?php if($item->hasChildren()): ?>
        <br/>
        <br/>
        <?= view($viewBasePath.'.settings.partials.menu', [
            'parentItemId' => $item->id(),
            'items' => $item->children(),
        ]); ?>
        <?php endif; ?>
    </li>
<?php endforeach; ?>
</ul>
<?php endif; ?>