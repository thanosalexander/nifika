@extends('layouts.error')

@section('title') 404 <?= trans('public.error.404') ?> @endsection

@section('content')
    <div class="content">
        <div class="title">404 <?= trans('public.error.404') ?></div>
        <?php if(Route::has('public.home')): ?>
        <a href="<?=route('public.home')?>"><?= trans('public.error.backToHome') ?></a>
        <?php endif; ?>
    </div>
@endsection