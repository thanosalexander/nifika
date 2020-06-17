<?php

namespace App\Logic\Base;

abstract class BaseFileModel extends BaseTranslatableModel
{
    use BaseFileModelTrait;
    
    const FILES_PATH = 'files/files/';
    const PUBLIC_BASE_PATH = '';
    const PROTECTED_BASE_PATH = 'media/';
    
    const FILE_ATTRIBUTE_NAME = 'filename';
    const FILE_ATTRIBUTE_REQUIRED = TRUE;
    const FILE_WATERMARKING = TRUE;
    
}
