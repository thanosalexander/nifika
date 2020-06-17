<?php
namespace App\Http\Requests;

use App\Http\Requests\Request;

class RecaptchaFormRequest extends Request {
    
    public function authorize() {
        return true;
    }
    /** Get the validation rules that apply to the request.
     * @return array */
    public function rules() {

        $rules = [
        ];

        return $rules;
    }
    
    /** Get custom messages for validator errors.
     * @return array */
    public function messages() {
        //merge with parent messages
        $messages = array_merge([
            //
        ], parent::messages());
        
        return $messages;
    }

}