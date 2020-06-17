<?php

use App\Logic\App\Assets;
use App\Logic\Template\StartPage;
use Jenssegers\Agent\Agent;

$agent = new Agent();


/* @var $webPage StartPage */
$startPage = StartPage::get();

?>
@section('header')
<!-- Page Header-->
<div id="banner">
    <a href="<?= e($startPage->url()); ?>"><img src="<?= Assets::logoUrl();  ?>"/></a>
</div>
<nav class="navbar navbar-expand-lg bg-dark navbar-dark" id="navbar">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
        @include("{$viewBasePath}.sections.mainMenu")
    </div>
</nav>
@endsection
