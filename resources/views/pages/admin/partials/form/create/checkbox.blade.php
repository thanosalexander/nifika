<?php

use App\Logic\Base\BaseModel;

$name = !isset($name) ? null : $name;
$label = !isset($label) ? null : $label;
$labelWrap = isset($labelWrap) ? true : false;
$checkboxClass = isset($checkboxClass) ? $checkboxClass : '';
$hideLabel = isset($hideLabel) ? true : false;
$comment = !isset($comment) ? '' : $comment;
$required = isset($required) ? ($required === false ? false : true) : false;
$checkedValue = isset($checkedValue) ? $checkedValue : BaseModel::ENABLED_YES;
$isChecked = (isset($isChecked) && $isChecked) ? true : null;
$isDisabled = (isset($isDisabled)) ? $isDisabled : false;

$defaultAttributes = ['autocomplete' => 'off', 'class' => 'form-check'];

if (!isset($defaultAttributes['class']) || empty($defaultAttributes['class'])) {
    $defaultAttributes['class'] = $checkboxClass;
} else if (!empty($checkboxClass)) {
    $defaultAttributes['class'] = implode(' ', [$defaultAttributes['class'], $checkboxClass]);
}

//$defaultAttributes = $required ? array_merge($defaultAttributes, ['required'=> 'required', 'data-validation'=>'required']) : $defaultAttributes;
$fieldAttributes = array_merge($defaultAttributes, ['id' => $name]);
if($isDisabled){
    $fieldAttributes = array_merge($defaultAttributes, ['disabled' => '']);
}
?>
<?php // echo Form::checkbox($name, $checkedValue, null, $fieldAttributes)
        //.'&nbsp;' . Form::{($required ? 'labelRequired' : 'label')}($name, $label);?>
<?php if($labelWrap): ?>
<?= Form::checkboxLabeled($name, Form::{($required ? 'labelRequired' : 'label')}($name, $label, ['class' => 'form-check-label']),
        $checkedValue, $isChecked, $fieldAttributes); ?>
<?php else: ?>
<?php if(!$hideLabel): ?>
<?=Form::{($required ? 'labelRequired' : 'label')}($name, $label, ['class' => 'form-check-label']);?>
<?php endif; ?>
<?= Form::checkbox($name, $checkedValue, $isChecked, $fieldAttributes) ?>
<?php endif; ?>

<?= view($viewBasePath.'.partials.form.create.field.comment', ['comment' => $comment]) ?>
<?= view($viewBasePath.'.partials.form.create.field.error', ['name' => $name]) ?>
