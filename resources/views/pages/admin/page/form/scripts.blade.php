<?php

/* @var $menuablePageTypes array */

?>
@section('headStyles')
@parent
@endsection

@section('headScripts')
@parent
<script src="<?= asset($assetBasePath . '/assets/ckeditor/ckeditor.js') ?>"></script>
<script src="<?= asset($assetBasePath . '/js/ckeditor.init.js') ?>"></script>
<script type="text/javascript" src="<?= asset($assetBasePath.'/js/fileFieldManagerScript.js')?>"></script>

@endsection

@section('bodyEnd')
@parent
<script>
    $(document).ready(function () {
        var onMainMenuSelector = '.switchButton input[name="displayOnMainMenu"][type="checkbox"]';
        var pageTypeSelector = '[name="type"]';
        var menuableTypes = <?= json_encode($menuablePageTypes) ?>;
        var rootSelector = '#mainImageContainer'; //find root element for this field
        var recordRowSelector = '.mainImageRow';
        var $myform = $($(document).find(rootSelector).closest('form')[0]);

        var validateElement = function ($element) {
            if ($element.length > 0 && $myform.data('validator') !== undefined) {
                $element.valid();
            }
        };

        var mainImage = new FileFieldManager();
        mainImage.init({
            rootSelector: rootSelector,
            recordRowSelector: recordRowSelector,
            validateElement: validateElement
        });

        /* Listen onlyInGroup button and on change show/hide group-field area */
        $(document).on('change', pageTypeSelector, function () {
            var selectedValue = $(this).val();
            if ($.inArray(parseInt(selectedValue), menuableTypes) > -1) {
                $(onMainMenuSelector).bootstrapToggle('enable');
            } else {
                $(onMainMenuSelector).bootstrapToggle('off');
                $(onMainMenuSelector).bootstrapToggle('disable');
            }
        });
    });
</script>
@endsection