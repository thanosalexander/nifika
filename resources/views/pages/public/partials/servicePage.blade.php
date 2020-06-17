<?php

use App\Logic\Template\PageModelPage;
use App\PageImage;

/* @var $webPage PageModelPage */
$parentPage = $webPage->model()->parentPage;
$parentWebPage = $parentPage->getMyWebPage();
$parentIconImage = $parentWebPage->imageGalleryNthImage(PageImage::SECOND);
$pageIconImage = $webPage->imageGalleryNthImage(PageImage::SECOND);
$pageTextImage = $webPage->imageGalleryNthImage(PageImage::THIRD);
$color = ((!isset($color) || empty($color)) ? '#cccccc' : $color);
?>

@extends("{$layoutBasePath}.service")

@section('contentTop')
<div id="slider" style="margin-top:30px;">
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
        <?php if (!is_null($parentIconImage) && !is_null($parentIconImage->filePath())) : ?>
        <img style="float:left;top:-130px;" src="<?= e($parentIconImage->filePath()); ?>"/>
        <?php endif; ?>
    </div>
    <div id="services_main">
        <div id="top_services" style="border:1px solid <?= $color ?>;"><div id="services_img">
                <?php if (!is_null($pageIconImage) && !is_null($pageIconImage->filePath())) : ?>
                <img src="<?= e($pageIconImage->filePath()); ?>"/>
                <?php endif; ?>
            </div><div id="services_text">
                <h2 style="font-weight: 600;color:<?= $color ?>;"><?= e($webPage->title()) ?></h2>
                <hr style="height:7px; background-color:<?= $color ?>;width:80%;position: relative;float:left;margin: 0px;margin-top:5px;">
                <br>
                <p><?= e($parentWebPage->description()); ?></p>
            </div>
        </div>
        <div id="services_more">
            <h2 style="color:<?= $color ?>;">In Detail</h2>
            <hr style="height:3px;background-color: <?= $color ?>;">
            <div class="article_section rightImage">
                <div class="article_section_image">
                    <?php if (!is_null($pageTextImage) && !is_null($pageTextImage->filePath())) : ?>
                    <img style="" src="<?= e($pageTextImage->filePath()); ?>"/>
                    <?php endif; ?>
                </div>
                <div class="article_section_text">
                    <?= $webPage->content(); ?>
                </div>
                <div style="clear: both;"></div>
            </div>
            <hr style="height:3px;background-color: <?= $color ?>;">
        </div>
        <div style="clear:both;height:1px;" ></div>
    </div>
</div>
@endsection