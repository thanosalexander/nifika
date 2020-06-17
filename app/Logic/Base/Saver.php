<?php

namespace App\Logic\Base;

use App\User;
use App\Http\Requests\Request;
use \Illuminate\Http\UploadedFile;

/** Used to save model in database. */
abstract class Saver {

    const ACTION_STORE = 'store';
    const ACTION_UPDATE = 'update';
    const ACTION_UPDATE_ORDER = 'updateOrder';
    const ACTION_DELETE = 'delete';

    /** @var Request */
    protected $request;

    /** @var Model */
    protected $model;
    
    /** @var User */
    protected $user;
    
    /** @var string */
    protected $action;

    /** @var string */
    protected $resultMessage;

    /** Get the Saver. 
     * @param Request $request
     * @return \static */
    protected static function initFromRequest($request) {
        $saver = new static();
        $saver->request = $request;
        $saver->setUser($request->user());
        $saver->setActionFromRequest($request);
        $saver->setModelFromRequest();
        return $saver;
    }
    
    abstract public function setModelFromRequest();
    
    /** Get the model. 
     * @return \static */
    public function getModel() {
        return $this->model;
    }
    
    /** Get the request user. 
     * @return \static */
    public function setUser($user) {
        $this->user = $user;
    }
    
    /** Get the pageSaver. 
     * @return \static */
    public function setAction($action) {
        $this->action = $action;
    }
    
    /** Get the pageSaver. 
     * @return \static */
    public function setActionFromRequest($request) {//actions depends on requested route
        $routeAction = array_last(explode('.', $request->route()->getName()));
        switch ($routeAction) {
            case "store":
                $this->setAction(static::ACTION_STORE);
                break;
            case "update":
                $this->setAction(static::ACTION_UPDATE);
                break;
            case "updateOrder":
                $this->setAction(static::ACTION_UPDATE_ORDER);
                break;
            case "destroy":
                $this->setAction(static::ACTION_DELETE);
                break;
            default:
                throw new \Exception("Unknown action!!");
        }
    }
    
    public function getResultMessage() {
        return $this->resultMessage;
    }
    
    public function setResultMessage($message) {
        $this->resultMessage = $message;
    }

    /** Save model's child relations
     * @param string $relation the model's relation name
     * @param array $data array of data to be saved
     * @param array $extraData extra data to be used for some relations */
    public function saveManyRelation($relation, $data, $extraData = []) {
        $records = [];
        foreach (array_values($data) as $order => $record) {
            $recordModelClass = get_class($this->model->{$relation}()->getRelated());
            switch ($this->action) {
                case static::ACTION_STORE:
                    unset($record['id']); //unset id to ensure that record will be cteated
                    $model = $recordModelClass::_get();
                    break;
                case static::ACTION_UPDATE:
                    $model = $recordModelClass::findOrNew($record['id']);
                    if (is_a($model, BaseFileModel::class)) { // model class exndends BaseFileModel
                        $model->emptyFile = (isset($record['deleteFile']) && !empty($record['deleteFile']));
                        if ($model->emptyFile) {
                            $model->setTheFileAttribute(null);
                            $model->setFileInfo();
                        }
                    }
                    break;
            }
            $model->fill($record);
            $records[] = $model;
        }
        $savingRecords = $records;

        //find existing relations that are not presented in sending data from
        //in order to delete them
        $deletingRecords = [];
        $newRecordIds = empty($records) ? [] : array_filter(collect($records)->pluck('id')->toArray());
        foreach ($this->model->$relation as $item) {
            if (!in_array($item->id, $newRecordIds)) {
                $deletingRecords[] = $item;
            }
        }

        //delete relations that are not presented in sending data
        if (count($deletingRecords) > 0) {
            $relationIds = $this->model->$relation->pluck('id')->toArray();
            foreach ($deletingRecords as $item) {
                if (in_array($item->id, $relationIds)) {
                    $item->delete();
                }
            }
        }

        //save relations that are presented in sending data
        $savedRecords = [];
        if (count($savingRecords) > 0) {/* @var $relationEloquent \Illuminate\Database\Eloquent\Relations\MorphMany|\Illuminate\Database\Eloquent\Relations\HasMany */
            $relationEloquent = $this->model->{$relation}();
            foreach ($savingRecords as $record) {
                $relationEloquent->save($record);
                $savedRecords[] = $record;
            }
        }
        //run rest actions
        $this->savedManyRelation($relation, $deletingRecords, $savedRecords);
    }

    /** Run after saving of relations
     * @param type $deleted
     * @param type $saved */
    public function savedManyRelation($relation, $deleted, $saved) {
        //override this method on each custom Saver that needs to run actions after saving its relation
    }

    /** Move uploaded file and return its name.
     * @param \Illuminate\Http\UploadedFile $image
     * @param string $destination destination's basepath
     * @return mixed false|string */
    public static function moveUploadedFile(UploadedFile $image, $destination, $watermarking = true) {
        $savedImages = false;
        $newImageName = rand(1000, 9999) . $image->hashName();
        try {
            $image->move($destination, $newImageName);
            if (file_exists($destination . $newImageName)) {
                $savedImages = $newImageName;
            }
        } catch (\Symfony\Component\HttpFoundation\File\Exception\FileException $e) {
            //just continue
        }
        return $savedImages;
    }

    /** Convert sent data 
     * @param \static $model Description 
     * @param \array $data 
     */
    public static function checkIfDeleteFileSent($model, $data) {
        if(is_a($model, BaseFileModel::class)){
            $model->emptyFile = (!empty($model->getTheFileAttribute()) 
                    && isset($data['deleteFile']) 
                    && !empty($data['deleteFile']));
            if ($model->emptyFile) {
                $model->setTheFileAttribute(null);
                $model->setFileInfo();
            }   
        }
    }

}
