<?php

use App\Logic\Locales\AppLocales;
use App\Logic\Template\Menu\UserMenu;
?>

<?php if(auth()->check()): ?>
@section('headScripts')
@parent
<?= view($viewBasePath.'.includes.scripts.headerActionBar') ?>
@endsection

@section('bodyEnd')
@parent
<script>
    $(document).ready(function () {
        $(document).on('change', '#modelLocaleList', function(){
            updateModelLocale(this.value, this);
        });
    });

    function updateModelLocale(lang, element) {
        if(!$){ return;}
        var data = new FormData();
        var urlPattern = "<?= route($routeBaseName.'.modelLocale.update') ?>";
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
<?php endif; ?>

@section('headerMenu')
<div id="actionButtonBarContainer">
    <?php if(auth()->check()): ?>
    <div id="actionButtonBar" class="text-right">
        <?php if(count(AppLocales::getFrontend(true)) > 1): ?>
        <div id="modelLocaleListWrapper">
            <label for="modelLocaleList" class="hidden-xs hidden-sm"><?=trans($transBaseName.'.modelLocaleLanguage')?>: </label>
            <?=Form::select('modelLocale', AppLocales::getFrontend(true), currentModelLocale(), [
                'id' => 'modelLocaleList', 'class' => 'actionButtonBar form-control',
                'autocomplete' => 'off'
            ]) ?>
        </div><?php
            endif;
  ?></div>
    <?php endif; ?>
</div>

<!-- Navigation -->
<nav id="headerMenu" class="navbar navbar-default navbar-fixed-top" role="navigation">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Menu</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="<?= route($routeBaseName.'.home') ?>">
            <strong><?= trans($transBaseName.'.panelName') ?></strong>
        </a>
    </div>
    <!-- Top Menu Items -->
    <?php if(auth()->check()): ?>

    <ul class="nav navbar-right top-nav">
        <li id="userMenu"><a href="#"><i class="fa fa-user"></i> <?= auth()->user()->name; ?></a></li>
    </ul>
    <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
    <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav side-nav">
            <?php foreach(UserMenu::getMainMenu(auth()->user(), app('request')) as $menuIndex => $menuItem): ?>
            <?php $hasChildren = (array_key_exists('children', $menuItem) && is_array($menuItem['children']) && count($menuItem['children']) > 0); ?>
            <li class="<?= $menuItem['active'] ? 'active' : '' ?>">
                <a href="<?= $hasChildren ? 'javascript:;' : $menuItem['url'] ?>"
                    data-toggle="<?= $hasChildren ? 'collapse' : '' ?>"
                    data-target="<?= $hasChildren ? "#userSubMenu{$menuIndex}" : '' ?>"
                    target="<?= $menuItem['targetWindow'] ?>"
                >
                    <?php if($menuItem['fontAwesomeIconTag']): ?>
                    <i class="fa <?= $menuItem['fontAwesomeIconTag']; ?>"></i>
                    <?php endif; ?>
                    <?= $menuItem['name'] ?>
                    <?php if($hasChildren): ?>
                    <i class="fa fa-fw fa-caret-down"></i>
                    <?php endif; ?>
                </a>
                <?php if($hasChildren): ?>
                <ul id="<?= "userSubMenu{$menuIndex}" ?>" class="collapse <?= $menuItem['active'] ? 'in' : '' ?>">
                    <?php foreach($menuItem['children'] as $subMenuIndex => $subMenuItem): ?>
                    <li class="<?= ($subMenuItem['active'] ? 'active' : '') ?>">
                        <a href="<?= $subMenuItem['url'] ?>" target="<?= $subMenuItem['targetWindow'] ?>">
                            <?php if($subMenuItem['fontAwesomeIconTag']): ?>
                            <i class="fa <?= $subMenuItem['fontAwesomeIconTag']; ?>"></i>
                            <?php endif; ?>
                            <?= $subMenuItem['name'] ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </li>

            <?php endforeach; ?>
        </ul>
    </div>
    <!-- /.navbar-collapse -->
    <?php endif; ?>
</nav>
@endsection
