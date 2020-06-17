<?php



/*
|--------------------------------------------------------------------------
| Web Localized Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web localized routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Login Routes
Route::group(['middleware'=>'initAdminEnvironment'], function(){
    $this->get('/myadmin', 'Auth\LoginController@showLoginForm')->name('login');
    $this->post('/adminlogin', 'Auth\LoginController@login')->name('login.post');
    $this->get('/logout', 'Auth\LoginController@logout')->name('logout');
});

Route::group(['as' => 'public.'], function(){
    Route::group(['middleware'=>'initPublicControllerEnvironment'], function(){
        Route::get('/', 'PublicController@index')->name('home');
        Route::post('/contact/send', 'PublicController@sendContactForm')->name('contact.send');
        Route::post('/recaptcha/verify', 'PublicController@verifyRecaptcha')->name('recaptcha.verify');
        Route::get('/c/sitemap.xml', 'SitemapController@showSitemapXml')->name('sitemap.show');
        Route::get('/{slug}', 'PublicController@showPage')->name('page.show');
    });
});
