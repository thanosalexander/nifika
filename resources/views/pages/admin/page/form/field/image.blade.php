<?php

use App\Page;

?>
    <div class="row mainImageRow">
        <div class="col-xs-12 form-group text-right">
            <button type="button" title="<?= trans($transBaseName.'.form.file.browseImage')?>" class=" btn btn-default browseFileButton"><i class="glyphicon glyphicon-folder-open"></i></button>
            &nbsp;
            <button type="button" title="<?= trans($transBaseName.'.form.file.cancelChanges') ?>" class=" btn btn-default resetFileButton"><i class="fa fa-undo"></i></button>
            &nbsp;
            <?php if(!is_null($model) && !$model->isFileRequired() && !empty($model->filepath())): ?>
            <button type="button" title="<?= trans($transBaseName.'.form.file.deleteImage') ?>" class=" btn btn-default removeExistedFileButton"><i class="glyphicon glyphicon-trash"></i></button>
            &nbsp;
            <?php endif; ?>
        </div>
        <!--<div class="col-lg-1 col-md-1 col-sm-1 hidden-xs form-group">&nbsp;</div>-->
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group recordFieldWrapper text-center">
            <div class="fileFieldWrapper">
                <?= Form::file(Page::FILE_ATTRIBUTE_NAME, [
                    'class' => 'form-control recordFieldFile minimize', 
                    'autocomplete' => 'off'
                ]) ?>
                <?php
                $hasFile = (!is_null($model) && !empty($model->filepath()));
                ?>
                <?= Form::text('_'.Page::FILE_ATTRIBUTE_NAME,
                        ($hasFile ? 'valid' : ''), [
                            'data-default-value' => ($hasFile ? 'valid' : ''),
                            'class' => 'recordFieldFileShadow minimize', 
                            'autocomplete' => 'off'
                            ]) ?>
                <span class="deleteFileNotice text-danger hidden"><?= trans($transBaseName.'.form.file.deleteImageFileSelected')?></span>
                <div class="filePreviewWrapper text-center">
                    <?= view($viewBasePath.'.partials.form.create.filePreview', ['path' => (!is_null($model) ? $model->filePath() : '')]); ?>
                    <div class="previewChosenFile filePreview hidden">
                        <img src="">
                        <br/>
                        <span class="previewChosenFileName"></span>
                    </div>
                </div>
            </div>
        </div>
        <div style="" class="col-sm-12 form-group minimized">
            <?= Form::text('deleteFile', null, [
                    'class' => 'form-control recordFieldDeleteFile minimize', 
                    'autocomplete' => 'off'
                ]) ?>
        </div>
    </div>
