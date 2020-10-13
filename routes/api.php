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

Route::get('pagseguro', [\App\Http\Controllers\Api\Shop\PagSeguroController::class, 'pagseguro'])->name('pagseguro');
Route::get('pagseguro-transparente', [\App\Http\Controllers\Api\Shop\PagSeguroController::class, 'transparente'])->name('pagseguro.transparente');
Route::post('pagseguro-transparente', [\App\Http\Controllers\Api\Shop\PagSeguroController::class, 'getCode'])->name('pagseguro.code.transparente');
Route::name('login')->post('login', 'AuthController@login');
Route::name('refresh')->post('refresh', 'AuthController@refresh');
Route::resource('shop/categories', \App\Http\Controllers\Api\Shop\CategoryController::class,
        ['only' => ['index', 'show']]);
Route::resource('shop/users', \App\Http\Controllers\Api\Shop\UserController::class,
    ['only' => ['index', 'store', 'show', 'update']]);
Route::resource('shop/profile', \App\Http\Controllers\Api\Shop\UserProfileController::class,
    ['only' => ['update']]);
Route::get('shop/products', [\App\Http\Controllers\Api\Shop\ProductController::class, 'index']);
Route::get('shop/products/{product}', [\App\Http\Controllers\Api\Shop\ProductController::class, 'show']);
Route::get('shop/products/{category}', [\App\Http\Controllers\Api\Shop\ProductController::class, 'indexByCategory']);
Route::get('shop/products/featured', [\App\Http\Controllers\Api\Shop\ProductController::class, 'indexByFeatured']);
Route::get('shop/products/recommended', [\App\Http\Controllers\Api\Shop\ProductController::class, 'indexByRecommended']);

Route::group(['middleware' => 'auth:api', 'jwt.refresh'], function () {
    Route::name('me')->post('me', 'AuthController@me');
    Route::name('logout')->post('logout', 'AuthController@logout');
    Route::patch('categories/{category}/restore', [\App\Http\Controllers\Api\Admin\CategoryController::class, 'restore']);
    Route::patch('products/{product}/restore', [\App\Http\Controllers\Api\Admin\ProductController::class, 'restore']);
    Route::patch('users/{user}/restore', [\App\Http\Controllers\Api\Admin\UserController::class, 'restore']);
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
        'products.photos' => \App\Http\Controllers\Api\Admin\ProductPhotoController::class,
        'users' => \App\Http\Controllers\Api\Admin\UserController::class,
    ]);
});
