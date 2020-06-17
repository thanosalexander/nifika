<?php

namespace App\Logic\App;

use App\Helpers\StringHelper;
use Illuminate\Support\Facades\Storage;

/** This encapsulates storage actions and should have no knowledge 
 * of other business like myApp and ids. */
class Filesystem {
    
    /** Get the base url for assets.
     * This is needed to know where to pull the file from
     * depending on filesystem used.
     * @return type */
    public static function baseUrl(){
        if( strtolower( static::disk() )==='s3'){
            if( !empty( config('filesystems.cloudfrontUrl') ) ){ //cloudfront url
                $res = config('filesystems.cloudfrontUrl');
            }else{ //s3 url
                $res = 'https://s3-us-west-2.amazonaws.com/'.config('filesystems.disks.s3.bucket');
            }
        }else{
            $res = url('storage');
        }
        $res .= '/';
        return $res;
    }
    /** Based on the given filename, create a filename that is valid for 
     * all filesystems and contains random part.
     * @param string $filename
     * @return string */
    public static function getValidFilename($filename){
        $res = StringHelper::keepOnlyNumbersLettersAndDots($filename);
        $res = substr(md5(rand(100000,999999)), 6, 4).'-'.$res;
        return $res;
    }
    /** Save a file to filesystem.
     * @param string $folder The folder to save to.
     * @param string $filename The name to give to the file.
     * @param string $pathToCurrentFile The path of the file to upload.
     * @return bool */
    public static function uploadTenantFile($folder, $filename, $pathToCurrentFile){
        $res = Storage::put( $folder.$filename, file_get_contents($pathToCurrentFile) );
        return $res;
    }
    /** Check if a file exists.
     * @param string $folder The folder the file resides in.
     * @param string $filename The name of the file.
     * @return bool */
    public static function existsTenantFile($folder, $filename){
        $res = Storage::exists($folder.$filename);
        return $res;
    }
    /** Remove a file from filesystem.
     * @param string $folder The folder the file resides in.
     * @param string $filename The name of the file.
     * @return bool */
    public static function removeTenantFile($folder, $filename){
        $res = Storage::delete($folder.$filename);
        return $res;
    }
    /** Shorthand for config('filesystems.default')
     * @return string */
    public static function disk(){
        return config('filesystems.default');
    }
    
}
