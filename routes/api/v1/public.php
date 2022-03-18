<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Login\LoginController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\CommonDataController;
use App\Http\Controllers\Api\V1\CollectionController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\BannerController;
use App\Http\Controllers\Api\V1\ReviewController;

Route::post('/login',[LoginController::class,'login']);
Route::post('/register',[LoginController::class,'register']);

Route::get('/category',[CommonDataController::class,'allCategories']);
Route::get('/product_types',[CommonDataController::class,'getProductTypes']);
Route::get('/category/options/{id}',[CommonDataController::class,'categoryOptions']);
Route::get('/category/brand/{id}',[CommonDataController::class,'getCategoryBrand']);
Route::get('/colors',[CommonDataController::class,'getColors']);
Route::get('/collection',[CommonDataController::class,'getCollection']);

// common controller for address
Route::get('/all/state',[CommonDataController::class,'states']);
Route::get('/all/district',[CommonDataController::class,'districts']);
Route::get('/all/cities',[CommonDataController::class,'cities']);
Route::get('/state/district/{state_id}',[CommonDataController::class,'getDistrictByState']);
Route::get('/district/city/{district_id}',[CommonDataController::class,'getCityByDistrict']);
Route::get('/city/localarea/{city_id}',[CommonDataController::class,'getLocalareaByCity']);
// end of common address controller
Route::get('all/collection',[CollectionController::class,'index']);
Route::get('/category',[CategoryController::class,'index']);
Route::get('/category/product/{id}',[ProductController::class,'categoryProduct']);
Route::get('/products',[ProductController::class,'allProduct']);
Route::get('/product/{id}',[ProductController::class,'productDetail']);
Route::get('/banner',[BannerController::class,'banner']);
Route::get('/product/review/{id}',[ReviewController::class,'review']);