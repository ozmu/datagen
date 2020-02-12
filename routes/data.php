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
Route::put('users.update', 'UsersController@update');
Route::delete('users.destroy', 'UsersController@destroy')->middleware('admin');

// Texts
