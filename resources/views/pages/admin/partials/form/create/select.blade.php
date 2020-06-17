<?php

$list = empty($list) ? [] : $list;
$name = !isset($name) ? null : $name;
$label = !isset($label) ? null : $label;
$noLabel = isset($noLabel) ? boolval($noLabel) :false;
$comment = !isset($comment) ? null : $comment;
$required = isset($required) ? true : false;
$defaultValue = isset($defaultValue) ? $defaultValue : null;
$selectedValue = isset($selectedValue) ? $selectedValue : null;
$hidePlaceHolder = isset($hidePlaceHolder) ? true : false;
$subChoicesName = !isset($subChoicesName) ? null : $subChoicesName;
$hasMultiSubChoices = isset($hasMultiSubChoices) ? true : false;
$isSubChoice = isset($isSubChoice) ? true : false;
$parentChoiceName = !isset($parentChoiceName) ? null : $parentChoiceName;
$fieldModelRelation = !isset($fieldModelRelation) ? 'ModelAttribute' : $fieldModelRelation;
$validationRules = (!isset($validationRules) || !is_array($validationRules)) ? [] : $validationRules;
$extraAttributes = (!isset($extraAttributes) || !is_array($extraAttributes)) ? [] : $extraAttributes;
$appendClass = isset($appendClass) ? $appendClass : '';

$fieldValue = null;
$fieldWrapperId = $name . 'SingleChoice';
$defaultAttributes = ['autocomplete' => 'off', 'class' => 'form-control'];
$defaultAttributes = $required ? array_merge($defaultAttributes, ['required'=> '']) : $defaultAttributes;
$defaultAttributes = array_merge($defaultAttributes, $validationRules);
if(!empty($model)){
    switch($fieldModelRelation){
        case 'ManyToMany':
            $fieldValue = !is_null($model->$name->first()) ? $model->$name->first()->id : null;
            break;
        case 'ModelAttribute':
            $fieldValue = $model->{$name};
            break;
        case 'CustomValue':
            $fieldValue = $selectedValue;
            break;
        default:
            $fieldValue = null;
            break;
    }
}
$fieldValue = $fieldValue === null ? $defaultValue : $fieldValue;
$fieldAttributes = array_merge($defaultAttributes, ['id' => $name, 'placeholder' => trans($transBaseName.'.form.selectPlaceholder')]);
$fieldAttributes = array_merge($fieldAttributes, $extraAttributes);

if (!isset($fieldAttributes['class']) || empty($fieldAttributes['class'])) {
    $fieldAttributes['class'] = $appendClass;
} else if (!empty($appendClass)) {
    $fieldAttributes['class'] = implode(' ', [$fieldAttributes['class'], $appendClass]);
}

if($hidePlaceHolder){
    unset($fieldAttributes['placeholder']);
}
?>

<div id="<?=$fieldWrapperId?>">
    <?php if(!$noLabel): ?>
    <?= Form::{($required?'labelRequired':'label')}($name, $label) ?>
    <?php endif; ?>
        <?php if($isSubChoice): ?>
        <?= Form::selectSubChoice($name, $parentChoiceName, $list, $fieldValue, $fieldAttributes);?>
        <?php else: ?>
        <?= Form::select($name, $list, $fieldValue, $fieldAttributes);?>
        <?php endif; ?>
        <?= view($viewBasePath.'.partials.form.create.field.comment', ['comment' => $comment]) ?>
        <?= view($viewBasePath.'.partials.form.create.field.error', ['name' => $name]) ?>
</div>
<?php if(!empty($subChoicesName)): ?>
<?php 
    $subChoicesType = $hasMultiSubChoices ? 'Multi' : 'Single';
    $subChoicesRootSelector = "#{$subChoicesName}{$subChoicesType}Choice";
?>
<?php if($hasMultiSubChoices): ?>
<?= view($viewBasePath.'.partials.form.scripts.multiSubChoices', ['parentChoiceName' => $name, 'subChoicesRootSelector' => $subChoicesRootSelector]) ?>
<?php else: ?>
<?= view($viewBasePath.'.partials.form.scripts.singleSubChoices', ['parentChoiceName' => $name, 'subChoicesName' => $subChoicesName]) ?>
<?php endif; ?>
<?php endif; ?>
