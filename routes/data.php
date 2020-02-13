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

// Users
Route::get('users', 'UsersController@index')->middleware('admin');
Route::post('users.create', 'UsersController@store')->middleware('admin');
Route::get('users.{id}', 'UsersController@show');
Route::put('users.update.{id}', 'UsersController@update');
Route::delete('users.destroy', 'UsersController@destroy')->middleware('admin');

// Texts
Route::get('texts', 'TextsController@index')->middleware('admin');
Route::post('texts.create', 'TextsController@store')->middleware('admin');
Route::get('texts.{id}', 'TextsController@show');
Route::put('texts.update.{id}', 'TextsController@update')->middleware('admin');
Route::delete('texts.destroy', 'TextsController@destroy')->middleware('admin');


// User Text
Route::get('text/new', 'TextsUsersController@index');
Route::get('text/last', 'TextsUsersController@last');
Route::post('text', 'TextsUsersController@store');
Route::get('text.{id}', 'TextsUsersController@show')->middleware('admin');
Route::put('text', 'TextsUsersController@update');
Route::delete('text.destroy', 'TextsUsersController@destroy');

// Utils
Route::get('utils/entities', 'UtilsController@entities');