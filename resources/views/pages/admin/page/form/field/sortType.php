
<?= view($viewBasePath.'.partials.form.create.select', [
    'name' => 'sortType',
    'label' => trans("{$transBaseName}.page.field.sortType"),
    'fieldModelRelation' => 'CustomValue', 
    'list' => $pageOrderTypes,
    'defaultValue' => null,
    'selectedValue' => (!empty($model) ? $model->sortType : null),
]) ?>
