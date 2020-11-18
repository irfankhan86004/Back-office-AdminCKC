<?php

/*
|--------------------------------------------------------------------------
| Media Routes
|--------------------------------------------------------------------------
*/

Route::get('media-picture/{id}-{width}-{height}-{filename}', 'MediaController@picture')->name('media_picture');
Route::get('media-file/{id}-{filename}', 'MediaController@file')->name('media_file');
Route::get('media-file-preview/{id}-{width}-{height}-{filename}.png', 'MediaController@filePreview')->name('media_file_preview');

/*
|--------------------------------------------------------------------------
| Backend Routes
|--------------------------------------------------------------------------
*/
// MailGun Hooks
Route::post('/mailgun/hooks/4sd6g48sd6fh54fdsh6', 'HooksMailGunController@index');

// Admin login
Route::get(config('app.admin_url'), 'AdminAuth\AuthController@showLoginForm')->name('admin');
Route::post(config('app.admin_url') . '/login', 'AdminAuth\AuthController@login')->name('admin_login');

Route::get(config('app.admin_url').'/tag/fetch', 'Admin\TagController@tagFetch')->name('admin-tag-fetch');
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['web']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/email', 'HomeController@email')->name('home.email');
});
