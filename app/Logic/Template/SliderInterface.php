<?php

namespace App\Logic\Template;

/** Used from template classes that have slider.
 * @author Patroklos */
interface SliderInterface {
    
    /** Get images for slider.
     * @return array */
    public function sliderImages();
    
    /** Whether the slider displays an image description.
     * @return boolean */
    public function sliderDisplaysDescription();
    
    /** The slider max height setting.
     * @return string */
    public function sliderMaxHeight();
    
    /** The slider max width setting.
     * @return string */
    public function sliderWidth();
    
    /** The fit setting.
     * @return string */
    public function sliderFit();
}
