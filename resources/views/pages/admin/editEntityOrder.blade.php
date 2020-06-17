<?php

use App\Logic\App\EntityManager;
use App\Logic\Template\Breadcrumb;
use App\Setting;
if(!empty($customContentView)){
    $contentView = $customContentView;
} else {
    $contentView = "{$viewBasePath}." . $entityName . ".editOrder";
}
?>
@if(\View::exists($contentView))
    <?= view($contentView, [
    'pageData' => $pageData, 
    'entityName' => $entityName, 
    'model' => (!empty($model) ? $model : null),
    'relationEntityName' => (isset($relationEntityName) ? $relationEntityName : null),
    'parentModel' => (isset($parentModel) ? $parentModel : null),
    'user' => $user, 
    ]
    ) ?>
@endif

@extends("{$layoutBasePath}.default")

@section('contentTop')
<?php if(ss(Setting::SS_ADMIN_SHOW_BREADCRUMB)): ?>
<?php 
$breadcrumb = Breadcrumb::_get();
$breadcrumb->createAdminEntityBreadcrumb((!empty($relationEntityName) ? $relationEntityName : $entityName),
    (!empty($model)/* edit */ ? $model : /* create */(!empty($parentModel) ? $parentModel : null)),
    (trans($transBaseName. '.page.editPageOrder'))
);
?>
<?= view($viewBasePath.'.includes.sections.breadcrumb', ['breadcrumb' => $breadcrumb]) ?>
<?php endif; ?>
@parent
@endSection

@section('bodyEnd')
@parent
@endsection
