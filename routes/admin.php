<?php
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CollectionController;
use App\Http\Controllers\Admin\ColorFamilyController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VariantController;
use App\Http\Controllers\Admin\BannerController;
use Illuminate\Support\Facades\Route;

Route::get('/category/children',[CategoryController::class,'getChildrenData']);
Route::get('all/options',[OptionController::class,'options'])->name('option.all');
Route::get('/',[WelcomeController::class,'index'])->name('welcome');
Route::get('/login','App\Http\Controllers\Admin\LoginController@index')->name('login');
Route::post('/admin/login','App\Http\Controllers\Admin\LoginController@login')->name('admin.login');


// Route::get('{path}', [WelcomeController::class,'index'])->where('path','([A-z\d\-\/_.]+)?');

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth:admin'])->group(function(){
    Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');
    Route::post('/logout', [AdminController::class,'logout'])->name('logout');
});
Route::middleware(['auth:admin'])->group(function(){
    // Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');
    // Route::post('/logout', [AdminController::class,'logout'])->name('logout');

    Route::group(['prefix'=>'api'],function(){
        Route::get('/user', [UserController::class,'index'])->name('user');
        Route::post('/product/status/{id}',[ProductController::class,'updateStatus']);
        Route::get('/category/parent',[CategoryController::class,'getParentData']);
        Route::get('/select/category',[CategoryController::class,'getSelectableCategory']);
        Route::post('update/product/options',[ProductController::class,'updateProductOptions']);
        Route::post('category/option/{id}',[CategoryController::class,'getCategoryOptions']);
        Route::post('/update/product/image',[ProductController::class,'updateProductImage']);
        Route::post('/update/cover/{id}',[ProductController::class,'updateCover']);
        Route::resources([
            'category' => CategoryController::class,
            'product' => ProductController::class,
            'brand' => BrandController::class,
            'colors' => ColorFamilyController::class,
            'collection' => CollectionController::class,
            'banner' => BannerController::class

        ]);
        
        Route::post('product/variant',[VariantController::class,'save']);
        Route::delete('product/variant/{id}',[VariantController::class,'delete']);
        Route::get('all/category/options',[OptionController::class,'index']);
        Route::post('store/options',[OptionController::class,'save']);
        Route::post('update/category/options',[OptionController::class,'updateCategoryOption']);
        
        
    });

});

Route::get('{path}', [DashboardController::class,'index'])->where('path','([A-z\d\-\/_.]+)?');
