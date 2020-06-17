<?php
namespace App\Helpers;

/** Holds help functions for strings. */
class StringHelper{
    
    /** Remove everything that is not a number.
     * @param string $input The input string.
     * @return string */
    public static function keepOnlyNumbers($input){
        return preg_replace('/[^0-9]+/', '', $input);
    }
    /** Remove everything that is not a number, letteror dot.
     * @param string $input The input string.
     * @return string */
    public static function keepOnlyNumbersLettersAndDots($input){
        return preg_replace('/[^0-9A-z.]+/', '', $input);
    }
    /** Prepare url by replacing  ' ' with '%20' .
     * @param string $url
     * @return string */
    public static function prepareUrl($url){
        return str_replace(' ', '%20', $url);
    }
    /** Adds http to url if missing.
     * @param string $url */
    public static function addHttp($url){
        if (   stripos($url, "http://") === false 
            && stripos($url, "https://") === false
        ){
            $url = "http://" . $url;
        }
        return $url;
    }
    /** Check if the given url is valid...
     * @param string $url
     * @return boolean */
    public static function isUrl($url){
        return (filter_var($url, FILTER_VALIDATE_URL) && substr_count($url, '.')>0);
    }
    /** Sanitize html to only keep basic styling tags.
     * @param string $input
     * @return string */
    public static function allowSimpleTags($input, $additionalTags = ''){
        return strip_tags($input,'<br><p><strong><ul><ol><li><em>'.$additionalTags);
    }
    /** Sanitize html to only keep styling tags used by our editor.
     * @param string $input
     * @return string */
    public static function allowSimpleTagsEditor($input, $additionalTags = ''){
        return strip_tags($input,'<span><br><p><strong><ul><ol><li><em>'.$additionalTags);
    }
    /** Replace variables in text with data.
     * @param string $text
     * @param array $variables
     * @return string */
    public static function setTextVariables($text, $variables = array()){
        foreach($variables as $variable => $value){
            $text = str_replace($variable, $value, $text);
        }
        return $text;
    }
    
}