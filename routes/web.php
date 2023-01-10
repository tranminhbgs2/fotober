<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;

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
Route::get('/login-change-language/{locale}', function ($locale){
    $locale = (in_array($locale, ['en', 'vi'])) ? $locale : 'vi';
    App::setLocale($locale);
    session()->put('locale', $locale);
    return redirect()->back();
})->name('login_change_language');

Route::get('/change-language/{locale}', function ($locale){
    $locale = (in_array($locale, ['en', 'vi'])) ? $locale : 'vi';
    App::setLocale($locale);
    session()->put('locale', $locale);
    return redirect()->back();
})->name('change_language');

Route::get('/', 'Auth\LoginController@showLoginForm')->name('login_form');
Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login_form');
Route::post('/login', 'Auth\LoginController@login')->name('login');

Route::get('/register', 'Auth\RegisterController@showRegistrationForm')->name('register_form');
Route::post('/register', 'Auth\RegisterController@processRegister')->name('register_form');
Route::get('/forgot-password', 'Auth\LoginController@showForgotPassForm')->name('forgot_password_form');

Route::post('/forgot-password', 'Auth\LoginController@processForgotPass')->name('forgot_password');
Route::get('/change-password', 'Auth\LoginController@showChangePassForm')->name('change_password_form');
Route::post('/change-password', 'Auth\LoginController@processChangePass')->name('change_password');

Route::get('/account-activation/{token}', 'Auth\LoginController@activation')->name('account_activation_url');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/logout', function(){
        return redirect()->route('login');
    });
    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
    Route::get('/home', 'Cms\DashboardController@home')->name('dashboard_home');

    // Hàm gọi tất cả các file route thành phần trong thư mục Cms
    includeRouteFiles(__DIR__ . '/Cms/');

});


