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

//Auth::routes();

Route::group(['middleware' => 'web'], function () {

    Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');
	Route::post('/', 'Auth\LoginController@login');
	Route::post('logout', 'Auth\LoginController@logout')->name('logout');

	Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
	Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
	Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
	Route::post('password/reset', 'Auth\ResetPasswordController@reset');

	Route::get('/home', 'HomeController@index')->name('home');

	Route::post('home', 'HomeController@store');

   /* Route::group(['prefix'=>'admin',  'middleware' => 'admin'], function(){
        Route::get('/', function(){
            return view('admin.index');
        });

        Route::get('/user', function(){
            return view('admin.user');
        });
    });*/
});
