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

        <?php $formDefaultAttributes = ['class' => 'hasJsValidator', 'files' => true]; ?>
        <?= Form::open(array_merge(['route' => [$routeBaseName.'.settings.update', $settingGroup], 'method' => 'POST'], $formDefaultAttributes)); ?>

        @include($viewBasePath.'.partials.form.create.message')
        
        
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?= e(trans($transBaseName.'.settings.startPage.home.title')) ?></h3>
            </div>
            <div class="panel-body">
                
            </div>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?= e(trans($transBaseName.'.settings.startPage.sidebar.title')) ?></h3>
            </div>
            <div class="panel-body">
                
            </div>
        </div>
        
        <div class="col-lg-8 col-md-10 nopaddingLeft">

            <div class="row">
                <div class="col-xs-12 text-right">
                    <?= Form::button(trans($transBaseName.'.form.save'), [
                        'class' => 'btn btn-primary save showAsActionBarButton minimize',
                        'type' => 'submit', 
                        ])?>
                </div>
            </div>
        </div>

        <?php echo Form::close(); ?>
    </div>
@endsection


@section('bodyEnd')
@parent
@include($viewBasePath.'.partials.form.scripts.jsValidator', ['entityName' => $settingGroup])
@include($viewBasePath.'.settings.partials.scripts')
@endsection
