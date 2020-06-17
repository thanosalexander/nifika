<?php

use App\Language;
use App\Logic\App\AppManager;
use Illuminate\Database\Seeder;

class LanguagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        
        if(env('APP_ENV') == 'local'){
            $this->deleteAll();
            
            factory(Language::class, 'FrontendLanguage', 1)->make(['code' => 'en'])->save();
            factory(Language::class, 'BackendLanguage', 1)->make(['code' => 'el'])->save();
            AppManager::setupMyApp();
        }
    }
    
    /** Delete all Languages
     * @return void */
    public function deleteAll()
    {
        try{
            Language::all()->each(function($model){
                $model->delete();
            });
        } catch(Exception $e){
            echo 'SOMETHING WENT WRONG ON LANGUAGES DELETION';
        }
        DB::table( (new Language())->getTable() )->truncate();
    }
}
