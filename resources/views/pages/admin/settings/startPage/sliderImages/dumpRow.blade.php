<?php $isDumpRecord = ($index === 'dump-id') ? true : false; ?>
<div class="<?= $isDumpRecord ? 'dump-record ' : '' ?>recordRow panel panel-default" data-row-id="<?=$index?>">
    <div class="panel-heading">
        <div class="row">
            <div class="col-xs-12 form-group text-right">
                <i title="<?= trans($transBaseName.'.form.multiRecords.dragToOtherPosition')?>" class="glyphicon glyphicon-move pull-left"></i>
                <button type="button" title="<?= trans($transBaseName.'.form.file.browseImage')?>" class=" btn btn-default browseFileButton"><i class="glyphicon glyphicon-folder-open"></i></button>
                &nbsp;
                <button type="button" title="<?= trans($transBaseName.'.form.file.cancelChanges') ?>" class=" btn btn-default resetFileButton"><i class="fa fa-undo"></i></button>
                &nbsp;
                <?php if(!$isDumpRecord && !$record->isFileRequired() && !empty($record->filepath())): ?>
                <button type="button" title="<?= trans($transBaseName.'.form.file.deleteImage') ?>" class=" btn btn-default removeExistedFileButton"><i class="glyphicon glyphicon-trash"></i></button>
                &nbsp;
                <?php endif; ?>
                <button type="button" title="<?= trans($transBaseName.'.form.multiRecords.deleteRecord') ?>" class="remove-record btn btn-danger" data-db-id="<?=($isDumpRecord ? '': $record->id)?>" data-row-id="<?=$index?>"><i class="glyphicon glyphicon-remove"></i></button>
            </div>
            <div class="col-sm-1 hidden-xs form-group">&nbsp;</div>
            <div class="col-lg-4 col-md-5 col-sm-5 col-xs-12 form-group recordFieldWrapper text-center">
                <div class="fileFieldWrapper">
                    <?= Form::file("{$relationName}[{$index}][".\App\File::FILE_ATTRIBUTE_NAME."]",
                            array_merge([
                                'data-record-field' => '['.\App\File::FILE_ATTRIBUTE_NAME.']', 
                                'data-base-name' => $relationName, 
                                'class' => 'form-control recordFieldFile minimize', 
                                'autocomplete' => 'off'
                                ], ($isDumpRecord ? ['disabled' => ''] : []))
                                    ) ?>

                    <?= Form::text("_{$relationName}[{$index}][".\App\File::FILE_ATTRIBUTE_NAME."]",
                            (!$isDumpRecord && !empty($record->filepath()) ? 'valid' : ''),
                            array_merge([
                                'data-record-field' => '['.\App\File::FILE_ATTRIBUTE_NAME.']', 
                                'data-base-name' => "_{$relationName}",
                                'data-default-value' => (!$isDumpRecord && !empty($record->filepath()) ? 'valid' : ''),
                                'class' => 'recordFieldFileShadow minimize', 
                                'autocomplete' => 'off'
                                ], ($isDumpRecord ? ['disabled' => ''] : []))
                                    ) ?>
                    <div class="filePreviewWrapper text-center">
                        <?php if($isDumpRecord): ?>
                            @include($viewBasePath.'.partials.form.create.filePreview', ['path' => ''])
                        <?php else: ?>
                            @include($viewBasePath.'.partials.form.create.filePreview', ['path' => $record->filePath()])
                        <?php endif; ?>
                        <div class="previewChosenFile filePreview hidden">
                            <img src="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 col-md-6 col-sm-6 col-xs-12 form-group recordFieldWrapper">
                <?= Form::textarea("{$relationName}[{$index}][description]", ($isDumpRecord ? null : $record->description), 
                    array_merge([
                        'rows' => 3,
                        'placeholder' => trans($transBaseName.'.settings.startPage.images.description').' '.trans($transBaseName.'.form.optionalLabel'),
                        'data-record-field' => '[description]', 
                        'data-base-name' => $relationName, 
                        'class' => 'form-control', 
                        'autocomplete' => 'off'
                        ], ($isDumpRecord ? ['disabled' => ''] : []) )
                    ) ?>
            </div>
            <div style="display: none;" class="col-sm-12 form-group recordFieldWrapper minimize">
                <?= Form::text("{$relationName}[{$index}][deleteFile]", null, 
                    array_merge([
                        'data-record-field' => '[deleteFile]', 
                        'data-base-name' => $relationName, 
                        'class' => 'form-control recordFieldDeleteImage', 
                        'autocomplete' => 'off'
                        ], ($isDumpRecord ? ['disabled' => ''] : []))
                        ) ?>
            </div>
            <div style="display: none;" class="col-sm-12 form-group recordFieldWrapper minimize">
                <?= Form::text("{$relationName}[{$index}][type]", \App\File::TYPE_SLIDER_IMAGE, 
                    array_merge([
                        'data-record-field' => '[type]', 
                        'data-base-name' => $relationName, 
                        'class' => 'form-control', 
                        'autocomplete' => 'off'
                        ], ($isDumpRecord ? ['disabled' => ''] : []))
                        ) ?>
            </div>
            <div style="display: none;" class="col-sm-12 form-group recordFieldWrapper minimize">
                <?= Form::hidden("{$relationName}[{$index}][id]", ($isDumpRecord ? null : $record->id), 
                    array_merge([
                        'data-record-field' => '[id]', 
                        'data-base-name' => $relationName, 
                        'class' => 'form-control', 
                        'autocomplete' => 'off'
                        ], ($isDumpRecord ? ['disabled' => ''] : []) )
                    ) ?>
            </div>
        </div>
    </div>
</div>