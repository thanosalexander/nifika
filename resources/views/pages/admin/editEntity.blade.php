<?php

use App\Logic\App\EntityManager;
use App\Logic\Template\Breadcrumb;
use App\Page;
use App\Setting;
if(!empty($customContentView)){
    $contentView = $customContentView;
} else {
    $contentView = "{$viewBasePath}." . (!empty($relationEntityName) ? $relationEntityName : $entityName) . ".form";
}
$userTopLevelPage = Page::find(Page::userTopLevelPage(auth()->user()));
?>
@if(\View::exists($contentView))
    <?= view($contentView, [
    'pageData' => $pageData, 
    'entityName' => $entityName, 
    'model' => (!empty($model) ? $model : null),
    'relationEntityName' => (isset($relationEntityName) ? $relationEntityName : null),
    'parentModel' => (isset($parentModel) ? $parentModel : null),
    'user' => $user, 
    ]
    ) ?>
@endif

@extends("{$layoutBasePath}.default")

@section('contentTop')
<?php if(ss(Setting::SS_ADMIN_SHOW_BREADCRUMB)): ?>
<?php 
$breadcrumb = Breadcrumb::_get();
$breadcrumb->createAdminEntityBreadcrumb((!empty($relationEntityName) ? $relationEntityName : $entityName),
    (!empty($model)/* edit */ ? $model : /* create */(!empty($parentModel) ? $parentModel : null)),
    (!empty($model) ? trans($transBaseName. '.page.editPage') : trans($transBaseName. '.page.addPage'))
);
?>
<?= view($viewBasePath.'.includes.sections.breadcrumb', ['breadcrumb' => $breadcrumb]) ?>
<?php endif; ?>
@parent
@endSection

@section('bodyEnd')
@parent


<?= view($viewBasePath.'.partials.form.scripts.jsValidator', ['entityName' => (!empty($relationEntityName) ? $relationEntityName : $entityName)]) ?>
<script>
    $(document).ready(function () {
        $('.switchButton.switchButtonYesNo input[type="checkbox"]').bootstrapToggle({
            on: '<?= trans($transBaseName.'.form.switchButton.on.yes') ?>',
            off: '<?= trans($transBaseName.'.form.switchButton.off.no') ?>',
            onstyle: 'success',
            offstyle: 'danger'
        });
    });
    <?php if(!is_null($model)): ?>
    function checkEntityDelete() {
        if (confirm('<?= trans($transBaseName.'.deleteEntity.confirm') ?>')) {
            var $deleteEntityForm = $($(document).find("#deleteEntityForm").closest('form')[0]);
            $deleteEntityForm.trigger('submit');
            var data = new FormData();
            data.append('_method', 'DELETE');
            data.append('_token', '<?=csrf_token()?>');
            $.ajax({
                type: "POST",
                url: '<?= route($routeBaseName.'.entity.destroy', [$entityName, $model->id]); ?>',
                data: data,
                processData: false,
                contentType: false,
                success: function(result) {
                    NW.success('<?= trans($transBaseName.'.deleteEntity.success') ?>');
                    setTimeout(function(){
                        window.location =  '<?=route($routeBaseName.'.entity.list', [
                            $entityName,
                            (($entityName === EntityManager::PAGE && !is_null($model->parentPage))
                                ? ((!is_null($userTopLevelPage) && $model->parentPage->id === $userTopLevelPage->id)
                                    ? null
                                    : $model->parentPage->id
                                )
                                : null
                            ),
                            (($entityName === EntityManager::PAGE && !is_null($model->parentPage))
                                ? ((!is_null($userTopLevelPage) && $model->parentPage->id === $userTopLevelPage->id)
                                    ? null
                                    : $entityName
                                )
                                : null
                            ),
                            ]); ?>';
                    },500);
                },
                error: function(jqXHR, textStatus, errorThrown){
                    NW.fail('<?= trans($transBaseName.'.deleteEntity.fail') ?>');
                }
            });
        }
    }
    <?php endif; ?>
</script>
@endsection
