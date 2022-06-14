<?php

use App\Http\Controllers\Api\V1\ForgotPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Login\LoginController;

Route::post('/verify/user',[ForgotPasswordController::class,'verfiyContactNumber']);
Route::post('/reset/user/password',[ForgotPasswordController::class,'resetPassword']);

Route::middleware(['auth:sanctum'])->group(function(){
    Route::get('/me', [LoginController::class,'getLoggedInUser']);
    Route::post('/logout', [LoginController::class,'logOut']);
});








