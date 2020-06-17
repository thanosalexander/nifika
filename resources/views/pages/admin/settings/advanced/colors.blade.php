
<div class="row">
<?php 
$sections = \App\Logic\Template\Theme::themeSections();

foreach($sections as $sectionName => $colors){ ?>
    <div class="col-xs-12">
        <div class="row">
            <div class="col-xs-12">
                <h4><strong><?=$sectionName?></strong></h4>
            </div>
        </div>
        <div class="row">   
<?php foreach($colors as $colorSetting){ ?>
            <div class="col-xs-12 col-sm-4 marginBottom15">
            <?=\App\Logic\Settings\SettingsAdministration::getSettingField(
                $colorSetting, e(trans($transBaseName.'.settings.advanced.theme.'.$colorSetting)) );?>
            </div>
<?php } ?>
        </div>
    </div>
<?php 
} ?>
</div>