<?php

use App\Logic\Pages\PageOrderType;
use App\Logic\Template\PageModelPage;
use App\Page;
use App\PageImage;
/* @var $webPage PageModelPage */

$subPages = $webPage->model()->subPages()
        ->frontEndVisible()
        ->subPagesSort(
        PageOrderType::column($webPage->model()->sortType),
        PageOrderType::direction($webPage->model()->sortType)
)->get();
?>

@extends("{$layoutBasePath}.default")


@section('contentTop')
<div id="slider">
    <?php if ($webPage->hasMedia()) : ?>
    <?php if ($webPage->hasVideo()): ?>
    <?php elseif ($webPage->hasImage()): ?>
    <img src="<?= e($webPage->image()); ?>"/>
    <?php else: ?>
    <?php endif; ?>
    <?php endif; ?>
</div>
        
@endsection

@section('content')
<div class="wrapper">
    <div id="moreinfo">
        <img src="<?= e(asset($assetBasePath.'/images/custom.png')) ?>"/>
        <h2><?= e($webPage->title()); ?></h2>
        <p><?= e($webPage->description()); ?></p>
    </div>
    <div id="services_main">
        <?php foreach ($subPages as $serviceCategoryPage) : /* @var $serviceCategoryPage Page */ ?>
        <?php $serviceCategoryWebPage = $serviceCategoryPage->getMyWebPage();
        $serviceCategoryTitleImage = $serviceCategoryWebPage->imageGalleryNthImage(PageImage::FIRST);
        ?>
        <?php if (!is_null($serviceCategoryTitleImage) && !is_null($serviceCategoryTitleImage->filePath())) : ?>
        <img src="<?= e($serviceCategoryTitleImage->filePath()); ?>" style="display:block;margin-top:100px; margin-left:100px;" />
        <?php endif; ?>
        <?php $categoryServicePages = $serviceCategoryWebPage->model()->subPages()
                ->frontEndVisible()
                ->subPagesSort(
                PageOrderType::column($serviceCategoryWebPage->model()->sortType),
                PageOrderType::direction($serviceCategoryWebPage->model()->sortType)
        )->get(); ?>
        <?php foreach ($categoryServicePages as $categoryServicePage) : /* @var $categoryServicePage Page */ ?>
        <?php $categoryServiceWebPage = PageModelPage::get($categoryServicePage);
        $categoryServiceTitleImage = $categoryServiceWebPage->imageGalleryNthImage(PageImage::FIRST);
        ?>
        <div class="services_box ">
            <?php if (!is_null($categoryServiceTitleImage) && !is_null($categoryServiceTitleImage->filePath())) : ?>
            <img src="<?= e($categoryServiceTitleImage->filePath()); ?>"/>
            <?php endif; ?>
            <a href="<?= e($categoryServiceWebPage->url());  ?>"><div class="services_button">MORE</div></a>
        </div>
        <?php endforeach; ?>
        <?php endforeach; ?>
    </div>
</div>
@endsection