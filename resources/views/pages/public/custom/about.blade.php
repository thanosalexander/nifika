<?php

use App\Logic\Template\PageModelPage;
use App\Logic\Template\StartPage;
use App\Page;

/* @var $webPage StartPage */
?>

@extends("{$layoutBasePath}.default")

@section('contentTop')
<div id="slider" style="">
    <?php if ($webPage->hasMedia()) : ?>
    <?php if ($webPage->hasVideo()): ?>
    <?php elseif ($webPage->hasImage()): ?>
    <img src="<?= e($webPage->image()); ?>"/>
    <?php else: ?>
    <?php endif; ?>
    <?php endif; ?>
</div>
@endsection

@section('content')
<div class="wrapper">
    <div id="moreinfo">
        <img src="<?= e(asset($assetBasePath.'/images/custom.png')) ?>"/>
        <h1 style="font-family: Open Sans;font-weight: 600; margin-bottom: 10px;"><?= e($webPage->title()); ?></h1>
        <p style="font-family: Open Sans;font-weight: 500;letter-spacing: 1px;clear:both;position:relative;left:0px;top:-60px;padding-right:30px;">
            <?= e($webPage->description()); ?></p>
    </div>
    <div id="about_us">
        <div class="img-hide" ><img src="<?= e(asset($assetBasePath.'/images/custom_2.png')) ?>"/></div>
        <p style="padding-top:25px;">Pilos was founded under the strategic scope of offering independent services and inspiring solutions that will make a genuine difference to clients and society.</p>
        <p style="padding-top:25px;">Our core services can be divided into two categories:</p>
    </div>
    <div id="about_us_first" class="article_section rightImage">
        <div class="article_section_title">
            <img class="titleIcon" src="<?= e(asset($assetBasePath.'/images/title1.png')) ?>"/>
            <h3 class="titleText">Construction consulting <br>& Project managment</h3>
        </div>
        <div class="article_section_image"><img src="<?= e(asset($assetBasePath.'/images/constr.jpg')) ?>"/></div>
        <div class="article_section_text">
            <p>
                Our unique combination of technical excellence and extensive experience guarantees enduring structures and resource-efficient solutions coming from a group of expert partners including Architects, Civil Engineers, Mechanical Engineers, Interior and Exterior designers, Topographic Surveyors and skilled Technical Staff. We develop and propose integrated solutions that will help streamline processes and achieve a sustainable result through the following services:
            </p>
            <br>
            <ul>
                <li>Advisory services and consulting</li>
                <li>Concept and design engineering</li>
                <li>Project and construction management</li>
                <li>Interior design</li>
                <li>Regulatory compliance support</li>
                <li>Renewable energy sources - Sustainable energy systems</li>
            </ul>
        </div>
        <div style="clear: both;"></div>
    </div>
    <hr style="margin:50px 0px;border-bottom: 1px black;">
    
    <div id="about_us_snd" class="article_section rightText">
        <div class="article_section_title">
            <img class="titleIcon" src="<?= e(asset($assetBasePath.'/images/title2.png')) ?>"/>
            <h3 class="titleText">Premium cosmetics development & Strategic consultation</h3>
        </div>
        <div class="article_section_image"><img src="<?= e(asset($assetBasePath.'/images/About_US_Cometics.jpg')) ?>"/></div>
        <div class="article_section_text">
            <p>
                Pilos is where innovation, quality, reliability and service meet beauty. Our superiority derives from the use of cutting edge technology, the development of unique formulas and our commitment to excellence. We can assure rapid response, flexibility and full service cooperation in order to develop high-performance formulas and brands based on scientific expertise and innovative strategic thinking through the following services:
            </p>
            <br>
            <ul>
                <li><?= trans('R&D Lab services') ?></li>
                <li>Regulatory compliance</li>
                <li>Quality management</li>
                <li>Pilot production</li>
                <li>Production unit setup Turnkey</li>
                <li>Fragrance Laboratory</li>
                <li>Turnkey service supervision</li>
                <li>Strategic marketing and branding consultation</li>
                <li>Graphic design applications</li>
            </ul>
        </div>
        <div style="clear: both;"></div>
    </div>
<!--    <div id="about_us_snd" class="about_us_service_section">
        <img class="img-hide" src="<?= e(asset($assetBasePath.'/images/About_US_Cometics.jpg')) ?>">
        <div id="about_us_sndd">
            <div id="sndd_title">
                <img style="" src="<?= e(asset($assetBasePath.'/images/title2.png')) ?>"><h3 style=" color:#4D4D4D; letter-spacing: 1px;line-height: 40px;">Premium cosmetics development & Strategic consultation</h3>
            </div>
            <br>
            <p>Pilos is where innovation, quality, reliability and service meet beauty. Our superiority derives from the use of cutting edge technology, the development of unique formulas and our commitment to excellence. We can assure rapid response, flexibility and full service cooperation in order to develop high-performance formulas and brands based on scientific expertise and innovative strategic thinking through the following services:<br><b>
                    <ul>
                        <li><?= trans('R&D Lab services') ?></li>
                        <li>Regulatory compliance</li>
                        <li>Quality management</li>
                        <li>Pilot production</li>
                        <li>Production unit setup Turnkey</li>
                        <li>Fragrance Laboratory</li>
                        <li>Turnkey service supervision</li>
                        <li>Strategic marketing and branding consultation</li>
                        <li>Graphic design applications</li>
                    </ul>
                </b>
        </div>
    </div>-->
    <div id="about_us_trdd">
        <div id="about_us_trdd_one">
            <h1>Our Mission...</h1>
            <p>Be a high-end organization that provides reliable and professional solutions through constant improvement of our core competencies in construction and cosmetics development as well as through the delivery of unequalled service to our clients all over the world.</p>
        </div>
        <div id="about_us_trdd_sndd">
            <img src="<?= e(asset($assetBasePath.'/images/flag.png')) ?>"/>
        </div>
        <div id="about_us_trdd_trdd">
            <h1>Our Values...</h1>
            <p>We are driven by our passion, integrity, professionalism and our innovative spirit, as we continually strive to fulfill our mission. These values guide us in all that we do and are the backbone upon which Pilos is built.</p>
        </div>
    </div>
</div>
@endsection