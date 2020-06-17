<?php

namespace App\Http\Controllers;

use App\Logic\Settings\SettingsSaver;
use App\Logic\Discounts\DiscountRule;
use App\Logic\StartPageSaver;
use App\Setting;
use App\Http\Requests\SettingsRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SettingsController extends Controller
{
    
    public function edit(Request $request, $settingGroup){
        $settingsPageTitle = trans(myApp()->getConfig('adminTransBaseName').'.menu.settings');
        switch($settingGroup){
            case Setting::GROUP_GENERAL:
                $pageData = ['pageTitle' => "$settingsPageTitle - ".trans(myApp()->getConfig('adminTransBaseName').'.menu.settingsGenaral')];
                break;
            case Setting::GROUP_START_PAGE:
                $pageData = ['pageTitle' => "$settingsPageTitle - ".trans(myApp()->getConfig('adminTransBaseName').'.menu.settingsStartPage')];
                break;
            case Setting::GROUP_CONTACT_PAGE:
                $pageData = ['pageTitle' => "$settingsPageTitle - ".trans(myApp()->getConfig('adminTransBaseName').'.menu.settingsContactPage')];
                break;
            case Setting::GROUP_ADVANCED:
                $pageData = ['pageTitle' => "$settingsPageTitle - ".trans(myApp()->getConfig('adminTransBaseName').'.menu.settingsAdvanced')];
                break;
            default:
                abort(Response::HTTP_NOT_FOUND);
                break;
        }
        return view(myApp()->getConfig('adminViewBasePath').'.settings')
                ->with('settingGroup', $settingGroup)
                ->with('pageData', $pageData);
    }
            
    public function store(SettingsRequest $request, $settingGroup){
        switch($settingGroup){
            case Setting::GROUP_GENERAL:
                return $this->storeGeneral($request);
            case Setting::GROUP_CONTACT_PAGE:
                return $this->storeSettings($request, $settingGroup);
            case Setting::GROUP_START_PAGE:
                return $this->storeStartPage($request);
            case Setting::GROUP_ADVANCED:
                return $this->storeAdvanced($request);
            default:
                abort(Response::HTTP_NOT_FOUND);
                break;
        }
    }
    
    protected function storeSettings(SettingsRequest $request, $settingGroup){
        $response = [
            'success' => ['status' => static::STATUS_OK, 'message' => trans(myApp()->getConfig('adminTransBaseName').'.form.message.createSuccess')],
            'fail' => ['status' => static::STATUS_FAIL, 'message' => trans(myApp()->getConfig('adminTransBaseName').'.form.message.saveFail')],
        ];        //filter editable settings
        
        $result = 'fail';
        try{
            \DB::beginTransaction();
            //get settings saver
            $saver = SettingsSaver::get($request);
            //save settings
            $saver->save($settingGroup);
            $result = 'success';
            \DB::commit();
        }catch( \Exception $e ){
            \Log::error($e->getTraceAsString());
            \DB::rollBack();
            $result = 'fail';
        }
        return \Redirect::back()
                ->with('message', $response[$result]['message'])
                ->with('status', $response[$result]['status']);
    }
    
    protected function storeGeneral(SettingsRequest $request){
        $response = [
            'success' => ['status' => static::STATUS_OK, 'message' => trans(myApp()->getConfig('adminTransBaseName').'.form.message.createSuccess')],
            'fail' => ['status' => static::STATUS_FAIL, 'message' => trans(myApp()->getConfig('adminTransBaseName').'.form.message.saveFail')],
        ];
        $result = 'fail';
        try{
            \DB::beginTransaction();
            //get settings saver
            $saver = SettingsSaver::get($request);
            //get sent logo and bbImage files
            $menuData = $request->get('menu', []);
            //save settings, logo and bgImage
            $saver->save(Setting::GROUP_GENERAL);
            if($saver->saveMenu($menuData)){
                $result = 'success';
            }
            \DB::commit();
        }catch( \Exception $e ){
            \Log::error($e->getTraceAsString());
            \DB::rollback();
            $result = 'fail';
        }
        
        return \Redirect::back()
                ->with('message', $response[$result]['message'])
                ->with('status', $response[$result]['status']);
    }
    protected function storeAdvanced(SettingsRequest $request){
        $response = [
            'success' => ['status' => static::STATUS_OK, 'message' => trans(myApp()->getConfig('adminTransBaseName').'.form.message.createSuccess')],
            'fail' => ['status' => static::STATUS_FAIL, 'message' => trans(myApp()->getConfig('adminTransBaseName').'.form.message.saveFail')],
        ];
        $result = 'fail';
        try{
            \DB::beginTransaction();
            //get settings saver
            $saver = SettingsSaver::get($request);
            //get sent logo and bbImage files
            $filesData = $request->only(['logo', 'backgroundImage']);
            //save settings, logo and bgImage
            $saver->save(Setting::GROUP_ADVANCED);
//            if($saver->saveLogo($filesData['logo'])
//                    && $saver->saveBackgroundImage($filesData['backgroundImage'])){
                $result = 'success';
//            }
            \DB::commit();
        }catch( \Exception $e ){
            \Log::error($e->getTraceAsString());
            \DB::rollback();
            $result = 'fail';
        }
        
        return \Redirect::back()
                ->with('message', $response[$result]['message'])
                ->with('status', $response[$result]['status']);
    }
    
    protected function storeStartPage(SettingsRequest $request){
        $response = [
            'success' => ['status' => static::STATUS_OK, 'message' => trans(myApp()->getConfig('adminTransBaseName').'.form.message.updateSuccess')],
            'fail' => ['status' => static::STATUS_FAIL, 'message' => trans(myApp()->getConfig('adminTransBaseName').'.form.message.saveFail')],
        ];
        $result = 'fail';
        try{
            \DB::beginTransaction();
            $saver = SettingsSaver::get($request);
            if( $saver->save(Setting::GROUP_START_PAGE) ){ $result = 'success'; }
            \DB::commit();
        }catch( \Exception $e ){
            \Log::error($e->getTraceAsString());
            \DB::rollback();
            $result = 'fail';
        }
        
        return \Redirect::back()
                ->with('message', $response[$result]['message'])
                ->with('status', $response[$result]['status']);
    }
}
