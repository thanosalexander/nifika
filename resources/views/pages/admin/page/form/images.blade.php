<?php
$formRelationName = 'images';
$label = !isset($label) ? null : $label;
$required = isset($required) ? true : false;
$maxRecords = -1;
$comment = !isset($comment) ? null : $comment;
$fieldWrapperId = $formRelationName . 'MultiRecordsTable';
?>

<div class="row nomarginHorizontal cmb-row cmb-type-multi-records" id="<?= $fieldWrapperId ?>">
    <legend class="form-group elementShadowWrapper">
        <?=$label ?>
        <a href="#<?= $fieldWrapperId ?>Collapse" class="toogleRecordsArrow<?= empty($model) ? ' ': ' ' ?>" data-toggle="collapse">
            <i class="fa"></i>
        </a>
        <?php if($required): ?>
                <?= view("{$viewBasePath}.partials.form.create.shadow", ['class' => '',
                'name' => "_{$formRelationName}", 'enabled' => ''
                ]) ?>
        <?php endif; ?>
    </legend>
    <div id="<?= $fieldWrapperId ?>Collapse" class="collapse <?= empty($model) ? ' in': ' in' ?>">
        <div class="col-lg-12 nopadding">
            <div class="recordsContainer panel-group row nomarginHorizontal">
                <?php 
                $modelRelations = (empty($model)
                    ? collect()
                    : $model->imagesOrdered()->get()
                );
                //add first the dump record
                $records = $modelRelations->prepend('', 'dump-id');
                ?>
                <?php foreach($records as $index => $record): ?>
                    <?= view("{$viewBasePath}.page.form.{$formRelationName}.dumpRow", [
                        'index' => $index,
                        'record' => $record,
                        'formRelationName' => $formRelationName,
                    ]) ?>
                <?php endforeach; ?>
            </div>
        </div>
        <?php if (!empty($comment) || $maxRecords > 0): ?>
        <div class="col-md-12">
            <?= view("{$viewBasePath}.partials.form.create.field.comment", [
            'name' => $formRelationName,
            'comment' => 
            (!empty($comment) ? $comment : '')
            .($maxRecords > 0 ? '(' . trans($transBaseName.'.form.multiRecords.maxRecords') .' '. $maxRecords . ')' : '')
            ]) ?>
        </div>
        <?php endif; ?>
        <div class="col-md-12 text-right nopadding">
            <button type="button" class="add-record btn btn-primary"><?= trans($transBaseName.'.form.multiRecords.image.add') ?></button>
        </div>
    </div>
</div>

<?= view("{$viewBasePath}.page.form.{$formRelationName}.scripts", [
    'fieldWrapperId' => $fieldWrapperId,
    'maxRecords' => $maxRecords,
]) ?>