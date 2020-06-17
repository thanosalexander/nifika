<script>
    $(document).ready(function () {
        $('.switchButton.switchButtonYesNo input[type="checkbox"]').bootstrapToggle({
            on: '<?= trans($transBaseName.'.form.switchButton.on.yes') ?>',
            off: '<?= trans($transBaseName.'.form.switchButton.off.no') ?>',
            onstyle: 'success',
            offstyle: 'danger'
        });
    });
</script>