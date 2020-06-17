<?php

/* @var $menuablePageTypes array */

?>
@section('headStyles')
@parent
@endsection

@section('headScripts')
@parent
@endsection

@section('bodyEnd')
@parent
<script>
    $(document).ready(function () {
        $(document).find(".orderableList ul").each(function(){
            //init record rows sort handler
            $(this).sortable({
                axis: "y",
                handle: 'i.glyphicon-move',
                placeholder: "ui-state-highlight",
                cancel: ".ui-state-disabled"
            });
        });
    });
</script>
@endsection