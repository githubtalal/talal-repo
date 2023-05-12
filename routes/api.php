<?php

use App\Models\TelegramBot;
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

Route::get('categories', [\App\Http\Controllers\ApiController::class, 'categories']);
Route::get('products/{category?}', [\App\Http\Controllers\ApiController::class, 'products']);
Route::post('addToCart/{product}', [\App\Http\Controllers\ApiController::class, 'addToCart']);
Route::get('removeFromCart/{cartItemId}', [\App\Http\Controllers\ApiController::class, 'removeFromCart']);

Route::post('checkout', [\App\Http\Controllers\ApiController::class, 'checkout']);
Route::get('get-checkout', [\App\Http\Controllers\ApiController::class, 'getCheckout']);
Route::get('store-settings', [\App\Http\Controllers\ApiController::class, 'storeSettings']);

Route::get('cart', [\App\Http\Controllers\ApiController::class, 'getCart']);
Route::get('update-cart', [\App\Http\Controllers\ApiController::class, 'updateCart']);
Route::get('/update-qty/{cartItem}', [\App\Http\Controllers\ApiController::class, 'updateQuantity']);
Route::get('save-payment', [\App\Http\Controllers\ApiController::class, 'savePaymentMethod']);

Route::get('empty-cart', [\App\Http\Controllers\ApiController::class, 'emptyCart']);

Route::get('store-info', [\App\Http\Controllers\ApiController::class, 'getStoreInfo']);

Route::get('telegrambot/{storeId}', [\App\Http\Controllers\ApiController::class, 'telegrambot'])->name('telegrambot.get');
Route::post('telegrambot/{storeId}', [\App\Http\Controllers\ApiController::class, 'telegrambot'])->name('telegrambot.post');
Route::get('test', [\App\Http\Controllers\ApiController::class, 'test']);

Route::get('store/{store_id}/{slug}', [\App\Http\Controllers\ApiController::class, 'getStoreDetails']);
Route::get('store/{store_id}', [\App\Http\Controllers\ApiController::class, 'getAllStoreDetails']);

Route::get('product/{product_id}', [\App\Http\Controllers\ApiController::class, 'getProductInfo']);
