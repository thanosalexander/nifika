<?php

namespace App\Logic\Settings;

use App\Helpers\StringHelper;
use App\Http\Requests\SettingsRequest;
use App\MenuItem;
use App\Page;
use App\Setting;

/** Used to save Settings in database. 
 * @property SettingsRequest $request
 */
class SettingsSaver {
    
    /** @var Request */
    protected $request;

    /** Get the Saver. 
     * @param Request $request
     * @return \static */
    public static function get($request) {
        $saver = new static();
        $saver->request = $request;
        return $saver;
    }

    /** Save settings of given group. 
     * @param string $group */
    public function save($group) {
        //get sent settings data
        $sentSettings = $this->request->input('settings');
        $editableSettings = Setting::editables($group);
        $settings = $this->fillFromSentData($editableSettings, $sentSettings);
        $this->saveSettings($settings);
        return true;
    }
    
    /** Convert settings array of [{key} => {value}] to array of [key => {key}, value => {value}]
     * @param array $settings
     * @param array $data
     * @return array */
    protected function fillFromSentData($settings, $data){
        $filledSettings = [];
        foreach($settings as $id){
            $settingType = Setting::getSettingType($id);
            switch($settingType){
                case Setting::EDITTYPE_CHECKBOX:
                    $filledSettings[$id] = !isset($data[$id]) ? 0: 1;
                    break;
                case Setting::EDITTYPE_EDITOR:
                    $filledSettings[$id] = StringHelper::allowSimpleTagsEditor($data[$id], '<img>');
                    break;
                case Setting::EDITTYPE_TEXT_PRICE:
                    $filledSettings[$id] = toDecimalDot(!isset($data[$id]) ? 0: $data[$id]);
                    break;
                case Setting::EDITTYPE_TEXT_NUMBER:
                    $filledSettings[$id] = (!isset($data[$id]) ? 0: $data[$id]);
                    break;
                case Setting::EDITTYPE_SELECT_MULTIPLE:
                    $filledSettings[$id] = !isset($data[$id]) ? '': implode(',', $data[$id]);
                    break;
                default: 
                    if(isset($data[$id])){
                        $filledSettings[$id] = $data[$id];
                    }
                    break;
            }
        }
        
        return $filledSettings;
    }
    
    /** Save given setting
     * @param type $inputSettings */
    protected function saveSettings($inputSettings){
        foreach ($inputSettings as $id => $value) {
            //fitler setting values
            $settingType = Setting::getSettingType($id);
            switch($id){
                case Setting::SS_CONTACT_PAGE_ENABLED:
                    $settingValue = $value === 0 ? Page::ENABLED_NO: Page::ENABLED_YES;
                    break;
                default:
                    $settingValue = $value;
                    break;
            }
            Setting::setter($id, $settingValue);
        }
    }
    
    /** Save file
     * @param array $data
     * @return \File */
    public function saveFile($data){
//        $file = \File::getCatalog();
//        $file->emptyFile = (!empty($file->getTheFileAttribute()) 
//                && isset($data['deleteFile']) 
//                && !empty($data['deleteFile']));
//        if ($file->emptyFile) {
//            $file->setTheFileAttribute(null);
//            $file->setFileInfo();
//        }
//        $file->fill($data);
//        $file->fill(['description' => '']);
//        return $file->save();
    }
    
    /** Save given setting
     * @param type $inputSettings */
    public function saveMenu($menuData){
        $saver = function($items, $saver, $action){
            $order = 0;
            foreach($items as $itemId => $data){
                if(intval($itemId) !== 0){ //root
                    $menuItem = MenuItem::find($itemId);
                    if(!is_null($menuItem)){
                        switch($action){
                            case 'save':
                                $menuItem->sort = $order;
                                $menuItem->save();
                                break;
                            case 'delete':
                                $menuItem->delete();
                                break;
                        }
                    }
                }
                if(is_array($data)){ //has children
                    $saver($data, $saver, $action);
                }
                $order++;
                
            }
        };
        
        if(array_key_exists('active', $menuData)){
            $saver($menuData['active'], $saver, 'save');
        }
        
        if(array_key_exists('deleted', $menuData)){
            $saver($menuData['deleted'], $saver, 'delete');
        }
        
        return true;
    }
}
