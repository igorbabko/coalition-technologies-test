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
    return view('index');
});

Route::get('/products', 'ProductController@index');
Route::post('/products', 'ProductController@store');
Route::patch('/products', 'ProductController@update');
Route::delete('/products', 'ProductController@destroy');
