<?php

/* @var $articles Collection */

use App\Logic\App\Assets;
use App\Logic\Template\ContactPage;
use App\Logic\Template\PageModelPage;
use App\Logic\Template\StartPage;
use App\Page;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

/* @var $webPage PageModelPage|StartPage|ContactPage */
$page = $webPage->model();

?>
@extends("{$layoutBasePath}.default")

@section('contentTop')
@endsection

@section('content')
<section class="section section-xs">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 offset-xl-2 col-lg-8 offset-lg-2">
                <?php foreach ($articles as $article) : /* @var $article Page */ ?>
                <?php $articleWebPage = PageModelPage::get($article); ?>
                <div class="row row-50">
                    <div class="col-sm-3">
                        <?php //if ($articleWebPage->hasMedia()) : ?>
                        <?php if ($articleWebPage->hasVideo()): ?>
                        <embed width="420" height="315" src="<?= e($articleWebPage->video())  ?>">
                        <?php elseif ($articleWebPage->hasImage()): ?>
                        <img class="image-responsive" alt="" src="<?= e($articleWebPage->image());  ?>" />
                        <?php else: ?>
                        <img class="image-responsive" alt="" src="<?= e(Assets::noImageUrl());  ?>" />
                        <?php endif; ?>
                        <?php //endif; ?>
                    </div>
                    <div class="col-sm-9">
                        <!--<div class="inset-1">-->
                            <h3><a href="<?= e($articleWebPage->url());  ?>"><?= e($articleWebPage->title());  ?></a></h3>
                            <time datetime="<?= e($article->created_at)  ?>" class="date-1"><?= e(Carbon::parse($article->created_at)->formatLocalized('%b %e, %G'));  ?></time>
                            <p class="big"><?= e($articleWebPage->description());  ?></p>
                        <!--</div>-->
                    </div>
                </div>
                <?php endforeach; ?>
                <div class="row">
                    <div class="col-lg-12">
                        <?= e($articles->links("vendor.pagination.bootstrap-4"));  ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection