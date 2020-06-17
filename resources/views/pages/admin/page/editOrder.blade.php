<?php

use App\Page;

if(empty($model)){
    $subPages = Page::topLevel(auth()->user())->orderBy('sort')->get();
} else {
    $subPages = $model->subPages()->orderBy('sort')->get();
}
?>

@section('contentTop')
@parent
<div class="row nomarginHorizontal">
    <div class="col-lg-12 col-md-12 nopaddingLeft marginBottom15">
        <h4><?= e($pageData['pageTitle']) ?><?= (!empty($model) ? ': ' . e($model->getMyName()) : '') ?></h4>
        <?php if(auth()->user()->isAdmin()): ?>
        <span class="help-block"><?= e(trans($transBaseName.'.form.editOrderComment')) ?></span>
        <?php endif; ?>
    </div>
</div>
@endsection

@section('content')
@parent

<?php $formDefaultAttributes = ['class' => 'hasJsValidator', 'files' => true]; ?>
<?php if (!empty($model)): ?>
    <?= Form::model($model, array_merge(['route' => [$routeBaseName.'.entity.updateOrder', $entityName, $model->id, $relationEntityName], 'method' => 'PUT'], $formDefaultAttributes)); ?>
<?php else: ?>
    <?= Form::open(array_merge(['route' => [$routeBaseName.'.entity.updateOrder', $entityName, null, null], 'method' => 'PUT'], $formDefaultAttributes)); ?>
<?php endif; ?>
<div class="row nomarginHorizontal">
    <div class="col-lg-12 col-md-12 nopaddingLeft">
        <?= view($viewBasePath.'.partials.form.create.message') ?>
        
        <div class="row">
            <div class="col-xs-12">
                <div class="orderableList">
                    <ul class="list-group">
                    <?php foreach($subPages as $item): /* @var $item Page */?>
                        <li class="list-group-item list-group-item-action" data-id="<?= $item->id ?>">
                            <i class="glyphicon glyphicon-move"></i>
                            <?= $item->getMyName() ?>
                            <input type="hidden" class="subPages" name="subPages[<?= $item->id ?>]" value="1"/>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        
        
        <div class="row">
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

<?= view($viewBasePath.'.page.form.editOrderScripts', [
]) ?>
    