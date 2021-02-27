<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/admin')->group(function () {
  Route::get('dummy-admin', 'Admin\LoginController@createDummyAdmin');
  Route::post('login', 'Admin\LoginController@login');
});

Route::prefix('v1/admin')->group(function () {
    Route::get('auth-id',function(){
        return response()->json(auth_id());
     });

    Route::prefix('product')->group(function () {
        Route::get('get','Admin\ProductController@get');
        Route::post('create', 'Admin\ProductController@create');
        Route::post('update','Admin\ProductController@update');
        Route::post('delete','Admin\ProductController@delete');
    });
});
