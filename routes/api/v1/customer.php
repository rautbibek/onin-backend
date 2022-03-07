<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Customer\HomeController;
use App\Http\Controllers\Api\V1\Customer\CartController;

Route::get('/customer/home',[HomeController::class,'index']);
//Route::get('/cart/data',[CartController::class,'index']);
Route::middleware(['auth:sanctum'])->group(function(){
    Route::resources([
        'cart' => CartController::class,
    ]);
});