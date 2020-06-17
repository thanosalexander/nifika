<?php
$relationName = 'sliderImages';
$label = !isset($label) ? null : $label;
$fields =  [];
$required = isset($required) ? true : false;
$maxRecords = -1;
$comment = !isset($comment) ? null : $comment;
$fieldWrapperId = $relationName . 'MultiRecordsTable';
?>

<div class="row cmb-row cmb-type-multi-records" id="<?= $fieldWrapperId ?>">
    <div class="form-group col-lg-12 elementShadowWrapper">
        <?= Form::{($required ? 'labelRequired' : 'label')}($relationName, $label) ?>
        <?php if($required): ?>
            @include($viewBasePath.'.partials.form.create.shadow', ['class' => '',
            'name' => "_{$relationName}", 'enabled' => ''
            ])
        <?php endif; ?>
    </div>
    <div class="col-lg-12">
        <div class="recordsContainer panel-group">
            <?php 
            //add first the dump record
            $records = App\File::sliderImages()->prepend('', 'dump-id');
            ?>
            <?php foreach($records as $index => $record): ?>
            @include($viewBasePath.'.settings.startPage.sliderImages.dumpRow')
            <?php endforeach; ?>
        </div>
    </div>
    <?php if (!empty($comment) || $maxRecords > 0): ?>
    <div class="col-lg-6">
        @include($viewBasePath.'.partials.form.create.field.comment', [
        'name' => $relationName,
        'comment' => 
        (!empty($comment) ? $comment : '')
        .($maxRecords > 0 ? '(' . trans($transBaseName.'.form.multiRecords.maxRecords') .' '. $maxRecords . ')' : '')
        ])
    </div>
    <?php endif; ?>
    <div class="col-lg-12 text-right">
        <button type="button" class="add-record btn btn-primary"><?= trans($transBaseName.'.form.multiRecords.addImage') ?></button>
    </div>
</div>

@include($viewBasePath.'.settings.startPage.sliderImages.scripts')
