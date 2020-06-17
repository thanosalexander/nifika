<?php

$name = !isset($name) ? null : $name;
$class = isset($class) ? $class : '';
$enabled = isset($enabled) ? true : false;

$defaultAttributes = ['autocomplete' => 'off', 'class' => 'elementShadow'];

$value = null;
if (empty($model)) { //on create shadow is invalid
    if (!$enabled) { //are disabled
        $value = 'valid';
        $defaultAttributes['class'] .= ' isDisabled';
    }
} else { //on edit shadows are enabled and valid
    $value = 'valid';
}

if (!isset($defaultAttributes['class']) || empty($defaultAttributes['class'])) {
    $defaultAttributes['class'] = $class;
} else if (!empty($class)) {
    $defaultAttributes['class'] = implode(' ', [$defaultAttributes['class'], $class]);
}

$fieldAttributes = array_merge($defaultAttributes, ['id' => $name]);
?>
<?= Form::text($name, $value, $fieldAttributes); ?>