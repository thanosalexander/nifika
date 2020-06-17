<?php

use App\Logic\App\MyApp;
use App\Logic\Locales\AdminLocale;
use App\Logic\Locales\AppLocales;
use App\Logic\Locales\ModelLocale;
use App\Setting;
use Carbon\Carbon;

if (! function_exists('ss')) {
    /** Setting getter shortcut.
     * @param int $setting
     * @param string $default
     * @return string */
    function ss($setting, $default=''){
        return Setting::getter($setting, $default); 
    }
}

if (! function_exists('myApp')) {
    /** Get the current admin locale
     * @return MyApp */
    function myApp(){
        return MyApp::_get();
    }
}

if (! function_exists('allow')) {
    /** Get the current admin locale
     * @return boolean */
    function allow(){        
        return forward_static_call_array([Gate::class, 'allows'], [
            func_get_arg(0),
            array_slice(func_get_args(), 1),
        ]);
    }
}

if (! function_exists('ec')) {
    /** Setting getter shortcut.
     * @param string $string
     * @return string */
    function ec($string){
        
        if ($string instanceof Htmlable) {
            return $string->toHtml();
        }

        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8', false);
    }
}

if (! function_exists('monthGenetive')) {
    function monthGenetive($monthNumber, $locale = null){
        $locale = (!is_null($locale) ? $locale : AppLocales::getCurrentLocale());
        switch($locale){
            case 'el': 
                $greekMonths = array('Ιανουαρίου','Φεβρουαρίου','Μαρτίου','Απριλίου','Μαΐου','Ιουνίου','Ιουλίου','Αυγούστου','Σεπτεμβρίου','Οκτωβρίου','Νοεμβρίου','Δεκεμβρίου');
                return (intval($monthNumber) <= 0 ? '' : $greekMonths[$monthNumber-1]);
            default:
                return Carbon::createFromFormat('m', $monthNumber)->formatLocalized('%B');
        }
    }
}

if (! function_exists('formatToLocalizedDate')) {
    function formatToLocalizedDate($locale, $date){
        switch($locale){
            case 'el': 
                //Expected date format yyyy-mm-dd hh:MM:ss

                 $time = strtotime($date);
                 $newformat = date('Y-m-d',$time);
                 return Carbon::createFromTimestamp($time)->formatLocalized('%A')
                         .' '. date('j', strtotime($newformat))
                         .' '.monthGenetive(date('m', strtotime($newformat)), $locale)
                         . ' '. date('Y', strtotime($newformat)); // . ' '. $date;
            default :
                return $date;
        }
    }
}

if (! function_exists('currentModelLocale')) {
    /** Get the current model locale
     * @return string */
    function currentModelLocale(){
        return ModelLocale::detectCurrent();
    }
}

if (! function_exists('currentAdminLocale')) {
    /** Get the current admin locale
     * @return string */
    function currentAdminLocale(){
        return AdminLocale::get()->getCurrent();
    }
}

if (! function_exists('ddAjax')) {
    /** Debug for ajax request
     * @return decimal */
    function ddAjax($var, $die = false){
        echo '('.gettype($var).')';
        if(is_array($var)){
            print_r($var);
        } else if (is_scalar($var)) {
            echo (is_bool($var) ? intval($var) : $var);
        } else if (is_object($var)){
            echo serialize($var);
        } else if (is_null($var)){
            echo null;
        } else {
            echo 'Unexpected variable type!';
        }
        echo "\n";
        if($die){
            header('jhhjh', true, 500);
            die();
        }
    }
}

if (! function_exists('removeAccent')) {
    /** Remove accent from greek accented letters
     * @param  string  $string
     * @return string */
    function removeAccent($string){
        $chars = array(
            'ά'=>'α','έ'=>'ε','ό'=>'ο','ώ'=>'ω',
            'ύ'=>'υ','ϋ'=>'υ','ΰ'=>'υ',
            'ί'=>'ι','ϊ'=>'ι','ΐ'=>'ι','ή'=>'η'
        );
        
       $string = str_replace(array_keys($chars), array_values($chars), $string);
       $string = str_replace(
               array_map('mb_strtoupper', array_keys($chars)), 
               array_map('mb_strtoupper', array_values($chars)), 
               str_replace(array_keys($chars), array_values($chars), $string)
               );
        
        return $string;
    }
}

if (! function_exists('toDecimalDot')) {
    /** Convert a number (string or integer or decimal) to decimal dot format
     * @return decimal */
    function toDecimalDot($number, $decimals = 2)
    {
        $number = str_replace(",", '.', $number);
        if (empty($number) || !is_numeric($number)) {
            $number = 0;
        }
        
        return number_format($number, $decimals, '.', ''); 
    }
}

if (! function_exists('toDecimalComma')) {
    /** Convert a number (string or integer or decimal) to decimal comma format
     * @return decimal */
    function toDecimalComma($number, $decimals = 2){
        $number = str_replace(",", '.', $number);
        if (empty($number) || !is_numeric($number)) {
            $number = 0;
        }
        
        return number_format($number, $decimals, ',', ''); 
    }
}
