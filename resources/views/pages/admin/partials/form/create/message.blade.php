@if($errors->any() || Session::has('message') && Session::has('status'))
<?php 
    $messageClass = Session::get('status')==App\Http\Controllers\Controller::STATUS_OK ? 'success' : 'danger'; 
    $message = $errors->any() 
            ? trans($transBaseName.'.form.messageValidationFail')
            : Session::get('message');
    $message = e($message);
?>
<script>
    $(document).ready(function(){
<?php   if( $messageClass == 'success' ){ ?>
            NW.success('<?=$message?>');
<?php   }elseif( $messageClass == 'danger' ){ ?>
            NW.fail('<?=$message?>');
<?php   } ?>
    });
</script>
<!--<div class="alert alert-<?=$messageClass?> alert-dismissible fade in">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
    {{$errors->any() ? trans($transBaseName.'.form.messageValidationFail'): Session::get('message')}}
</div>-->
@endif
