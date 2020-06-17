<?php if(!empty($path)): ?>
<a class="previewExistedFile filePreview" href="<?=e($path)?>" width="100" target="_blank">
    <?php if( !isset($isFile) || !$isFile ): ?>
    <img class="image" src="<?=asset($path)?>"/>
    <?php else: ?>
    <?= trans($transBaseName.'.form.filePreview'); ?>
    <?php endif; ?>
</a>
<?php endif; ?>
