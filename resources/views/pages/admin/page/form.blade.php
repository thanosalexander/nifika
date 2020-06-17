<?php

use App\Logic\App\EntityManager;
use App\Logic\App\Permission;
use App\Logic\Pages\PageOrderType;
use App\Logic\Pages\PageType;
use App\Logic\Pages\PageView;
use App\Page;
use App\Setting;

$pageTypes = PageType::creatablePageTypes(true);
$pageOrderTypes = PageOrderType::allPageOrderTypes(true);
$menuablePageTypes = PageType::menuablePageTypes();
$uniquePageTypes = PageType::uniquePageTypes();
$pageViews = collect(PageView::allPageCustomViews(true))->prepend('Default', '')->toArray();
$existsMenuablePageTypes = false;
$canUserMangeMenu = Permission::canMangeMenu($user);
foreach($pageTypes as $pageType => $pageTypeLabel) {
    if(!$existsMenuablePageTypes){
        $existsMenuablePageTypes = in_array($pageType, $menuablePageTypes);
    }
}
if(is_null($model)){
    $canBeAssignedOnMenu = ($existsMenuablePageTypes);
} else {
    $canBeAssignedOnMenu = ($existsMenuablePageTypes || in_array($model->type, $menuablePageTypes));
}

?>

@section('contentTop')
@parent
<div class="row nomarginHorizontal">
    <div class="col-lg-12 col-md-12 nopaddingLeft">
        <h4><?= e($pageData['pageTitle']) ?><?= (!empty($model) ? ': ' . e($model->getMyName()) : '') ?></h4>
    </div>
</div>
@endsection

@section('content')
@parent

<?php $formDefaultAttributes = ['class' => 'hasJsValidator', 'files' => true]; ?>
<?php if (!empty($model)): ?>
    <?= Form::model($model, array_merge(['route' => [$routeBaseName.'.entity.update', $entityName, $model->id], 'method' => 'PUT'], $formDefaultAttributes)); ?>
<?php else: ?>
    <?= Form::open(array_merge([
        'route' => [$routeBaseName.'.entity.store', $entityName, (is_null($parentModel) ? null: $parentModel->id), $relationEntityName], 'method' => 'POST'], $formDefaultAttributes)); ?>
<?php endif; ?>

<div class="row nomarginHorizontal">
    <div class="col-lg-12 col-md-12 nopaddingLeft">
        <?= view($viewBasePath.'.partials.form.create.message') ?>
        
        <div class="row marginBottom15">
            <div class="col-xs-12 col-sm-4 form-check switchButton switchButtonYesNo">
                <?= view($viewBasePath.'.partials.form.create.checkbox', [
                    'name' => 'enabled', 
                    'label' => trans($transBaseName.'.form.enabledShe'),
                    'checkedValue' => Page::ENABLED_YES,
                    'labelWrap' => '',
                ]) ?>
            </div>
            <?php if($canUserMangeMenu && $canBeAssignedOnMenu): ?>
            <div class="col-xs-12 col-sm-8 form-check switchButton switchButtonYesNo">
                <?= view($viewBasePath.'.partials.form.create.checkbox', [
                    'name' => 'displayOnMainMenu',
                    'label' => trans($transBaseName.'.page.field.displayOnMainMenu'),
                    'checkedValue' => Page::ENABLED_YES,
                    'labelWrap' => '',
                    'isChecked' => (!is_null($model) && !is_null($model->mainMenuItem)),
                    'isDisabled' => (!is_null($model) && !$model->canBeAssignedOnMenu())
                ]) ?>
            </div>
            <?php endif; ?>
        </div>
        <div class="row marginBottom15">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <?= view("{$viewBasePath}.page.form.field.type", [
                                'entityName' => EntityManager::PAGE,
                                'model' => $model,
                                'pageTypes' => $pageTypes,
                                'label' => trans($transBaseName.'.page.field.type')
                            ]); ?>
                        </div>
                    </div>
                    <?php if($user->isAdmin()): ?>
                    <div class="col-lg-3">
                        <?php if(count($pageViews) > 0): ?>
                        <div class="form-group">
                            <?= view("{$viewBasePath}.page.form.field.customView", [
                                'entityName' => EntityManager::PAGE,
                                'model' => $model,
                                'pageViews' => $pageViews,
                                'label' => trans($transBaseName.'.page.field.customView')
                            ]); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-lg-3">
                        <?php if(count($pageOrderTypes) > 0): ?>
                        <div class="form-group">
                            <?= view("{$viewBasePath}.page.form.field.sortType", [
                                'entityName' => EntityManager::PAGE,
                                'model' => $model,
                                'pageOrderTypes' => $pageOrderTypes,
                                'label' => trans($transBaseName.'.page.field.sortType')
                            ]); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <?= view($viewBasePath.'.partials.form.create.input', [
                        'name' => 'title',
                        'label' => trans($transBaseName.'.page.field.title'),
                        'type' => 'text',
                        'required' => ''
                    ]) ?>
                </div>
                <div class="form-group">
                    <?= view($viewBasePath.'.partials.form.create.textarea', [
                        'name' => 'description',
                        'label' => trans($transBaseName.'.page.field.description'),
                    ]) ?>
                </div>
            </div>
        </div>
        <div class="row marginBottom15">
            <div class="col-md-4">
                <?= Form::label(trans($transBaseName.'.page.field.image')); ?>
                <div class="form-group panel panel-default">
                    <div id="mainImageContainer" class="panel-heading">
                        <?= view("{$viewBasePath}.page.form.field.image", [
                            'entityName' => EntityManager::PAGE,
                            'model' => $model,
                            'label' => trans($transBaseName.'.page.field.image')
                        ]); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="form-group">
                    <?= view($viewBasePath.'.partials.form.create.textarea', [
                        'name' => 'content',
                        'label' => trans($transBaseName.'.page.field.content'),
                        'editor' => true,
                    ]) ?>
                </div>
            </div>
        </div>
        <?php if(ss(Setting::SS_ADMIN_PAGE_VIDEO_ENABLED)): ?>
        <div class="form-group">
            <?= view($viewBasePath.'.partials.form.create.input', [
                'name' => 'video',
                'label' => trans($transBaseName.'.page.field.video'),
                'type' => 'text',
                'comment' => trans($transBaseName.'.page.fieldComment.video')
            ]) ?>
        </div>
        <?php endif; ?>
        <?php if(Permission::canMangePageImages($user)): ?>
        <div class="form-group">
            <?= view($viewBasePath.'.page.form.images', [
                'entityName' => EntityManager::PAGE,
                'model' => $model,
                'label' => trans($transBaseName.'.page.field.images')
            ]) ?>
        </div>
        <?php endif; ?>
        <div class="form-group">
            <?= view($viewBasePath.'.partials.form.create.input', [
                'name' => 'metaTitle',
                'label' => trans($transBaseName.'.page.field.metaTitle'),
                'type' => 'text'
            ]) ?>
        </div>
        <div class="form-group">
            <?= view($viewBasePath.'.partials.form.create.textarea', [
                'name' => 'metaDescription',
                'label' => trans($transBaseName.'.page.field.metaDescription'),
            ]) ?>
        </div>
        <div class="form-group">
            <?= view($viewBasePath.'.partials.form.create.textarea', [
                'name' => 'metaKeywords',
                'label' => trans($transBaseName.'.page.field.metaKeywords'),
            ]) ?>
        </div>
        <div class="row">
            <?php if(!empty($model)): ?>
            <div class="col-sm-6">
                <?= Form::button(trans($transBaseName.'.form.delete'), [
                    'class' => 'btn btn-danger showAsActionBarButton',
                    'data-actionbar-order' => '2',
                    'data-actionbar-bgclass' => 'bgDanger',
                    'onclick' => 'checkEntityDelete()', 
                    ])?>
            </div>
            <?php endif; ?>
            <div class="col-xs-12 text-right">
                <?= Form::button(trans($transBaseName.'.form.save'), [
                    'id' => 'saveButton',
                    'class' => 'btn btn-primary save showAsActionBarButton',
                    'type' => 'submit', 
                    ])?>
            </div>
        </div>
    </div>
</div>

<?php echo Form::close(); ?>
@endsection

<?= view($viewBasePath.'.page.form.scripts', [
    'menuablePageTypes' => $menuablePageTypes
]) ?>
