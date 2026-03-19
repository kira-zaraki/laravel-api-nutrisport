<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Feed\FeedController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RefreshController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Cart\CartController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Ai\AIController;
use App\Http\Middleware\CheckAgent;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('register',[RegisterController::class, 'register']);
Route::post('/login',[LoginController::class, 'login'])->name('login');
Route::get('/refresh', [RefreshController::class, 'refresh']);

Route::middleware('auth:api')->group(function () {

    Route::post('/order', [OrderController::class, 'store']);
    Route::get('/orders', [OrderController::class, 'orderByUser']);

    Route::prefix('products')->group(function(){
        Route::get('/', [ProductController::class, 'productListe']);
        Route::get('{product}', [ProductController::class, 'show']);
        Route::get('site/{site}', [ProductController::class, 'productsBySite']);
    });
    Route::get('/sites/{site}/products/{product}', [ProductController::class, 'showBySite']);

    Route::get('/feeds/products.{type}',[FeedController::class, 'products']);

    Route::prefix('agent')->middleware(CheckAgent::class)->group(function () {
        Route::get('orders', [AdminOrderController::class, 'recent']);
        Route::post('product', [AdminProductController::class, 'store']);
    });

    Route::put('/user/profile', [UserController::class, 'updateProfile']);

    Route::post('/ai/nutrition-advisor', [AIController::class, 'chat']);
});

Route::prefix('cart')->group(function(){
    Route::post('/add',[CartController::class, 'add']);
    Route::delete('/remove',[CartController::class, 'remove']);
    Route::post('/show',[CartController::class, 'show']);
});