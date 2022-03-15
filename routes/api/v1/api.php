<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Login\LoginController;

Route::middleware(['auth:sanctum'])->group(function(){
    Route::get('/me', [LoginController::class,'getLoggedInUser']);
    Route::post('/logout', [LoginController::class,'logOut']);
});








