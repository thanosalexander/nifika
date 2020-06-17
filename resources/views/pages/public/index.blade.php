<?php

    /** @var \App\Logic\Template\StartPage $webPage */


?>


@extends("{$layoutBasePath}.default")

@section('htmlId','home')

@section('bodyClasses','index')


@section('content')
    <div id="fixed-full">
        @include('pages.public.partials.header')
        <div class="bullets">
            <a href="#cover"><div id="bcover" class="bullet active"></div></a>
            <a href="#image2"><div id="bimage2" class="bullet"></div></a>
            <a href="#image3"><div id="bimage3" class="bullet"></div></a>
        </div>
        <img id="scrollIndicator" src="<?= asset('public/images/symbols/scroll.svg') ?>" alt="⌄" style="cursor: pointer;">
    </div>


    <div class="slides">
        <div class="slide" id="cover">
            <div id="bigLogoContainer" style="text-align: center; justify-self: center; align-self: end;">
                <h1 id="annaVeneti">
                    ANNA
                    VENETI
                </h1>
            </div>
            <!-- This could be a background image to div#cover (inline) but this looks nicer to me -->
            <img style="object-position: 70%;" src="<?= e($webPage->image()); ?>" alt="bridal-collection-12">
        </div>

        <?php if ($webPage->hasMedia()) : ?>

            <?php if ($webPage->hasImage()): ?>

                <?php /** @var \App\PageImage $image */ ?>
                <?php foreach($webPage->imageGallery() as $index=>$image): ?>

                    <div class="slide" id="image<?= ($index+2) ?>">
                        <img src="<?=  asset($image->filePath()) ?>" alt="bridal-collection-24"/>
                    </div>

                <?php endforeach; ?>

            <?php endif; ?>

        <?php endif; ?>

    </div>
@endsection


@section('beforeBodyEnd')
    @parent
    <script>
        var inPageNavigation = {
            setActiveBullet : function(){
                var currentImage = "#cover";
                document.querySelectorAll(".bullet").forEach(bullet => {bullet.classList.remove("active")})
                if (location.hash != ""){
                    document.getElementById("b"+location.hash.substring(1)).classList.add("active");
                    currentImage = document.querySelector(window.location.hash)
                }
                // check if we are on the last image and remove the down arrow
                if (currentImage.nextElementSibling == null) {
                    document.getElementById("scrollIndicator").style.display = "none";
                } else {
                    document.getElementById("scrollIndicator").style.display = "";
                }
            },
            setDocumentHash : function(){
                var slides = document.querySelectorAll(".slide")
                slides.forEach(slide => {
                    // if we have scrolled close to the start of the next image
                    var diff = document.documentElement.scrollTop - slide.offsetTop;
                    // I have no idea why but if I check diff < 10 instead scrolling breaks
                    // at least in FF
                    if (diff == 0 || diff == 1 ) window.location.hash = "#"+slide.id
                })
                inPageNavigation.setActiveBullet();
            },
            scrollToNextImage : function(){
                if (window.location.hash === "") {
                    var currentImage = document.querySelector("#cover");
                } else {
                    var currentImage = document.querySelector(window.location.hash)
                }
                var nextImage = currentImage.nextElementSibling;
                if (nextImage) window.location.hash = "#"+nextImage.id;
            }
        }
        // this handles the active bullet color
        if (location.hash !== "") inPageNavigation.setActiveBullet();
        window.addEventListener("hashchange", inPageNavigation.setActiveBullet);
        window.addEventListener("scroll", inPageNavigation.setDocumentHash);
        document.getElementById("scrollIndicator").addEventListener("click", inPageNavigation.scrollToNextImage);
        // the down arrow does nothing if js is disabled so we set it's pointer here
        document.getElementById("scrollIndicator").style.cursor = "pointer";

    </script>
@endsection
