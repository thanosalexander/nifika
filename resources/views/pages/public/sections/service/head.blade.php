@section('headMeta')
    <title>Anna Veneti</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?= isset($seoTitle) ? e($seoTitle) : '';  ?></title>
<?php if (isset($seoDescription) && $seoDescription != "") { ?>
    <meta name="description" content="<?= isset($seoDescription) ? e($seoDescription) : '';  ?>">
<?php } ?>
<meta name="keywords" content="<?= isset($seoKeywords) ? e($seoKeywords) : '';  ?>">
@endsection

@section('headStyles')
<?php // use this to set scripts on the header ?>
<link rel="stylesheet" type="text/css" href="<?= asset($assetBasePath.'/css/service/style.css');  ?>"/>
<link rel="stylesheet" type="text/css" href="<?= asset($assetBasePath.'/css/common.css');  ?>"/>
<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans" />
<link rel="stylesheet" type="text/css" href="http://allfont.net/allfont.css?fonts=open-sans-extrabold" />
<link rel="stylesheet" type="text/css" href="<?= asset($assetBasePath.'/css/bootstrap.min.css'); ?>">
@endsection

@section('headScripts')
<?php // use this to set scripts on the header ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
@endsection
