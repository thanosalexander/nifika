<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Logic\App\EntityManager;
use App\Logic\Pages\PageSaver;
use App\Page;
use App\PageImage;

class AdminEntityRequest extends Request {

    /** Determine if the user is authorized to make this request.
     * @return bool */
    public function authorize() {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $entity = $this->route('entity');
        $routeAction = array_last(explode('.', $this->route()->getName()));

        switch ($entity) {
            case EntityManager::PAGE:
                $rules = [
                    'type' => 'required',
                    'title' => 'required',
                    'images.*.'.PageImage::FILE_ATTRIBUTE_NAME => 'mimes:jpeg,jpg,png|max:5000', //if it is changed max it should update validation message
                ];
                switch ($routeAction) {
                    case PageSaver::ACTION_UPDATE:
                        unset($rules['type']);
                        break;
                    case PageSaver::ACTION_UPDATE_ORDER:
                        $rules = [];
                        break;
                    default :
                        break;
                }
                
                break;
            case EntityManager::ARTICLE:
                $rules = [
                    'title' => 'required',
                    Page::FILE_ATTRIBUTE_NAME => 'mimes:jpeg,jpg,png|max:5000', //if it is changed max it should update validation message
                    'images.*.'.PageImage::FILE_ATTRIBUTE_NAME => 'mimes:jpeg,jpg,png|max:5000', //if it is changed max it should update validation message

                ];
                switch ($routeAction) {
                    case "edit":
                    case PageSaver::ACTION_UPDATE:
                        break;
                    default :
                        break;
                }
                
                break;
        }
        
        if($routeAction == 'destroy'){
            $rules = [];
        }
        
        return $rules;
    }
    public function messages() {
        //merge with parent messages
        $messages = array_merge([
        ], parent::messages());
    
        $entity = $this->route('entity');        
        $entityMessages = [];
        //set entity messages
        switch ($entity) {
            case EntityManager::PAGE:
                $entityMessages = [
                    '*.required_if' => trans('request.validation._required'),
                ];
        }
        
        //merge with entity messages
        $messages = array_merge($entityMessages, $messages);
        
        return $messages;
    }

}
