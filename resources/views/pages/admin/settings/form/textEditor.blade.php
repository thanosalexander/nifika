<?= Form::label($name, $label).
     Form::textarea($name, $value, ['class' => 'form-control hasEditor', 'autocomplete' => 'off' ]);
?>