<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Login\LoginController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\CommonDataController;
use App\Http\Controllers\Api\V1\CollectionController;
use App\Http\Controllers\Api\V1\CategoryController;

Route::post('/login',[LoginController::class,'login']);
Route::post('/register',[LoginController::class,'register']);
Route::get('/category',[CommonDataController::class,'allCategories']);
Route::get('/product_types',[CommonDataController::class,'getProductTypes']);
Route::get('/category/options/{id}',[CommonDataController::class,'categoryOptions']);
Route::get('/category/brand/{id}',[CommonDataController::class,'getCategoryBrand']);
Route::get('/colors',[CommonDataController::class,'getColors']);
Route::get('/collection',[CommonDataController::class,'getCollection']);
Route::get('all/collection',[CollectionController::class,'index']);
Route::get('/category',[CategoryController::class,'index']);
Route::get('/category/{slug}',[ProductController::class,'categoryProduct']);