<?php


use \App\Logic\Locales\AppLocales;
use \App\Logic\Settings\SettingsAdministration;
use \App\Logic\Template\Menu\PageMenu;
use \App\Logic\Template\Menu\Menus;
use \App\Setting;
?>
@section('headScripts')
@parent
<script>

    $(document).ready(function () {
        $(document).on('change', '#adminLocaleList', function(){
            updateAdminLocale(this.value, this);
        });
        
        $(document).find("#publicMenu ul").each(function(){
            //init record rows sort handler
            $(this).sortable({
                axis: "y",
                handle: 'i.glyphicon-move',
                placeholder: "ui-state-highlight",
                cancel: ".ui-state-disabled"
            });
        });
        
        $(document).on('click', '.removeMe', function(){
            var $item = $(this).closest('li');
            if($item.hasClass('ui-state-disabled')){
                //enable it
                $item.removeClass('ui-state-disabled');
                $item.find('.activeMenuInput').prop('disabled', false);
                $item.find('.deletedMenuInput').prop('disabled', true);
            } else {
                //disable it
                $item.addClass('ui-state-disabled');
                $item.find('.activeMenuInput').prop('disabled', true);
                $item.find('.deletedMenuInput').prop('disabled', false);
            }
        });
    });
    
    function updateAdminLocale(lang, element) {
        if(!$){ return;}
        var data = new FormData();
        var urlPattern = "<?= route($routeBaseName.'.adminLocale.update') ?>";
        data.append('_token', '<?=csrf_token()?>');
        data.append('lang', lang);
        $.ajax({
            type: "POST",
            url: urlPattern,
            data: data,
            processData: false,
            contentType: false,
            beforeSend: function(result) {
            },
            success: function(result) {
            },
            error: function(jqXHR, textStatus, errorThrown){
            },
            complete: function(){
                location.reload();
            }
        });
    }
    
</script>
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
    
    <?= Form::button(trans($transBaseName.'.form.save'), [
        'class' => 'btn btn-primary save showAsActionBarButton minimize',
        'type' => 'submit', 
        ])?>
    
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><?= trans($transBaseName.'.settings.general.adminpanel.title') ?></h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12">
                    <div id="publicMenu">
                        <?= view($viewBasePath.'.settings.partials.menu', [
                            'parentItemId' => null,
                            'items' => PageMenu::initMenu(Menus::MENU_ID_MAIN)->getTreeItems()
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
        
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
            <!--
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
            -->
        </div>
    </div>
        
    <?php echo Form::close(); ?>

    <?php if(count(AppLocales::getBackend(true)) > 1): ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><?= trans($transBaseName.'.settings.general.adminpanel.title') ?></h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12">
                    <?= Form::label(trans($transBaseName.'.settings.general.adminpanel.managerLanguage').':'); ?>
                    <?= Form::select('adminLocale', AppLocales::getBackend(true), currentAdminLocale(), [
                        'id' => 'adminLocaleList', 'class' => 'actionButtonBar form-control',
                        'autocomplete' => 'off',
                        'title' => trans($transBaseName.'.settings.general.adminpanel.managerLanguage')
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

@endsection

@section('bodyEnd')
@parent
@include($viewBasePath.'.partials.form.scripts.jsValidator', ['entityName' => $settingGroup])
@include($viewBasePath.'.settings.partials.scripts')
@endsection