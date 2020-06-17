<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Setting;

class SettingsRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $settingGroup = $this->route('settingGroup');
        $routeAction = array_last(explode('.', $this->route()->getName()));
        $priceRule = 'required|regex:/^\d+(,\d{1,2})?$/';
        
        switch ($settingGroup) {
            case Setting::GROUP_GENERAL:
                $rules = [
                ];
                break;
            case Setting::GROUP_START_PAGE:
                $rules = [
                ];
                break;
            case Setting::GROUP_CONTACT_PAGE:
                $rules = [
                    'settings.'.Setting::SS_CONTACT_PAGE_RECEIPT_EMAIL => 'email',
                ];
                break;
            case Setting::GROUP_ADVANCED:
                $rules = [
                ];
                break;
            default: 
                $rules = [
                ];
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
    
        $settingGroup = $this->route('settingGroup');        
        $settingGroupMessages = [];
        //set entity messages
        switch ($settingGroup) {
            case Setting::GROUP_GENERAL:
                break;
        }
        
        //merge with entity messages
        $messages = array_merge($settingGroupMessages, $messages);
        
        return $messages;
    }
    
}
