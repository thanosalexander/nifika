<?php

use App\Logic\Pages\PageType;

$uniquePageTypes = PageType::uniquePageTypes();
?>
<?php if (is_null($model)): ?>
    <?php if (count($pageTypes) > 1): ?>
        <?= view($viewBasePath . '.partials.form.create.select', [
            'name' => 'type',
            'label' => trans("{$transBaseName}.page.field.type"),
            'fieldModelRelation' => 'CustomValue',
            'list' => $pageTypes,
            'defaultValue' => null,
            'selectedValue' => null,
            'required' => '',
        ])
        ?>
    <?php elseif (count($pageTypes) === 1): ?>
        <?php if (is_null($model)): ?>
            <?php $pageType = array_first(array_keys($pageTypes)); ?>
            <?= Form::hidden('type', $pageType, ['autocomplete' => 'off']); ?>
        <?php else: ?>
            <label><?= trans("{$transBaseName}.page.field.type") ?></label>: <?= PageType::pageTypeLabel($model->type) ?>
        <?php endif; ?>
    <?php endif; ?>
<?php else: ?>
   <?php if (in_array($model->type, $uniquePageTypes)): ?>
            <?= Form::hidden('type', $model->type, ['autocomplete' => 'off']); ?>
    <?php elseif (count($pageTypes) === 1): ?>
        <?php $pageType = array_first(array_keys($pageTypes)); ?>
        <?= Form::hidden('type', $pageType, ['autocomplete' => 'off']); ?>
   <?php elseif (count($pageTypes) > 1): ?>
        <?= view($viewBasePath . '.partials.form.create.select', [
            'name' => 'type',
            'label' => trans("{$transBaseName}.page.field.type"),
            'fieldModelRelation' => 'CustomValue',
            'list' => $pageTypes,
            'defaultValue' => null,
            'selectedValue' => $model->type,
            'required' => '',
        ])
        ?>
    <?php endif; ?>
<?php endif; ?>