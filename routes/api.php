<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/sent_mail', 'App\Http\Controllers\TestController@index2');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'App\Http\Controllers\AuthController@login');
    Route::post('logout', 'App\Http\Controllers\AuthController@logout');
    Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
    Route::post('me', 'App\Http\Controllers\AuthController@me');
});

Route::group(['namespace'=>'App\Http\Controllers\User', 'prefix'=>'user', 'middleware'=>'jwt.auth'], function(){
    Route::get('/{user}/edit', 'EditController');
    Route::patch('/{user}', 'UpdateController');
});

Route::group(['namespace'=>'App\Http\Controllers\User', 'prefix'=>'user'], function(){
    Route::post('/store', 'StoreController');
});

Route::group(['namespace'=>'App\Http\Controllers', 'prefix'=>'lot', 'middleware'=>'jwt.auth'], function(){
    Route::get('/', 'Lot\IndexController')->name('lot.index');
    Route::post('/', 'Lot\StoreController')->name('lot.store');
    Route::get('/{lot}', 'Lot\ShowController')->name('lot.show');
    Route::get('/{lot}/edit', 'Lot\EditController')->name('lot.edit');
    Route::patch('/{lot}', 'Lot\UpdateController')->name('lot.update');
    Route::delete('/{lot}', 'Lot\DeleteController')->name('lot.delete');

    Route::post('/{lot}/edit/photo/', 'Photo\StoreController');
    Route::delete('/{lot}/edit/photo/{photo}', 'Photo\DeleteController'); 

});

Route::group(['namespace'=>'App\Http\Controllers\Message', 'prefix'=>'message', 'middleware'=>'jwt.auth'], function(){
    Route::get('/', 'IndexController')->name('message.index');
    Route::get('/{recipient}/create', 'CreateController')->name('message.asd');
    Route::post('/{recipient}', 'StoreController')->name('message.store');
    Route::delete('/{message}', 'DeleteController')->name('message.delete');
});

Route::group(['namespace'=>'App\Http\Controllers', 'prefix'=>'admin', 'middleware'=>'isAdmin'], function(){
    Route::get('/', 'AdminController@index')->name('admin.index');

});
