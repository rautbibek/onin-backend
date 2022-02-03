<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Login\LoginController;
use App\Http\Controllers\Api\V1\Home\HomeController;
use App\Http\Controllers\Api\V1\CommonDataController;
use App\Http\Controllers\Admin\CategoryController;


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

Route::post('/login',[LoginController::class,'login']);
Route::get('/category',[CommonDataController::class,'allCategories']);
Route::get('/product_types',[CommonDataController::class,'getProductTypes']);
Route::get('/category/options/{id}',[CommonDataController::class,'categoryOptions']);
Route::get('/category/brand/{id}',[CommonDataController::class,'getCategoryBrand']);
Route::get('/colors',[CommonDataController::class,'getColors']);
Route::get('/collection',[CommonDataController::class,'getCollection']);
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
    //return "hello";
});








