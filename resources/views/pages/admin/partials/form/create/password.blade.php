<?php
$name = !isset($name) ? null : $name;
$label = !isset($label) ? null : $label;
$comment = !isset($comment) ? '' : $comment;
$required = isset($required) ? true : false;

$defaultAttributes = ['autocomplete' => "off"];
//$defaultAttributes = empty($model) && $required  ? array_merge($defaultAttributes, ['required'=> 'required', 'data-validation'=>'required']) : $defaultAttributes;

$fieldAttributes = array_merge($defaultAttributes, ['id' => $name]);
?>
<div class="cmb-row cmb-type-text">
    <div class="cmb-th">
        <?= Form::{($required ? 'labelRequired' : 'label')}($name, $label) ?>
    </div>
    <div class="cmb-td">
        @if ($errors->has($name))
            <div class="validation-error">{{ $errors->first($name) }}</div>
        @endif
        <?= Form::password($name, $fieldAttributes); ?>
        <?php if (!empty($comment)): ?>
            <p class="cmb2-metabox-description"><?=$comment?></p>
        <?php endif; ?>
    </div>
</div>