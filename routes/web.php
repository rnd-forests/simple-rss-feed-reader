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

Auth::routes();

Route::get('/', 'HomeController@index');

Route::get('/feeds', 'FeedsController@index')->name('feeds.index');
Route::put('/feeds', 'FeedsController@store')->name('feeds.store');
Route::get('/feeds/{feed}', 'FeedsController@show')->name('feeds.show');
Route::get('/feeds/{feed}/edit', 'FeedsController@edit')->name('feeds.edit');
Route::patch('/feeds/{feed}', 'FeedsController@update')->name('feeds.update');
Route::delete('/feeds/{feed}', 'FeedsController@destroy')->name('feeds.destroy');
