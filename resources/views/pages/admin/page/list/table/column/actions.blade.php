<?php 

use App\Http\Controllers\AdminController;
use App\Logic\App\EntityManager;
use App\Page;

/** It used from laravel-datatable in order to edit "state" column view.
 * You should convert php code to string because it is given as eval()'s param  */
/* @var $model Page */
?>
<?php
//To show available php variables in comment next row:
//dump(array_keys(get_defined_vars()));
//available names are found inside array "data"
$routeBaseName = myApp()->getConfig('adminRouteBaseName');
?>
<?php if($model->canHaveSubPages(auth()->user())): ?>
<a title="<?= trans($transBaseName.'.listEntity.action.list') ?>" class="btn btn-default" href="<?= route("$routeBaseName.entity.list", [$entity, $model->id, EntityManager::PAGE])?>"><i class="glyphicon glyphicon-list"></i></a>
<?php endif; ?>
<a title="<?= trans($transBaseName.'.listEntity.action.edit') ?>" class="btn btn-default" href="<?= route("$routeBaseName.entity.edit", [$entity, $model->id])?>"><i class="glyphicon glyphicon-edit"></i></a>
<?php if(!$excludeDelete): ?>
<button type="button" title="<?= trans($transBaseName.'.listEntity.action.delete') ?>" class="confirm-delete btn btn-danger" onclick="javascript:checkDelete(<?= $model->id; ?>);"><i class="glyphicon glyphicon-remove"></i></button>
<?php endif; ?>