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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');

Route::get('home/settings', 'HomeController@showSettings')->name('settings');
Route::post('home/settings', 'HomeController@storeSettings')->name('settings');

Route::get('/sms-verify', 'Auth\SmsCodeVerificationController@show')->name('sms-verify');
Route::post('/sms-verify', 'Auth\SmsCodeVerificationController@login');
