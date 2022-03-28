<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\FavoriteController;
use App\Http\Controllers\Api\V1\Customer\HomeController;
use App\Http\Controllers\Api\V1\Customer\CartController;
use App\Http\Controllers\Api\V1\Customer\AddressController;
use App\Http\Controllers\Api\V1\Customer\ReviewController;
use App\Http\Controllers\Api\V1\Customer\OrderController;
use App\Http\Controllers\Api\V1\ProductController;


Route::get('/customer/home',[HomeController::class,'index']);
//Route::get('/cart/data',[CartController::class,'index']);

Route::middleware(['auth:sanctum'])->group(function(){
    Route::get('/favorite/product',[ProductController::class,'favoriteProduct']);
    Route::post('/favorite/{id}',[FavoriteController::class,'favorite']);
    
    
    Route::resource('order', OrderController::class)->except(['update', 'destroy','create','edit']);
    Route::resource('address', AddressController::class)->except(['show', 'update','create','edit']);
    Route::resource('review', ReviewController::class)->except(['show','create','edit']);
    Route::resource('cart', CartController::class)->except(['show','create','edit']);
});