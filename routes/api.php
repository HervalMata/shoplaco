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
Route::get('shop/products', [\App\Http\Controllers\Api\Shop\ProductController::class, 'index']);
Route::get('shop/products/{product}', [\App\Http\Controllers\Api\Shop\ProductController::class, 'show']);
Route::get('shop/products/{category}', [\App\Http\Controllers\Api\Shop\ProductController::class, 'indexByCategory']);
Route::get('shop/products/featured', [\App\Http\Controllers\Api\Shop\ProductController::class, 'indexByFeatured']);
Route::get('shop/products/recommended', [\App\Http\Controllers\Api\Shop\ProductController::class, 'indexByRecommended']);
Route::patch('categories/{category}/restore', [\App\Http\Controllers\Api\Shop\CategoryController::class, 'restore']);
Route::patch('products/{product}/restore', [\App\Http\Controllers\Api\Shop\ProductController::class, 'restore']);
Route::resource(
    'products.colors', \App\Http\Controllers\Api\Admin\ProductColorController::class,
    ['only' => ['index', 'store', 'destroy']]
);
Route::resource(
    'products.materials', \App\Http\Controllers\Api\Admin\ProductMaterialController::class,
    ['only' => ['index', 'store', 'destroy']]
);
    Route::apiResources([
        'categories' => \App\Http\Controllers\Api\Admin\CategoryController::class,
        'colors' => \App\Http\Controllers\Api\Admin\ColorController::class,
        'materials' => \App\Http\Controllers\Api\Admin\MaterialController::class,
        'products' => \App\Http\Controllers\Api\Admin\ProductController::class,
        'products.photos' => \App\Http\Controllers\Api\Admin\ProductPhotoController::class
    ]);
//});
