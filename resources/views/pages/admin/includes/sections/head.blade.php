
@section('headMeta')
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title><?= trans($transBaseName.'.panelName') ?></title>
@parent
@endsection

@section('headStyles')
    <?php // use this to set scripts on the header ?>
    <!-- Bootstrap Core CSS -->
    <link href="<?= asset($assetBasePath.'/assets/bootstrap/css/bootstrap-3.3.7.min.css')?>" rel="stylesheet">
    <link href="<?= asset($assetBasePath.'/assets/bootstrap/css/bootstrap-3.3.7-additional.css')?>" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= asset($assetBasePath.'/css/sb-admin.css')?>" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="<?=asset($assetBasePath.'/assets/font-awesome-4.7.0/css/font-awesome.min.css')?>" rel="stylesheet">
    
    <link href="<?=asset($assetBasePath.'/css/admin.css')?>" rel="stylesheet">
    
    <link href="https://code.jquery.com/ui/1.11.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet"/>
    <link href="<?=asset($assetBasePath.'/assets/bootstrap-toggle/bootstrap-toggle.2.2.2.min.css');?>" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
@parent
@endsection

@section('headScripts')
    <?php // use this to set scripts on the header ?>
    <!-- jQuery -->
    <script src="<?=asset($assetBasePath.'/js/jquery-1.11.1.min.js')?>" type="text/javascript"></script>
    <script src="<?=asset($assetBasePath.'/js/jquery-ui-1.11.4.min.js')?>" type="text/javascript"></script>
    <script src="<?= asset($assetBasePath.'/js/jquery.ui.touch-punch.min.js') ?>" type="text/javascript"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="<?=asset($assetBasePath.'/assets/bootstrap/js/bootstrap-3.3.7.min.js')?>"></script>
    <script src="<?=asset($assetBasePath.'/assets/bootstrap-toggle/bootstrap-toggle.2.2.2.min.js');?>" type="text/javascript"></script>
    <script src="<?=asset($assetBasePath.'/assets/bootstrap-notify-3.1.3/bootstrap-notify.min.js')?>" type="text/javascript"></script>
    <script src="<?=asset($assetBasePath.'/js/notifywrapper.js')?>" type="text/javascript"></script>
    <script src="<?=asset($assetBasePath.'/js/adminUI.js')?>" type="text/javascript"></script>
@parent
@endsection