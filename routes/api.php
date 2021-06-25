<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/greeting', function () {
    return 'Hello World !';
});

Route::namespace('App\Http\Controllers\Api')->group(function () {
    // 注文処理 手続き型
    Route::post('/v1/order', 'Procedural\OrderController@order');
    // 注文処理 オブジェクト指向
    Route::post('/v2/order', 'ObjectOriented\OrderController@order');
});
