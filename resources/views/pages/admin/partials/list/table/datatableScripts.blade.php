<?php
/* @var $tableData array */
?>
@section('bodyEnd')
@parent
<!-- datatable -->
<script>
var myDatatable = {table: null, filters: null};
$(document).ready(function () {
    myDatatable.table = $(document).find('table').DataTable({
        processing: true,
        serverSide: true,
        stateSave: true,
        responsive: true,
        searching: <?= !empty($tableData['globalSearch']) ? ($tableData['globalSearch'] ? 'true' : 'false'): 'false' ?>,
        autoWidth: false,
        order: <?= json_encode($tableData['order']) ?>,
        paging: <?= !empty($tableData['paging']) ? ($tableData['paging'] ? 'true' : 'false'): 'false' ?>,
        ajax: {
            url: '<?= route($routeBaseName.'.entity.listdata', [$entityName, (!is_null($parentModel) ? $parentModel->id: null), $relationEntityName]) ?>',
            data: function ( d ) {
                //add filters values to datatable data
                if(myDatatable.filters !== null){
                    for(var filterIndex in myDatatable.filters){
                        if(myDatatable.filters[filterIndex] !== null){
                            for(var index in d.columns){
                                if(filterIndex === d.columns[index].name){
                                    d.columns[index].search.value = myDatatable.filters[filterIndex];
                                }
                            }
                        }
                    }
                }
                return $.extend( {}, d);
            },
            statusCode: {
                401: function() {location.reload();}
            }
        },
        columns: <?= json_encode($tableData['columnsData']) ?>,
        "language": {
            "url": '<?= App\Logic\Locales\AppLocales::getCurrentLocale() !== 'en' ? asset($assetBasePath.'/assets/datatables/i8n/'. App\Logic\Locales\AppLocales::getCurrentLocale().'.json') : '' ?>'
        }
    });
});
</script>
@endsection
