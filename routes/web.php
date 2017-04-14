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

Route::get('/', 'Controller@upload');

Route::get('/upload','Controller@upload');
Route::post('/file/upload','Controller@fileUpload');
Route::post('/cropped','Controller@cropped');
Route::post('/files/delete','Controller@delete');
