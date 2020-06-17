<?php

use App\Logic\Template\Breadcrumb;
/* @var $breadcrumb Breadcrumb */
$breadcrumbView = $breadcrumb->getItems()->each(function($item) use ($viewBasePath){
    $item->linkView = $item->getView("{$viewBasePath}.includes.sections.breadcrumbLink");
})->values()->implode('linkView', ' &raquo; ');
?>
<div class="row marginBottom15">
    <div class="col-xs-12 small">
        <strong><?= trans("{$transBaseName}.menu.breadcrumb.youAreHere"); ?>::</strong> <?= $breadcrumbView ?>
    </div>
</div>