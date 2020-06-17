<?php

namespace App\Logic\Settings;

use App\Setting;
use App\Http\Controllers\AdminController;

/** Used to handle printing of settings. */
class SettingsAdministration {
    
    /** Get the setting field view to administrate a setting.
     * @param int $settingId
     * @param string $label
     * @param string $value
     * @return \Illuminate\View\View */
    public static function getSettingField($settingId, $label = null, $value = null){
        $settingType = Setting::getSettingType($settingId);
        switch($settingId){
//            case Setting::SS_FACEBOOK_RSS_ALLOWED_CATEGORIES:
//                return view(myApp()->getConfig('adminViewBasePath').'.settings.form.custom.facebookRssCategories');
        }
        $value = ($value === null ? ss($settingId): $value);
        switch($settingType){
            case Setting::EDITTYPE_CHECKBOX:
                return static::getCheckEdit($settingId, $label, $value);
            case Setting::EDITTYPE_TEXT:
                return static::getTextEdit($settingId, $label, $value);
            case Setting::EDITTYPE_TEXT_PRICE:
                return static::getTextPriceEdit($settingId, $label, $value);
            case Setting::EDITTYPE_TEXT_NUMBER:
                return static::getTextNumberEdit($settingId, $label, $value);
            case Setting::EDITTYPE_TEXTAREA:
                return static::getTextareaEdit($settingId, $label, $value);
            case Setting::EDITTYPE_SELECT:
                
                break;
            case Setting::EDITTYPE_SELECT_MULTIPLE:
                
                break;
            case Setting::EDITTYPE_EDITOR:
                return static::getTextEditor($settingId, $label, $value);
            case Setting::EDITTYPE_COLOR:
                return static::getColorEdit($settingId, $label, $value);
            default:
                
                break;
        }
        
        return "$label - $settingId";
    }
    
    /** Get the logo image field view to administrate it.
     * @return \Illuminate\View\View */
    public static function getLogoImageField(){
        return view(myApp()->getConfig('adminViewBasePath').'.settings.form.custom.logoImage');
    }
    
    /** Get the background image field view to administrate it.
     * @return \Illuminate\View\View */
    public static function getBackgroundImageField(){
        return view(myApp()->getConfig('adminViewBasePath').'.settings.form.custom.backgroundImage');
    }
    
    /** Get the catalog file field view to administrate it.
     * @return \Illuminate\View\View */
    public static function getCatalogFileField(){
        return view(myApp()->getConfig('adminViewBasePath').'.settings.form.custom.catalogFile');
    }
    
    /** Get the checkbox setting field view to administrate a setting.
     * @param int $settingId
     * @param string $label
     * @param string $isChecked
     * @return \Illuminate\View\View */
    protected static function getCheckEdit($settingId, $label, $isChecked = null){
        $name = 'settings['.$settingId.']';
        $isChecked = ($isChecked !== null ? $isChecked : ss($settingId));
        $view = view(myApp()->getConfig('adminViewBasePath').'.partials.form.create.checkbox', 
                [   
                    'label' => $label,
                    'name' => $name,
                    'labelWrap' => '',
                    'isChecked'=> ($isChecked ? true : false),
                ]);
        return $view;
    }
    
    /** Get the text setting field view to administrate a setting.
     * @param int $settingId
     * @param string $label
     * @param string $value
     * @param \Illuminate\View\View */
    protected static function getTextEdit($settingId, $label, $value = null){
        $name = 'settings['.$settingId.']';
        return view(myApp()->getConfig('adminViewBasePath').'.settings.form.text', 
                ['name' => $name, 'label' => $label, 'value' => $value]);
    }
    
    /** Get the price setting field view to administrate a setting.
     * @param int $settingId
     * @param string $label
     * @param string $value
     * @param \Illuminate\View\View */
    protected static function getTextPriceEdit($settingId, $label, $value = null){
        $name = 'settings['.$settingId.']';
        $textValue = ($value === null ? ss($settingId): $value);
        return view(myApp()->getConfig('adminViewBasePath').'.settings.form.number', 
                ['name' => $name, 'label' => $label,
                    'value' => toDecimalComma(empty($textValue) ? 0 : $value)]);
    }
    
    /** Get the number setting field view to administrate a setting.
     * @param int $settingId
     * @param string $label
     * @param string $value
     * @param \Illuminate\View\View */
    protected static function getTextNumberEdit($settingId, $label, $value = null){
        $name = 'settings['.$settingId.']';
        $textValue = $value === null ? ss($settingId): $value;
        return view(myApp()->getConfig('adminViewBasePath').'.settings.form.number', 
                ['name' => $name, 'label' => $label,
                    'value' => (empty($textValue) ? 0 : $value)]);
    }
    
    /** Get the teaxtarea setting field view to administrate a setting.
     * @param int $settingId
     * @param string $label
     * @param string $value
     * @param \Illuminate\View\View */
    protected static function getTextareaEdit($settingId, $label, $value = null){
        $name = 'settings['.$settingId.']';
        return view(myApp()->getConfig('adminViewBasePath').'.settings.form.textarea', 
                ['name' => $name, 'label' => $label, 'value' => $value]);
    }
    
    /** Get the textEditor setting field view to administrate a setting.
     * @param int $settingId
     * @param string $label
     * @param string $value
     * @param \Illuminate\View\View */
    protected static function getTextEditor($settingId, $label, $value = null){
        $name = 'settings['.$settingId.']';
        return view(myApp()->getConfig('adminViewBasePath').'.settings.form.textEditor', 
                ['name' => $name, 'label' => $label, 'value' => $value]);
    }
    
    /** Get a color edit admin field.
     * @param int $settingId
     * @param string $label
     * @param string $value
     * @param \Illuminate\View\View */
    protected static function getColorEdit($settingId, $label, $value = null){
        $name = 'settings['.$settingId.']';
        return view(myApp()->getConfig('adminViewBasePath').'.settings.form.colorEdit', 
                ['id'=>'s'.$settingId, 'name' => $name, 'label' => $label, 'value' => $value]);
    }
    
}