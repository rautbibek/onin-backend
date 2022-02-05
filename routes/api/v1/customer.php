<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Customer\HomeController;

Route::get('/customer/home',[HomeController::class,'index']);