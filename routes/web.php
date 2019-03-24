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

Route::post('/login', 'Auth\LoginController@login')->name('login');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout')->middleware('auth:api');
Route::post('/register', 'Auth\RegisterController@register')->name('register');
Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::post('/password/reset', 'Auth\ResetPasswordController@reset')->name('password.reset');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/hierarchy', function () {
    return view('welcome');
});

Route::get('/list', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('welcome');
});

Route::get('/register', function () {
    return view('welcome');
});

Route::get('/password/email', function () {
    return view('welcome');
});

Route::get('/password/reset', function () {
    return view('welcome');
});

Route::group(['prefix' => '/home','middleware'=>'auth:api'], function () {
  Route::get('/', function () {
    return view('welcome');
  });

  Route::get('/employee/create', function () {
    return view('welcome');
  });

  Route::get('/employee/edit/:id', function () {
    return view('welcome');
  });
});
