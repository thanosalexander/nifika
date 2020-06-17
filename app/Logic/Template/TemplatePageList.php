<?php

namespace App\Logic\Template;

use Intervention\Image\ImageManagerStatic;

/** . */
class TemplatePageList {

    /**
     * @return \static
     */
    public static function _get() {
        $obj = new static();
        return $obj;
    }
    
    public static function getPageListView(Page $page) {
         if(!is_nuyll($pageImage->filePath())) {
            $path = $pageImage->fileServerPath();
//            $url = $webPage->image();
//            dump($path);
//            dump($url);
            return static::_get()->getPageThumbnail($path, 300, 300);
        }
        
    }
    
    public static function thumbnail(PageModelPage $webPage) {
        if($webPage->hasImage()) {
            $path = $webPage->imageServerPath();
//            $url = $webPage->image();
//            dump($path);
//            dump($url);
            return static::_get()->getPageThumbnail($path);
        }
        return '';
    }
    
    public function getPageThumbnail($path, $width = 300, $height = 200) {
        $thumbPattern = "{{original}}_{{type}}_300x200";
        
        $originalImage = ImageManagerStatic::make($path);
        $originalFilename = $originalImage->filename;
        $originalName = $originalImage->basename;
        $isPortrait = $originalImage->width() < $originalImage->height();
//        $originalImage->save(public_path("/storage/thumbs/{$originalName}"));
        
        $thumbFilenamePattern = str_replace('{{original}}', $originalFilename, $thumbPattern);
        $thumbNamePattern = str_replace($originalFilename, $thumbFilenamePattern, $originalName);
//        dump($thumbNamePattern);
        
        $thumbNameWithRC = str_replace('{{type}}', 'fit', $thumbNamePattern);
//        dump($thumbNameWithRC);
        $thumbPathWithRC = "/storage/thumbs/{$thumbNameWithRC}";
//        dump($thumbPathWithRC);
        $thumbServerPathWithRC = public_path($thumbPathWithRC);
//        dump($thumbServerPathWithRC);
        $thumbUrlWithRC = asset($thumbPathWithRC);
//        dump($thumbUrlWithRC);
        if(!\File::exists($thumbServerPathWithRC)) {
            $originalImage
                    ->resize($width, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->fit($width, $height, function ($constraint) {
                        $constraint->upsize();
                    }, 'center')
                    ->save($thumbServerPathWithRC);
        }
        
        return $thumbUrlWithRC;
    }
}
