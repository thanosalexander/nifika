<?php

use App\Logic\Template\PageModelPage;

/* @var $webPage PageModelPage */
$parentPage = $webPage->model()->parentPage;
$parentWebPage = $parentPage->getMyWebPage();
?>

@extends("{$layoutBasePath}.project")

@section('contentTop')

@endsection

@section('content')
<div class="wrapper">
    <div id="container">
    </div>
    <div id="preview-text">
        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <?php if ($webPage->hasImageGallery()): ?>
                <?php $galleryImages = $webPage->imageGallery(); ?>
                <?php $index = 0; ?>
                <?php foreach ($galleryImages as $galleryImage): /* @var $galleryImage PageImage */ ?>
                <div class="carousel-item <?= ($index++ === 0 ? 'active' : '' ) ?>">
                    <img class="d-block w-100" src="<?= e($galleryImage->filePath()) ?>"/>
                    <p></p>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <div class="carousel-item active">
                    <?php if ($webPage->hasImage()): ?>
                    <img class="d-block w-100" src="<?= e($webPage->image()); ?>"/>
                    <!--<img class="" src="http://pilos.com.cy/projects/wp-content/uploads/2019/05/005-grafeia06.jpg" alt="">-->
                    <?php endif; ?>
                    <p></p>
                </div>
                <?php endif; ?>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
        <article id="post-84" class="post-84 post type-post status-publish format-standard has-post-thumbnail hentry category-1">
            <header class="entry-header">
                <h1 class="entry-title"><?= e($webPage->title()) ?></h1> </header><!-- .entry-header -->
            <div class="entry-content">
                <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                    </ol>
                    <div class="carousel-inner">
                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
                <br>
                <p><?= e($webPage->description()) ?></p>
                <p></p>
                <?= $webPage->content(); ?>
            </div><!-- .entry-content -->
        </article><!-- #post-84 -->
        <hr style="width:40%;background-color:#2d3d54;height:2px;position: relative;float:left;">
        <br> <br>
        <div class="back" style="margin: 0">
            <a href="<?= e($parentWebPage->url()) ?>"> BACK</a>
        </div>
        <br>
    </div>
</div>
@endsection