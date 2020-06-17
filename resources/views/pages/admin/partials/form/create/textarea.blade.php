<?php
$name = !isset($name) ? null : $name;
$label = !isset($label) ? null : $label;
$noLabel = isset($noLabel) ? boolval($noLabel) :false;
$comment = !isset($comment) ? '' : $comment;
$required = isset($required) ? true : false;
$editor = isset($editor) ? true : false;
$defaultValue = isset($defaultValue) ? $defaultValue : null;
$rows = isset($rows) ? $rows : 5;

$defaultAttributes = ['autocomplete' => 'off', 'class' => 'form-control', 'rows' => $rows];
if($editor){
    $defaultAttributes['class'].=' hasEditor';
}
//$defaultAttributes = !$editor && $required ? array_merge($defaultAttributes, ['required'=> 'required', 'data-validation'=>'required']) : $defaultAttributes;
$fieldAttributes = array_merge($defaultAttributes, ['id' => $name]);
?>
<?php if(!$noLabel): ?>
<?= Form::{($required ? 'labelRequired' : 'label')}($name, $label) ?>
<?php endif; ?>
<?= Form::textarea($name, $defaultValue, $fieldAttributes); ?>
<?= view($viewBasePath.'.partials.form.create.field.comment', ['comment' => $comment]) ?>
<?= view($viewBasePath.'.partials.form.create.field.error', ['name' => $name]) ?>
