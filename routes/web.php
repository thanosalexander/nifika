<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware'=>'auth'], function(){
    Route::group(['middleware'=>'initAdminEnvironment'], function(){
        Route::group(['middleware' => 'authorizeUser'], function () {
        //client's routes
        Route::group([
            'prefix' => myApp()->getConfig('adminBaseUrl'), 
            'as' => myApp()->getConfig('adminRouteBaseName').'.'
            ], function () {
            // Settings controller
            Route::post('/adminLocale', 'AdminController@updateAdminLocale')->name('adminLocale.update');
            Route::post('/modelLocale', 'AdminController@updateModelLocale')->name('modelLocale.update');
            Route::get('/settings/{settingGroup}', 'SettingsController@edit')->name('settings.edit');
            Route::post('/settings/{settingGroup}/update', 'SettingsController@store')->name('settings.update');

            Route::get('/', 'AdminController@index')->name('home');
            Route::get('/list/{entity}/{id?}/{relation?}', 'AdminController@listEntity')->name('entity.list');
            Route::get('/listdata/{entity}/{id?}/{relation?}', 'AdminController@listDataEntity')->name('entity.listdata');
            Route::get('/create/{entity}/{id?}/{relation?}', 'AdminController@createEntity')->name('entity.create');
            Route::post('/store/{entity}/{id?}/{relation?}', 'AdminController@storeEntity')->name('entity.store');
            Route::get('/editOrder/{entity}/{id?}/{relation?}', 'AdminController@editEntityOrder')->name('entity.editOrder');
            Route::put('/updateOrder/{entity}/{id?}/{relation?}', 'AdminController@updateEntityOrder')->name('entity.updateOrder');            
            Route::get('/{entity}/{id?}', 'AdminController@editEntity')->name('entity.edit');
            Route::put('/{entity}/{id}/update', 'AdminController@updateEntity')->name('entity.update');
            Route::delete('/{entity}/{id}', 'AdminController@destroyEntity')->name('entity.destroy');
            Route::put('/{entity}/{id}/switchStatus', 'AdminController@switchStatusEntity')->name('entity.switchStatus');
            });
        });
    });
});
