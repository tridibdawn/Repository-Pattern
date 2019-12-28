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

Route::get('/users', 'UserController@index');
Route::get('/users/create', 'UserController@create');
Route::post('/users', 'UserController@store');
Route::get('/users/{user}', 'UserController@show');
Route::get('/users/edit/{user}', 'UserController@edit');
Route::put('/users/{user}', 'UserController@update');
Route::patch('/users/{user}', 'UserController@update');
Route::delete('/users/{user}', 'UserController@destroy');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
