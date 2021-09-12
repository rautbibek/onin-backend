<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Login\LoginController;
use App\Http\Controllers\Api\V1\Home\HomeController;


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
// Route::get('/login',[LoginController::class,'login']);
// Route::get('/home',[HomeController::class,'index']);
// Route::get('/test', function () {
//     return "welcome";
// });

Route::middleware('auth:api')->get('/user', function (Request $request) {
    //return $request->user();
    return "hello";
});






