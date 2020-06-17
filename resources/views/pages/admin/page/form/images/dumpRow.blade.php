<?php 

use App\PageImage;
$isDumpRecord = ($index === 'dump-id') ? true : false;
?>
<div class="<?= $isDumpRecord ? 'dump-record ' : '' ?>recordRow panel panel-default col-lg-4 col-md-6 col-sm-6 nopadding ui-state-default" data-row-id="<?=$index?>">
    <div class="panel-heading text-right">
        <div class="row">
            <div class="col-sm-12 form-group recordFieldWrapper">
                <i title="<?= trans($transBaseName.'.form.multiRecords.image.dragToOtherPosition')?>" class="glyphicon glyphicon-move pull-left"></i>
                
                <button type="button" title="<?= trans($transBaseName.'.form.multiRecords.image.browse')?>" class=" btn btn-default browseFileButton"><i class="glyphicon glyphicon-folder-open"></i></button>
                &nbsp;&nbsp;&nbsp;
                <button type="button" title="<?= trans($transBaseName.'.form.multiRecords.image.cancelChanges') ?>" class=" btn btn-default resetFileButton"><i class="fa fa-undo"></i></button>
                <?php if(!$isDumpRecord && !$record->isFileRequired() && !empty($record->filepath())): ?>
                <button type="button" title="<?= trans($transBaseName.'.form.multiRecords.image.delete') ?>" class=" btn btn-default removeExistedFileButton"><i class="glyphicon glyphicon-trash"></i></button>
                <?php endif; ?>
                <br>
                <div class="fileFieldWrapper">
                    <?= Form::file("{$formRelationName}[{$index}][".PageImage::FILE_ATTRIBUTE_NAME."]",
                            array_merge([
                                'data-record-field' => '['.PageImage::FILE_ATTRIBUTE_NAME.']', 
                                'data-base-name' => $formRelationName, 
                                'class' => 'form-control recordFieldFile minimize', 
                                'autocomplete' => 'off'
                                ], ($isDumpRecord ? ['disabled' => ''] : []))
                                    ) ?>

                    <?= Form::text("_{$formRelationName}[{$index}][".PageImage::FILE_ATTRIBUTE_NAME."]",
                            (!$isDumpRecord && !empty($record->filepath()) ? 'valid' : ''),
                            array_merge([
                                'data-record-field' => '['.PageImage::FILE_ATTRIBUTE_NAME.']', 
                                'data-base-name' => "_{$formRelationName}",
                                'data-default-value' => (!$isDumpRecord && !empty($record->filepath()) ? 'valid' : ''),
                                'class' => 'recordFieldFileShadow minimize', 
                                'autocomplete' => 'off'
                                ], ($isDumpRecord ? ['disabled' => ''] : []))
                                    ) ?>
                    <div class="filePreviewWrapper text-center">
                        <?php if($isDumpRecord): ?>
                            <?= view("{$viewBasePath}.partials.form.create.filePreview", ['path' => '']) ?>
                        <?php else: ?>
                            <?= view("{$viewBasePath}.partials.form.create.filePreview", ['path' => $record->filePath()]) ?>
                        <?php endif; ?>
                        <div class="previewChosenFile filePreview hidden">
                            <img src="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 form-group form-check switchButton imagesSwitchButtonYesNo recordFieldWrapper text-right">
                <?= Form::checkboxLabeled(
                        "{$formRelationName}[{$index}][enabled]",
                        Form::label("{$formRelationName}[{$index}][enabled]", trans($transBaseName.'.form.multiRecords.image.enabled')),
                        PageImage::ENABLED_YES, 
                        ($isDumpRecord ? PageImage::ENABLED_YES : $record->enabled), 
                        array_merge([
                            'data-record-field' => '[enabled]', 
                            'data-base-name' => $formRelationName, 
                            'class' => 'form-control', 
                            'autocomplete' => 'off'
                            ], ($isDumpRecord ? ['disabled' => ''] : []))
                        ); ?>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="button" title="<?= trans($transBaseName.'.form.multiRecords.deleteRecord') ?>" class="remove-record btn btn-danger" data-row-id="<?=$index?>"><i class="glyphicon glyphicon-remove"></i></button>
            </div>
            <div style="display: none;" class="col-sm-12 form-group recordFieldWrapper minimize">
                <?= Form::text("{$formRelationName}[{$index}][deleteImage]", null, 
                    array_merge([
                        'data-record-field' => '[deleteImage]', 
                        'data-base-name' => $formRelationName, 
                        'class' => 'form-control recordFieldDeleteImage', 
                        'autocomplete' => 'off'
                        ], ($isDumpRecord ? ['disabled' => ''] : []))
                        ) ?>
            </div>
            <div style="display: none;" class="col-sm-12 form-group recordFieldWrapper minimize">
                <?= Form::hidden("{$formRelationName}[{$index}][id]", ($isDumpRecord ? null : $record->id), 
                    array_merge([
                        'data-record-field' => '[id]', 
                        'data-base-name' => $formRelationName, 
                        'class' => 'form-control', 
                        'autocomplete' => 'off'
                        ], ($isDumpRecord ? ['disabled' => ''] : []) )
                    ) ?>
            </div>
        </div>
    </div>
</div>
