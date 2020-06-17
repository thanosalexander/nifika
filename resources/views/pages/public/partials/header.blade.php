<?php

/* @var $webPage StartPage */
$startPage = \App\Logic\Template\StartPage::get();

?>

<header>
    <nav class="initialState">


        @include("{$viewBasePath}.sections.mainMenu")

        <span>
            <?php if(request()->route()->getName()==='public.home'): ?>
                <a class="icon" href="https://facebook.com/annaveneti"><img src="<?= asset('public/images/symbols/facebook.svg') ?>" alt="facebook"></a>
                <a class="icon" href="https://instagram.com/annaveneti"><img src="<?= asset('public/images/symbols/instagram.svg') ?>" alt="instagram"></a>
            <?php else: ?>
               <a class="icon" href="https://facebook.com/annaveneti"><img src="<?= asset('public/images/symbols/facebookBlack.svg') ?>" alt="facebook"></a>
                <a class="icon" href="https://instagram.com/annaveneti"><img src="<?= asset('public/images/symbols/instagramBlack.svg') ?>" alt="instagram"></a>
            <?php endif; ?>
        </span>
    </nav>


    <?php if(request()->route()->getName()==='public.home'): ?>
        <img id="logo" src="<?= \App\Logic\App\Assets::whiteLogoUrl()  ?>" alt="annaveneti" />
        <img id="menu" src="<?= asset('public/images/symbols/hamburger.svg') ?>" alt="MENU">
    <?php else: ?>
        <a href="<?= e($startPage->url()); ?>">
            <img id="logo"  src="<?= \App\Logic\App\Assets::logoUrl()  ?>" alt="annaveneti" />
        </a>
        <img id="menu" src="<?= asset('public/images/symbols/hamburgerBlack.svg') ?>" alt="MENU">
    <?php endif; ?>



</header>
