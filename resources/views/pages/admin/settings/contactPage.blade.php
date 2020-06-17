<?php

use App\Logic\Settings\SettingsAdministration;
use App\Setting;

?>
@section('content')
    <div id="settingPage">
        <div id="headerRow" class="row">
            <div class="col-sm-12">
                <h3 id="headerPageTitle" class=""><?= $pageData['pageTitle']?></h3>
            </div>
        </div>
        <br>

        <?php $formDefaultAttributes = ['class' => 'hasJsValidator']; ?>
        <?= Form::open(array_merge(['route' => [$routeBaseName.'.settings.update', $settingGroup], 'method' => 'POST'], $formDefaultAttributes)); ?>

            @include($viewBasePath.'.partials.form.create.message')
            <div class="col-lg-8 col-md-10 nopaddingLeft">
                <div class="row marginBottom15">
                    <div class="col-sm-6 form-check switchButton switchButtonYesNo">
                        <?= SettingsAdministration::getSettingField(
                                Setting::SS_CONTACT_PAGE_ENABLED,
                                e(trans($transBaseName.'.form.enabledShe')));?>
                    </div>
                </div>
                <div class="form-group">
                    <?= SettingsAdministration::getSettingField(
                            Setting::SS_CONTACT_PAGE_ADDRESS,
                            e(trans($transBaseName.'.settings.contactPage.contactAddress')));?>
                </div>
                <div class="form-group">
                    <?= SettingsAdministration::getSettingField(
                            Setting::SS_CONTACT_PAGE_PHONE,
                            e(trans($transBaseName.'.settings.contactPage.contactPhone')));?>
                </div>
                <div class="form-group">
                    <?= SettingsAdministration::getSettingField(
                            Setting::SS_CONTACT_PAGE_FAX,
                            e(trans($transBaseName.'.settings.contactPage.contactFax')));?>
                </div>
                <div class="form-group">
                    <?= SettingsAdministration::getSettingField(
                            Setting::SS_CONTACT_PAGE_RECEIPT_EMAIL,
                            e(trans($transBaseName.'.settings.contactPage.receiptEmail')));?>
                </div>
            </div>
            <div class="col-lg-8 col-md-10 nopaddingLeft">
                <div class="form-group">
                    <?= SettingsAdministration::getSettingField(
                            Setting::SS_GOOGLE_MAP_API_KEY,
                            e(trans($transBaseName.'.settings.contactPage.googleMapApiKey')));?>
                </div>
                <div class="form-group">
                    <?= SettingsAdministration::getSettingField(
                             Setting::SS_GOOGLE_MAP_LAT,
                            e(trans($transBaseName.'.settings.contactPage.googleMapLat')));?>
                </div>
                <div class="form-group">
                    <?= SettingsAdministration::getSettingField(
                             Setting::SS_GOOGLE_MAP_LNG,
                            e(trans($transBaseName.'.settings.contactPage.googleMapLng')));?>
                </div>
            </div>

            <?= Form::button(trans($transBaseName.'.form.save'), [
                'class' => 'btn btn-primary save showAsActionBarButton minimize',
                'type' => 'submit', 
                ])?>

        <?= Form::close(); ?>
    </div>
@endsection

@section('bodyEnd')
@parent
@include($viewBasePath.'.partials.form.scripts.jsValidator', ['entityName' => $settingGroup])
@include($viewBasePath.'.settings.partials.scripts')
@endsection