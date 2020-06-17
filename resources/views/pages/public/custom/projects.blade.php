<?php

use App\Logic\Pages\PageOrderType;
use App\Logic\Template\PageModelPage;
use App\Page;
use Illuminate\Database\Eloquent\Collection;
/* @var $webPage PageModelPage */
/* @var $subPages Collection */
$subPages = $webPage->model()->subPages()
        ->frontEndVisible()
        ->subPagesSort(
        PageOrderType::column($webPage->model()->sortType),
        PageOrderType::direction($webPage->model()->sortType)
)->get();

$firstSubPage = $subPages->shift();
$firstSubWebPage = $firstSubPage->getMyWebPage();
?>

@extends("{$layoutBasePath}.project")


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
    <div class="wrapper">
        <div id="moreinfo">
            <!--<img src="http://pilos.com.cy/projects/wp-content/themes/pilos/images/custom.png"/>-->
            <img src="<?= e(asset($assetBasePath.'/images/custom.png')) ?>"/>
            <h2><?= e($webPage->title()); ?></h2>
            <p><?= e($webPage->description()); ?></p>
        </div>
        <div id="featuredProject">
            <div class="leftTopCorner"><img src="<?= e(asset($assetBasePath.'/images/custom_2.png')) ?>"/></div>
            <div class="featuredProjectContent">
                <div class="featuredProjectContentImage">
                    <?php if ($firstSubWebPage->hasImage()) : ?>
                    <img class="" src="<?= e($firstSubWebPage->image()); ?>" alt="" />
                    <?php endif; ?>
                </div>
                <div class="featuredProjectContentInfo">
                    <h2><?= e($firstSubWebPage->title()); ?></h2>
                    <hr>
                    <p> </p>
                    <p><?= e($firstSubWebPage->description()); ?></p>
                    <div class="back" style="margin: 0">
                        <a href="<?= e($firstSubWebPage->url()); ?>"> More </a>
                    </div>
                </div>
                <div style="clear: both;"></div>
            </div>
            <div class="rightBottomCorner"><img src="<?= e(asset($assetBasePath.'/images/custom.png')) ?>"/></div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <?php foreach ($subPages as $subPage) : /* @var $subPage Page */ ?>
            <?php $subWebPage = PageModelPage::get($subPage);?>
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:50px;">
                <div class="project-box">
                    <?php if ($subWebPage->hasImage()) : ?>
                    <img src="<?= e($subWebPage->image()); ?>" class="" alt="" />
                    <?php endif; ?>
                    <h4><a href="<?= e($subWebPage->url()); ?>"><?= e($subWebPage->title()); ?></a></h4>
                    <hr>
                    <p></p>
                    <p><?= e($subWebPage->description()); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<!--<div class="back"><div id="nav" style=""><a href="http://pilos.com.cy/projects/page/2/" >Load more</a></div></div>-->
@endsection

@section('beforeBodyEnd')
@parent
<script>
    $(window).scroll(function () {
        sessionStorage.scrollTop = $(this).scrollTop();
    });

    $(document).ready(function(){
        if (sessionStorage.scrollTop != "undefined") {
            $(window).scrollTop(sessionStorage.scrollTop);
        }
    });
</script>
@endsection
