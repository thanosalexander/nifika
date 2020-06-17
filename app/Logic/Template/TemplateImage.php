<?php

namespace App\Logic\Template;

use App\PageImage;
use Intervention\Image\ImageManagerStatic;

/** . */
class TemplateImage {

    const THUMB_NAME_PATTERN = '{{original}}_thumb_{{type}}_{{dimensions}}';
    
    protected $thumbBasePath = 'storage/thumbs/';

    /**
     * @return \static
     */
    public static function _get() {
        $obj = new static();
        return $obj;
    }
    /**
     * 
     * @param PageImage $pageImage
     * @param boolean $resized
     * @param boolean $fit
     * @param int $width
     * @param int $height
     * @return type
     */
    public static function galleryThumbnail(PageImage $pageImage, $resized = true, $fit = true, $width = 300, $height = 300) {
        if (!is_null($pageImage->filePath())) {
            $path = $pageImage->fileServerPath();
            return static::_get()->getThumbnail($path, $width, $height, $resized, $fit);
        }
        return null;
    }

    /**
     * @param PageModelPage $webPage
     * @param boolean $resized
     * @param boolean $fit
     * @param int $width
     * @param int $height
     * @return string|null
     */
    public static function pageThumbnail(PageModelPage $webPage, $resized = true, $fit = true, $width = 300, $height = 200) {
        if ($webPage->hasImage()) {
            $path = $webPage->imageServerPath();
            return static::_get()->getThumbnail($path, $width, $height, $resized, $fit);
        }
        return null;
    }

    protected function getThumbnail($path, $width = 300, $height = 200, $resized = true, $fit = true) {
        $this->initThumbBasePath();
        $thumbNamePattern = static::THUMB_NAME_PATTERN;
        if (!\File::exists($path)) {
            return null;
        }
        $originalImage = ImageManagerStatic::make($path);
        $originalFilename = $originalImage->filename;
        $originalName = $originalImage->basename;
        $isPortrait = $originalImage->width() < $originalImage->height();
//        $originalImage->save(public_path("/storage/thumbs/{$originalName}"));

        $thumbType = [
            ($resized ? 'resized' : null),
            ($fit ? 'fit' : null),
        ];
        $thumbDimensions = "{$width}x{$height}";
        $thumbNameInfo = [
            '{{original}}' => $originalFilename,
            '{{type}}' => implode('_', array_filter([($resized ? 'resized' : null), ($fit ? 'fit' : null)])),
            '{{dimensions}}' => $thumbDimensions,
        ];

//        dump($thumbNamePattern);
        $thumbFileName = str_replace(array_keys($thumbNameInfo), array_values($thumbNameInfo), $thumbNamePattern);
        $thumbName = str_replace($originalFilename, $thumbFileName, $originalName);
//        dump($thumbName);
        $thumbPath = "{$this->thumbBasePath}{$thumbName}";
//        dump($thumbPath);
        $thumbServerPath = public_path($thumbPath);
//        dump($thumbServerPath);
        $thumbUrl = asset($thumbPath);
//        dd($thumbUrl);
//        dd('end');
        if (!\File::exists($thumbServerPath) || static::isObsolete($thumbServerPath, $path)) {
            if ($resized) {
                $originalImage->resize($width, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
            if ($fit) {
                $originalImage->fit($width, $height, function ($constraint) {
                    $constraint->upsize();
                }, 'center');
            }
            $originalImage->save($thumbServerPath);
        }

        return $thumbUrl;
    }
    
    protected function initThumbBasePath() {
        $basePath = $this->thumbBasePath;
        if (!\File::exists($basePath)) {
            \File::makeDirectory($basePath, 493, true);
        }
    }

    public static function isObsolete($file, $originalFile) {
        if (!\File::exists($originalFile) || !\File::exists($file) || (filemtime($originalFile) > filemtime($file))
        ) {
            return true;
        }
    }

}
