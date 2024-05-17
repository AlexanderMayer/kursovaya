<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/imgtest','App\Http\Controllers\ImgTestController@create');
Route::post('/imgtest/store','App\Http\Controllers\ImgTestController@store')->name('imgtest.store');

Route::group(['namespace'=>'App\Http\Controllers\User', 'prefix'=>'user'], function(){
    Route::post('/store', 'StoreController')->name('user.store');
});
