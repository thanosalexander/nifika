<?php

use App\Logic\Settings\SettingsAdministration;
use App\Setting;

?>
@section('headScripts')
@parent
<script type="text/javascript" src="<?= asset($assetBasePath.'/js/fileFieldManagerScript.js')?>"></script>
<script type="text/javascript" src="<?=asset($assetBasePath.'/assets/spectrum/spectrum.1.8.0.min.js')?>"></script>
@endsection

@section('headStyles')
@parent
<link rel="stylesheet" href="<?=asset($assetBasePath.'/assets/spectrum/spectrum.1.8.0.min.css')?>" />
@endsection

@section('content')
<div id="settingPage">
    <div id="headerRow" class="row">
        <div class="col-sm-12">
            <h3 id="headerPageTitle" class=""><?= $pageData['pageTitle']?></h3>
        </div>
    </div>
    <br>

    <?php $formDefaultAttributes = ['class' => 'hasJsValidator', 'files' => true]; ?>
    <?= Form::open(array_merge(['route' => [$routeBaseName.'.settings.update', $settingGroup], 'method' => 'POST'], $formDefaultAttributes)); ?>
        
        @include($viewBasePath.'.partials.form.create.message')

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?= e(trans($transBaseName.'.settings.advanced.seoAndSocial')) ?></h3>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <?= SettingsAdministration::getSettingField(
                            Setting::SS_SEO_SITENAME,
                            e(trans($transBaseName.'.settings.advanced.siteName')));?>
                </div>
                <div class="form-group">
                    <?= SettingsAdministration::getSettingField(
                            Setting::SS_GOOGLE_ANALYTICS_ID,
                            e(trans($transBaseName.'.settings.advanced.googleAnalytics')));?>
                </div>
                <div class="form-group">
                    <?= SettingsAdministration::getSettingField(
                            Setting::SS_FACEBOOK_PAGE_PLUGIN_CODE,
                            e(trans($transBaseName.'.settings.advanced.facebookLikeBox')));?>
                </div>
                <div class="form-group">
                    <?= SettingsAdministration::getSettingField(
                            Setting::SS_FACEBOOK_PAGE_URL,
                            e(trans($transBaseName.'.settings.advanced.facebookPageUrl')));?>
                </div>
                <div class="form-group">
                    <?= SettingsAdministration::getSettingField(
                            Setting::SS_GOOGLE_PLUS_PAGE_URL,
                            e(trans($transBaseName.'.settings.advanced.googlePlusPageUrl')));?>
                </div>
                <div class="form-group">
                    <?= SettingsAdministration::getSettingField(
                            Setting::SS_TWITTER_PAGE_URL,
                            e(trans($transBaseName.'.settings.advanced.twitterPageUrl')));?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 text-right">
                <?= Form::button(trans($transBaseName.'.form.save'), [
                    'class' => 'btn btn-primary save showAsActionBarButton minimize',
                    'type' => 'submit', 
                    ])?>
            </div>
        </div>
    <?= Form::close(); ?>
</div>
@endsection


@section('bodyEnd')
@parent
@include($viewBasePath.'.partials.form.scripts.jsValidator', ['entityName' => $settingGroup])
@include($viewBasePath.'.settings.partials.scripts')
<script>
$(document).ready(function () {
    FileFieldManager.init({
            rootSelector: '#fileFields',
            recordRowSelector: '.fileFieldWrapper',
            validateElement: function(){}
    });
});
</script>
@endsection
