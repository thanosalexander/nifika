<?php

use App\Logic\Base\BaseModel;
/** It used from laravel-datatable in order to edit "state" column view.
 * You should convert php code to string because it is given as eval()'s param  */
?>
<?php
//To show available php variables in comment next row:
//dd(get_defined_vars());
//available names are found inside array "data"
?>
<span 
    onclick="javascript:checkSwitchStatus(<?='<?=$id?>'?>, '<?= $column; ?>');" 
    title="<?= trans($transBaseName.'.listEntity.pressToDisable') ?>" 
    class="<?= $column; ?>TableField btn label label-success<?='<?=$'.$column.' == '. BaseModel::ENABLED_NO.' ? " hidden": ""?>'?>">
    <?= !isset($labels['on']) ? trans($transBaseName.'.form.enabled') : $labels['on'] ?>
</span>
<span 
    onclick="javascript:checkSwitchStatus(<?='<?=$id?>'?>, '<?= $column; ?>');" 
    title="<?= trans($transBaseName.'.listEntity.pressToEnable') ?>" 
    class="<?= $column; ?>TableField btn label label-danger<?='<?=$'.$column.' == '. BaseModel::ENABLED_YES.' ? " hidden": ""?>'?>">
    <?= !isset($labels['off']) ? trans($transBaseName.'.form.disabled') : $labels['off'] ?>
</span>