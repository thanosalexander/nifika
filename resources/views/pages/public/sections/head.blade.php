@section('headMeta')
<title>Anna Veneti</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= isset($seoTitle) ? e($seoTitle) : '';  ?></title>
<?php if (isset($seoDescription) && $seoDescription != "") { ?>
    <meta name="description" content="<?= isset($seoDescription) ? e($seoDescription) : '';  ?>">
<?php } ?>
<meta name="keywords" content="<?= isset($seoKeywords) ? e($seoKeywords) : '';  ?>">
@endsection

@section('headStyles')
<?php // use this to set scripts on the header ?>
<link rel="stylesheet" type="text/css" href="<?= asset($assetBasePath.'/css/style.css');  ?>"/>
<link rel="stylesheet" type="text/css"  href="<?= asset($assetBasePath.'/css/fonts.css');  ?>"/>
<script defer src="<?= asset($assetBasePath.'/js/general.js');  ?>"></script>
@endsection

