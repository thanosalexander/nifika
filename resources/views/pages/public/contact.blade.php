<?php

use App\Logic\Template\ContactPage;

/* @var $webPage ContactPage */
/* @var $errors Illuminate\Support\ViewErrorBag */
?>

@extends("{$layoutBasePath}.default")

@section('contentTop')
<div id="slider" style="height:582px;">
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
        <h2><?= e($webPage->title()) ?></h2>
        <p><?= e($webPage->description()) ?></p>
    </div>
    <div class="contact_page">
        <div class="contact_page_img" style="background-image: url('<?= e(asset($assetBasePath.'/images/about_img_contact.jpg')) ?>');">
            <p>Anytime at</p>
        </div>
        <div class="contact_page_text">
            <p>your Service</p>
            <div style="padding-left:30px;">
                <form class="needs-validation <?= e($errors->any() ? 'was-validated' : '') ?>" action="<?= route("{$routeBaseName}.contact.send") ?>" method="post" >
                    <?php if(Session::has('message')): ?>
                    <?php $messageType = (Session::get('status') === 'fail' ? 'alert-warning': 'alert-success'); ?>
                    <div class="form-group">
                        <div class="alert <?= $messageType ?> alert-dismissible fade show" role="alert">
                            <?= e(Session::get('message')); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="form-group <?= e( $errors->has('name') ? 'has-error' : ''  ) ?>">
                        <input type="text" name="name" placeholder="Name..." class="form-control" required="" value="<?= (old('name') ? old('name') : '') ?>"/>
                        @if ($errors->has('name'))
                            <span class="invalid-feedback"><?= e( $errors->first('name')  ) ?></span>
                        @endif
                    </div>
                    <div class="form-group <?= e( $errors->has('subject') ? 'has-error' : ''  ) ?>">
                        <input type="text" name="subject" placeholder="Subject..." class="form-control" value="<?= (old('subject') ? old('subject') : '') ?>"/>
                        @if ($errors->has('subject'))
                            <span class="invalid-feedback"><?= e( $errors->first('subject')  ) ?></span>
                        @endif
                    </div>
                    <div class="form-group <?= e( $errors->has('email') ? 'has-error' : ''  ) ?>">
                        <input type="email" name="email" placeholder="Email..." class="form-control" required="" value="<?= (old('email') ? old('email') : '') ?>"/>
                        @if ($errors->has('email'))
                            <span class="invalid-feedback"><?= e( $errors->first('email')  ) ?></span>
                        @endif
                    </div>
                    <div class="form-group <?= e( $errors->has('message') ? 'has-error' : ''  ) ?>">
                        <textarea name="message" placeholder="Message..." class="form-control" rows="5" required=""><?= (old('message') ? old('message') : '') ?></textarea>
                        @if ($errors->has('message'))
                            <span class="invalid-feedback"><?= e( $errors->first('message')  ) ?></span>
                        @endif
                    </div>
                    <input class="form-input" type="hidden" name="_token" value="<?= csrf_token(); ?>">
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-lg btn-secondary">SEND</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection