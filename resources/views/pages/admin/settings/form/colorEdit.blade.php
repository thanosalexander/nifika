<script>
    $(document).ready(function(){
        $('#<?=$id?>').spectrum({
//            color: "#ECC",
            showInput: true,
            className: "full-spectrum",
            showInitial: true,
            showPalette: true,
            showSelectionPalette: true,
            maxSelectionSize: 10,
            preferredFormat: "rgb",
            localStorageKey: "spectrum.<?=$id?>",
            showAlpha: true
        });
    });
</script>
<?= Form::label($id, $label).'<br>'.
     Form::text($name, $value, ['id'=>$id, 'class' => 'form-control', 'autocomplete' => 'off', ]);
?>
