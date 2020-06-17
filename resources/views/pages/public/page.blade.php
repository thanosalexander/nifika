<?php

use App\Logic\Template\ContactPage;
use App\Logic\Template\PageModelPage;
use App\Logic\Template\StartPage;
use Carbon\Carbon;

/* @var $webPage PageModelPage|StartPage|ContactPage */
$page = $webPage->model();
?>
@extends("{$layoutBasePath}.default")

@section('content')
<section class="section section-xs">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 offset-xl-2 col-lg-8 offset-lg-2">
                <!--<h3><?= e($webPage->title()); ?></h3>-->
                <p>
                    <?php if ($webPage->hasMedia()) : ?>
                    <?php if ($webPage->hasVideo()): ?>
                    <embed width="420" height="315" src="<?= e($webPage->video()) ?>">
                    <?php elseif ($webPage->hasImage()): ?>
                    <img class="image-responsive" alt="" src="<?= e($webPage->image()); ?>" />
                    <?php else: ?>
                    <?php endif; ?>
                    <?php endif; ?>
                </p>
                <?= $webPage->content(); ?>
            </div>
        </div>
    </div>
</section>

@endsection