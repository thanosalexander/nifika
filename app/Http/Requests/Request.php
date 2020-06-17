<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class Request extends FormRequest
{
    
    public function messages() {
        $messages = [
            '*.required' => trans('request.validation._required'),
            '*.present' => trans('request.validation._present'),
            '*.email' => trans('request.validation._email'),
            '*.url' => trans('request.validation._url'),
            '*.image' => trans('request.validation._image'),
            '*.mimes' => trans('request.validation._mimes'),
            '*.between' => trans('request.validation._between'),
            '*.integer' => trans('request.validation._integer'),
            '*.numeric' => trans('request.validation._numeric'),
            '*.min' => trans('request.validation._min'),
            '*.max' => trans('request.validation._max'),
            '*.same' => trans('request.validation._same'),
            '*.phone.regex' => trans('request.validation._phone'), 
        ];

        return $messages;
    }
    
    /** Convert rules to be compatible with js validator.
     * Specifically, convert wildcard rules from dot format to js format 
     * etc field.*.column => field[*][column]
     * @param type $rules */
    public function convertRulesForJsValidator($rules){
        //find wildcard rules
        $wildcardRules = preg_grep("/\.\*/", array_keys($rules));
        foreach ($wildcardRules as $wildcardRule){
            //find basename of the rule etc. field.*.columnName -> field
            preg_match("/^(.*)\.\*/", $wildcardRule, $match);
            $dataBaseName = !empty($match[1]) ? $match[1] : null;
            //find columnName of the rule etc. field.*.columnName -> columnName
            preg_match("/^".$dataBaseName."\.\*\.(.*)/", $wildcardRule, $match);
            $dataColumnName = !empty($match[1]) ? $match[1] : null;
            $newRuleNameParts = !empty($dataColumnName) ? [$dataBaseName, '[*]', "[{$dataColumnName}]"] : [$dataBaseName, '[*]'];
            $rules[implode('', $newRuleNameParts)] = $rules[$wildcardRule];
            unset($rules[$wildcardRule]);
        }
        
        //convert "field.key.column" --> "field[key][column]"
//        foreach ($rules as $fieldName => $fieldRules){
//            $temp = str_replace('.', '][', str_replace_first('.', '[', $fieldName));
//            if($fieldName !== $temp){
//                $rules[$temp.']'] = $fieldRules;
//                unset($rules[$fieldName]);
//            }
//        }
        
        return $rules;
    }
}
