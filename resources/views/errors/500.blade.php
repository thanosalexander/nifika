<?php //this will only work when abort is used // ?>

@extends('layouts.error')

@section('title') 500 <?= trans('public.error.500') ?> @endsection

@section('content')
    <div class="content">
        <div class="title">500 <?= trans('public.error.500') ?></div>
        <?php if(Route::has('public.home')): ?>
        <a href="<?=route('public.home')?>"><?= trans('public.error.backToHome') ?></a>
        <?php endif; ?>
    </div>
@endsection