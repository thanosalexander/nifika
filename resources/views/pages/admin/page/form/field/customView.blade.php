
<?= view($viewBasePath.'.partials.form.create.select', [
    'name' => 'customView',
    'label' => trans("{$transBaseName}.page.field.customView"),
    'fieldModelRelation' => 'CustomValue', 
    'list' => $pageViews,
    'defaultValue' => null,
    'hidePlaceHolder' => (count($pageViews) === 1),
    'selectedValue' => (!empty($model) ? $model->customView : null),
]) ?>
