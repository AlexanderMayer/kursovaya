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
Route::get('/{page}', [App\Http\Controllers\MainController::class, 'enter'])->where('page', '.*');
//Route::get('/', function () {
//    return view('welcome');
//});
//
//Route::get('/test','App\Http\Controllers\TestController@index');
//Route::get('/test/store','App\Http\Controllers\TestController@store')->name('imgtest.store');

//Route::group(['namespace'=>'App\Http\Controllers\User', 'prefix'=>'user'], function(){
//    Route::post('/store', 'AddAvatarController')->name('user.store');
//});
