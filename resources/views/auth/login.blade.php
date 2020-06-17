@extends('layouts.admin.default')

@push('headStyles')
<style type="text/css">
    #wrapper {
        padding-left: 0 !important;
    }
</style>
@endpush

@section('content')
<div class="row">
        <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading"><?= trans('public.login.title') ?></div>
            <div class="panel-body">
                <?= Form::open(['route' => 'login.post', 'method' => "POST", 'role' => 'form', 'class' => 'form-horizontal', 'id' => 'loginForm']) ?>
                    {{ csrf_field() }}

                    <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                        <?= Form::label('username', trans('public.login.username'), ['class' => 'col-md-4 control-label']) ?>
                        <div class="col-md-6">
                            <?= Form::text('username', null, ['class' => 'form-control', 'id' => 'username', 'required' => '', 'autofocus' => '', 'placeholder' => trans('public.login.username')]) ?>
                            @if ($errors->has('username'))
                                <span class="help-block"><strong>{{ $errors->first('username') }}</strong></span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <?= Form::label('password', trans('public.login.password'), ['class' => 'col-md-4 control-label']) ?>
                        <div class="col-md-6">
                            <?= Form::password('password', ['class' => 'form-control', 'id' => 'password', 'required' => '', 'placeholder' => trans('public.login.password')]) ?>
                            @if ($errors->has('password'))
                                <span class="help-block"><strong>{{ $errors->first('password') }}</strong></span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <div class="checkbox">
                                <label>
                                    <?= Form::checkbox('remember', null).' '
                                        .trans('public.login.rememberMe') ?>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-8 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">
                                <?= trans('public.login.submit') ?>
                            </button>
                        </div>
                    </div>

                <?= Form::close(); ?>
            </div>
        </div>
    </div>
</div>

@endsection
