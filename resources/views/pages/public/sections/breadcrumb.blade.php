<?php

use App\Logic\Template\Breadcrumb;
use App\Logic\Template\BreadcrumbItem;
use App\Logic\Template\PageModelPage;

/* @var $webPage PageModelPage */
/* @var $breadcrumb Breadcrumb */
?>

@section('breadcrumb')
<?php if(!BreadcrumbItem::isPublicPageRoot($webPage)): ?>
<?php
$breadcrumbView = $breadcrumb->getItems()->each(function($item, $key) {
    $item->linkView = $item->getView("pages.public.partials.breadcrumbLink");
})->values()->implode('linkView', '');

$customBreadcrumbImage = \View::shared('customBReadcrumbImage', asset($assetBasePath.'/images/breadcrumbs-bg-09-1920x420.jpg'));

$page=$webPage->model();
if(!isset($page->property->type_id)){ //DEN EINAI PROPERTY PAGE
?>
<!-- Breadcrumbs-->
<section class="breadcrumbs-custom bg-image context-dark breadcrumbs-bg-1" data-opacity="48" style="background-image: url(<?= $customBreadcrumbImage; ?>);">
    <div class="container">
        <h2 class="breadcrumbs-custom-title"><?= e($webPage->title()); ?></h2>
    </div>
</section>
<?php }?>
<section class="section-xs bg-white">
    <div class="container">
        <ul class="breadcrumbs-custom-path">
            <?= $breadcrumbView ?>
        </ul>
    </div>
</section>
<div class="divider-section"></div>
<?php endif; ?>
@endsection