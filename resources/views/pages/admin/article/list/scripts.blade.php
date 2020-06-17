@section('headStyles')
@parent
@endsection

@section('bodyEnd')
@parent
<script>
    function checkSwitchStatus(id, fieldName) {
        var fieldClassName = fieldName+'TableField';
        var data = new FormData();
        var urlPattern = "<?= url(str_replace('{entity}', $entityName, \Route::getRoutes()->getByName($routeBaseName.'.entity.switchStatus')->uri()))?>";
        data.append('_method', 'PUT');
        data.append('_token', '<?=csrf_token()?>');
        data.append('fieldName', fieldName);
        $.ajax({
            type: "POST",
            url: urlPattern.replace('{id}', id),
            data: data,
            processData: false,
            contentType: false,
            success: function(result) {
                if($('#entityRow'+id+' .'+fieldClassName+'.label-success').hasClass('hidden')){
                    $('#entityRow'+id+' .'+fieldClassName+'.label-danger').addClass('hidden');
                    $('#entityRow'+id+' .'+fieldClassName+'.label-success').removeClass('hidden');
                }else{
                    $('#entityRow'+id+' .'+fieldClassName+'.label-success').addClass('hidden');
                    $('#entityRow'+id+' .'+fieldClassName+'.label-danger').removeClass('hidden');
                }
            },
            error: function(jqXHR, textStatus, errorThrown){
                alert('<?= trans($transBaseName.'.listEntity.updateStatusFailure') ?>');
            }
        });
    }

    $(document).ready(function () {
        // Reload DataTable data every n seconds
        $('#page-wrapper table').on( 'init.dt', function () {
            //
        });

        //on draw datatable colorize previous state from current order state
        $(document).find('#page-wrapper table').on('draw.dt', function () {
            //
        });
    });
</script>
@endsection