<?php

namespace App\Logic\Base;

use App\Helpers\StringHelper;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

trait BaseFileModelTrait
{
    
    public function debug(){
        dump($this);
        dump('isFileProtected:----' . $this->isFileProtected());
        dump('fileRootPath:-------' . $this->fileRootPath());
        dump('fileBasePath:-------' . $this->fileBasePath());
        dump('filePath:-----------' . $this->filePath());
        dump('fileServerBasePath:-' . $this->fileServerBasePath());
        dump('fileServerPath:-----' . $this->fileServerPath());
        dump('fileExists:---------' . $this->fileExists());
        dd('dd');
    }
    
    /** If it is true, it means that fileAttribute is empty(original value is not empty).
     * When it is triggered update action of the model, the file attribute will be saved as null and not as original value.
     * After update the property turned to false
     *  @var boolean */
    public $emptyFile = false;
    
    /** Get value of file attribute with name 'static::FILE_ATTRIBUTE_NAME' */
    public function getTheFileAttribute(){
        return $this->{static::FILE_ATTRIBUTE_NAME}; 
    }
    
    /** Set value of file attribute with name 'static::FILE_ATTRIBUTE_NAME' */
    public function setTheFileAttribute($value){
        $this->{static::FILE_ATTRIBUTE_NAME} = $value;
    }
    
    /**  */
    protected static function publicFileServerPath($path = ''){
        return public_path(static::PUBLIC_BASE_PATH.$path). (empty(static::PUBLIC_BASE_PATH.$path) ? '/' : '');
    }
    
    /**  */
    protected static function protectedFileServerPath($path = ''){
        return storage_path(static::PROTECTED_BASE_PATH.$path). (empty(static::PROTECTED_BASE_PATH.$path) ? '/' : '');
    }
    
    /** */
    protected function fileRootPath(){
        return $this->isFileProtected() ? static::PROTECTED_BASE_PATH : static::PUBLIC_BASE_PATH;
    }
    
    /** */
    protected function fileBasePath(){
        return static::FILES_PATH; 
    }
    
    /** */
    protected function fileServerBasePath(){
        return $this->isFileProtected() ? static::protectedFileServerPath($this->fileBasePath()) : static::publicFileServerPath($this->fileBasePath()); 
    }
    
    /**  */
    protected function isFileProtected(){
        return false; 
    }
    
    /** */
    public function isFileRequired(){
        return static::FILE_ATTRIBUTE_REQUIRED;
    }
    
    /** Indicates if uploading file should be resized before be uploaded to server for this instance.
     * By default is true. */
    public function shouldUploadFileBeResized(){
        return true;
    }
    
    /** Get image url ex: http://www.asd.com/images/filename.jpg
     * @return string|null */
    public function filePath(){
        return $this->isFileProtected() ? $this->fileProtectedPath() : $this->filePublicPath();
    }
    
    /**  */
    protected function filePublicPath(){
        $fileColumn = static::FILE_ATTRIBUTE_NAME;
        return !empty($this->$fileColumn) ? asset($this->fileRootPath().$this->fileBasePath().$this->$fileColumn) : null;
    }
    
    /**  */
    protected function fileProtectedPath(){
        $fileColumn = static::FILE_ATTRIBUTE_NAME;
        return !empty($this->$fileColumn) ? $this->fileRootPath(). $this->id.'/'.$this->fileBasePath().$this->$fileColumn : null;
    }
    
    /** */
    public function fileServerPath(){
        $fileColumn = static::FILE_ATTRIBUTE_NAME;
        return !empty($this->$fileColumn) ? $this->fileServerBasePath().$this->$fileColumn : null;
    }
    
    /** Check if the file exists on storage
     * !! DO NOT USE THIS ON PAGE LOADS cause it is slow since the
     * image is on another machine. 
     * @return boolean */
    public function fileExists(){
        $res = false;
        $fileColumn = static::FILE_ATTRIBUTE_NAME;
        if(!empty($this->$fileColumn)){
            $res = \File::exists($this->fileServerPath());
        }
        return $res;
    }
    
    /** */
    public static function canShowProtectedFile($path){
        return auth()->check();
    }
    
    public function isFileDirty(){
        $fileColumn = static::FILE_ATTRIBUTE_NAME;
        $originalAttributes = $this->getOriginal();
        if(!empty($this->$fileColumn) && 
            ($this->$fileColumn instanceof UploadedFile || $this->$fileColumn != $originalAttributes[$fileColumn]) 
        ){//current value is not empty AND is uploaded file OR different from original value
            return true;
        }elseif(empty($this->$fileColumn) && !$this->isFileRequired() && !empty($originalAttributes[$fileColumn])
        ){//current value is empty AND file is optionnal AND original value is not empty
            return true;
        }
        return false;
    }
    
    /**
     * Extends parent boot
     * Define callback for 'deleted' event
     */
    public static function boot(){
        parent::boot();
        //set callback function for 'creating' event that is triggered before db creating
        static::creating(function($item){
            $modelClass = get_class($item);
            $fileColumn = $modelClass::FILE_ATTRIBUTE_NAME;
            //if fileAttribute is UploadedFile upload file and assign the name to fileAttribute attribute 
            if($item->$fileColumn instanceof UploadedFile){
                $item->$fileColumn = $item->uploadFile();
                //request file`s info
                $item->setFileInfo($item->requestFileInfo());
            }
            
            return $item;
        });
        
        //set callback function for 'updating' event that is triggered before db updating
        static::updating(function($item){
            $modelClass = get_class($item);
            $fileColumn = $modelClass::FILE_ATTRIBUTE_NAME;
            //if fileAttribute is UploadedFile upload file and assign the name to fileAttribute attribute 
            if($item->$fileColumn instanceof UploadedFile){
                $item->$fileColumn = $item->uploadFile();
                //request file`s info
                $item->setFileInfo($item->requestFileInfo());
            }elseif(empty($item->$fileColumn)){ //if fileattribute is null
                if(!$item->emptyFile && $item->isFileRequired()){ //if 'emptyFile' flag set false and file is required
                    // it means that file was not changed and then set the previous saved value
                    $originalAttributes = $item->getOriginal();
                    $item->$fileColumn = $originalAttributes[$fileColumn];
                }
            }
            return $item;
        });
        
        //set callback function for 'updating' event that is triggered before db updating
        static::updated(function($item){
            $item->emptyFile = false;
            $modelClass = get_class($item);
            $fileColumn = $modelClass::FILE_ATTRIBUTE_NAME;
            //if fileAttribute is UploadedFile upload file and assign the name to fileAttribute attribute 
            $originalAttributes = $item->getOriginal();
            $originalFilename = $originalAttributes[$fileColumn];
            $res = true;
            if( !empty($originalFilename) && $originalFilename != $item->$fileColumn){
                $path = $item->fileServerBasePath().$originalFilename;
                $res = $modelClass::deleteFileFromServer($path);
            }
            return $res;
        });

        //set callback function for 'deleted' event that is triggered after db deletion
        static::deleted(function($item){
            $modelClass = get_class($item);
            $res = true;
            if(! empty($item->filePath()) ){
                $res = $modelClass::deleteFileFromServer($item->fileServerPath());
            }
            return $res;
        });
    }
    
    /**
     * @param string $filePath
     * @return bool */
    public static function deleteFileFromServer($filePath){
        $res = true;
        if(!empty($filePath)){
            $res = (\File::isFile($filePath) && \File::delete($filePath));
        }
        return $res;
    }
    
    /** Upload file using Storage functions.
     * @return string The name of the new file. */
    protected function uploadFile(){
        $uploadingFile = $this->getTheFileAttribute();
        $savedFilename = false;
        if($uploadingFile instanceof UploadedFile){
//            $tempFileName = $resize ? static::resizeImage($file, $name) : $file->getPathname();
            $savingFileBasePath = $this->fileServerBasePath();
            $savingFilename = static::getValidFilename(
                    static::convertUploadedFileName($uploadingFile)
            );

            $savingFilePath = $savingFileBasePath.$savingFilename;
            try{
                if(substr($uploadingFile->getMimeType(), 0, 5) == 'image') {
                    // this is an image
                    $img = \Image::make($uploadingFile->getRealpath());
                    $img->orientate();
                    $img->save();
                }
                
                $uploadingFile->move($savingFileBasePath, $savingFilename);
                if(\File::exists($savingFilePath)){
                    $savedFilename = $savingFilename;
                }
            }catch(FileException $e){
                //just continue
            }
        }
        
        return $savedFilename;
    }

    /** Convert original filename of uploaded file to valid filename. Specifically,
     * - Convert filename's extension to real file extension.<br>
     * - Convert file name's non latin chars to latin and keep only numbers and '-'.
     * @param UploadedFile $file
     * @return string. */
    protected static function convertUploadedFileName(UploadedFile $file){
        //get original filename ex. "my name_ειναι ^frd343.JPG"
        $originalName = $file->getClientOriginalName();
        //get original filename's extension ex. ".JPG"
        $originalExtension = $file->getClientOriginalExtension();
        //get real filename's extension reading file's mimetype ex. "jpeg"
        $realExtension = $file->guessExtension();
        //get name of file without extension "my name_ειναι ^frd343"
        $originalNameWithoutExtension = preg_replace('/\.'.$originalExtension.'$/', '', $originalName);
        //convert to valid name
        $validName = Str::slug($originalNameWithoutExtension);
        //create the full filename
        $filename = implode('.', [$validName, $realExtension]);
        
        return $filename;
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
    
    /** Used to resize the uploaded file.
     * @param UploadedFile $file
     * @param string $name
     * @return string */
    protected static function resizeImage(UploadedFile $file, $name){
//        $image = \Intervention\Image\ImageManagerStatic::make($file);
//        $image->resize( 1200, 648, function(\Intervention\Image\Constraint $constraint){
//                                       $constraint->aspectRatio();
//                                       $constraint->upsize();
//                                   }
//                    );
//        $filename = storage_path('temp/'.$name);
//        $image->save( $filename );
//        return $filename;
    }

    /** Set fileInfo attribute
     * @param array|string|null $fileInfo as string should be accepted only a json encoded array */
    public function setFileInfo($fileInfo = null) {
        if (is_array($fileInfo)) { //is array
            $this->fileInfo = json_encode($fileInfo);
        } else if (is_array(json_decode($fileInfo, true))) { //is json encoded array
            $this->fileInfo = $fileInfo;
        } else { //is not valid value
            $this->fileInfo = null;
        }
    }

    /** Get fileInfo data as array or null
     * @return array|null */
    public function getFileInfo() {
        $fileInfo = json_decode($this->fileInfo, true);
        if (!is_array($fileInfo)) { // is not array
            $fileInfo = null;
        }
        return $fileInfo;
    }

    /** Request the fileinfo (using getimagesize) of the file
     * @return array|null the results of the successful results of getimagesize or null */
    public function requestFileInfo() {
        $fileInfo = null;
        if (!empty($this->filepath())) {
            try {
                $fileInfo = getimagesize($this->filepath());
                if (!is_array($fileInfo)) { //fail results
                    $fileInfo = null;
                }
            } catch (\Exception $e) { //if file does not exists on disk
                $fileInfo = null;
            }
        }
        return $fileInfo;
    }

}
