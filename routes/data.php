<?php


/*
|--------------------------------------------------------------------------
| DATA Routes
|--------------------------------------------------------------------------
|
| Here is where you can register DATA routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "data" middleware group.
|
| Default Namespace => /Data/
|
*/

// User Text
Route::get('text/new', 'TextsUsersController@index');
Route::get('text/last', 'TextsUsersController@last');
Route::post('text', 'TextsUsersController@store');
Route::get('text/{id}', 'TextsUsersController@show');
Route::put('text', 'TextsUsersController@update');
Route::delete('text/destroy', 'TextsUsersController@destroy');

// Utils
Route::group(['prefix' => 'utils'], function(){
    Route::get('entities', 'UtilsController@entities');
    Route::get('widgets', 'UtilsController@widgets');
});

// Admin
Route::group(['middleware' => 'admin', 'prefix' => 'admin'], function(){
    // Users
    Route::resource('users', 'UsersController')->except(['create', 'edit']);

    // Texts
    Route::resource('texts', 'TextsController')->except(['create', 'edit']);
    
    // Settings
    Route::get('settings', 'AdminController@getSettings');
    Route::put('settings', 'AdminController@updateSettings');
});