<?php
$type = !isset($type) || !in_array($type, ['date', 'text', 'file']) ? 'text' : $type;
$name = !isset($name) ? null : $name;
$label = !isset($label) ? null : $label;
$noLabel = isset($noLabel) ? boolval($noLabel) :false;
$comment = !isset($comment) ? '' : $comment;
$required = isset($required) ? true : false;
$defaultValue = isset($defaultValue) ? $defaultValue : null;
$placeholder = (isset($placeholder) && !empty($placeholder)) ? $placeholder : null;

$defaultAttributes = ['autocomplete' => 'off', 'class' => 'form-control'];
//$defaultAttributes = $required ? array_merge($defaultAttributes, ['required'=> 'required', 'data-validation'=>'required']) : $defaultAttributes;
if($type == 'file' && !empty($model)){/// on edit action
    unset($defaultAttributes['required']);
    unset($defaultAttributes['data-validation']);
}
$fieldAttributes = array_merge($defaultAttributes, ['id' => $name]);

if ($type == 'date') {
    $fieldAttributes = array_merge($defaultAttributes, ['data-datepicker' => '']);
}
if (!empty($placeholder)) {
    $fieldAttributes['placeholder'] = $placeholder;
}
?>
    <?php if(!$noLabel): ?>
    <?= Form::{($required ? 'labelRequired' : 'label')}($name, $label) ?>
    <?php endif; ?>
    <?= Form::input($type == 'date'?'date':$type , $name, $defaultValue, $fieldAttributes); ?>
    <?php if($type == 'file' && !empty($model)):?>
        <?= view($viewBasePath.'.partials.form.create.filePreview', [
            'path' => $model->profileImagePath()
        ]) ?>
        <br/>
    <?php endif; ?>
    <?= view($viewBasePath.'.partials.form.create.field.comment', ['comment' => $comment]) ?>
    <?= view($viewBasePath.'.partials.form.create.field.error', ['name' => $name]) ?>
<?php if($type == 'date'): ?>
<script>
    $(document).ready(function () {
        $('input[name="<?=$name?>"][data-datepicker]').datepicker({
            dateFormat: 'dd/mm/yy',
            showButtonPanel: true,
            autoclose: true,
            closeText: "Ok",
            currentText: "Now"
        });      
    });
</script>
<?php endif; ?>
<?php if($type == 'file'): ?>
<script>
    $(document).ready(function () {
        $(document).on('change', "#<?=$name?>", function (){
            var $myform = $($(document).find("#<?=$name?>").closest('form')[0]);
            if($myform.data('validator') !== undefined){
                $("#<?=$name?>").valid();
            }
        });     
    });
</script>
<?php endif; ?>