<?php

use App\Logic\Template\Breadcrumb;
use App\Setting;

if(!empty($customContentView)){
    $contentView = $customContentView;
} else {
    $contentView = "{$viewBasePath}." 
    . (!empty($relationEntityName) ? $relationEntityName : $entityName)
    . ".list";
}
?>
<?php if(View::exists($contentView)): ?>
    <?= view($contentView, [
    'pageData' => $pageData,
    'entityName' => $entityName,
    'relationEntityName' => $relationEntityName,
    'parentModel' => $parentModel,
    'user' => $user, 
    ]) ?>
<?php endif; ?>

@extends("{$layoutBasePath}.default")

@section('contentTop')
<?php if(ss(Setting::SS_ADMIN_SHOW_BREADCRUMB)): ?>
<?php 
$breadcrumb = Breadcrumb::_get();
$breadcrumb->createAdminEntityBreadcrumb($entityName, $parentModel);
?>
<?= view($viewBasePath.'.includes.sections.breadcrumb', ['breadcrumb' => $breadcrumb]) ?>
<?php endif; ?>
@parent
@endSection

@section('content')
@parent
<?= view($viewBasePath.'.partials.form.create.message') ?>
@append

@section('headStyles')
@parent
<link rel="stylesheet" href="<?=e(asset($assetBasePath.'/assets/datatables/css/dataTables.bootstrap.min.css'))?>">
@endsection

@section('bodyEnd')
@parent
<!--  DataTables.net Plugin, full documentation here: https://datatables.net/    -->
<script src="<?=e(asset($assetBasePath.'/assets/datatables/js/jquery.dataTables.min.js'))?>"></script>
<script src="<?=e(asset($assetBasePath.'/assets/datatables/js/dataTables.bootstrap.min.js'))?>"></script>
<script>
    function checkDelete(id, confirmMessage, successMessage, failMessage, successCallback ) {
        if(confirmMessage == null){
            confirmMessage = '<?= trans($transBaseName.'.deleteEntity.confirm') ?>';
        }
        if(successMessage == null){
            successMessage = '<?= trans($transBaseName.'.deleteEntity.success') ?>';
        }
        if(failMessage == null){
            failMessage = '<?= trans($transBaseName.'.deleteEntity.fail') ?>';
        }
        if ( confirm(confirmMessage) ) {
            $('.deleteEntityAlert').addClass('hidden');
            var data = new FormData();
            var urlPattern = "<?= url(str_replace('{entity}', (!is_null($relationEntityName) ? $relationEntityName: $entityName), Route::getRoutes()->getByName($routeBaseName.'.entity.destroy')->uri()))?>";
            data.append('_method', 'DELETE');
            data.append('_token', '<?=csrf_token()?>');
            $.ajax({
                type: "POST",
                url: urlPattern.replace('{id}', id),
                data: data,
                processData: false,
                contentType: false,
                success: function(result) {
                    try{
                        NW.success(successMessage);
                        $('#entityRow' + id).remove();
                        if(successCallback){
                            successCallback();
                        }
                    }catch(e){
                        NW.fail(failMessage);
                    }
                },
                error: function(){
                    NW.fail(failMessage);
                }
            });
        }
    }
    
</script>
@endsection