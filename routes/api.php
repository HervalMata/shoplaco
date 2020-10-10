<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::group(['namespace' => 'Api', 'as' => 'api.'], function () {
    Route::resource('shop/categories', \App\Http\Controllers\Api\Shop\CategoryController::class,
        ['only' => ['index', 'show']]);
    Route::apiResources([
        'categories' => \App\Http\Controllers\Api\Admin\CategoryController::class
    ]);
//});
