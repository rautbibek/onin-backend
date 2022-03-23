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
use App\Http\Controllers\Admin\Address\StateController;
use App\Http\Controllers\Admin\Address\DistrictController;
use App\Http\Controllers\Admin\Address\CityController;
use App\Http\Controllers\Admin\Address\LocalAreaController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\Order\OrderController;
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
        Route::delete('/product/image/{id}',[ProductController::class,'removeProductImage']);
        Route::get('/unread/notification',[NotificationController::class,'unreadNotification']);
        Route::post('/read/notification/{id}',[NotificationController::class,'readNotification']);
        Route::post('/update/cover/{id}',[ProductController::class,'updateCover']);
        Route::post('/remove/collection/product/{id}',[CollectionController::class,'removeProductFromCollection']);
        Route::get('/all/order',[OrderController::class,'index']);
        Route::resources([
            'category' => CategoryController::class,
            'product' => ProductController::class,
            'brand' => BrandController::class,
            'colors' => ColorFamilyController::class,
            'collection' => CollectionController::class,
            'banner' => BannerController::class,
            'state' => StateController::class,
            'district' => DistrictController::class,
            'city' => CityController::class,
            'localarea' => LocalAreaController::class

        ]);
        
        Route::post('product/variant',[VariantController::class,'save']);
        Route::delete('product/variant/{id}',[VariantController::class,'delete']);
        Route::get('all/category/options',[OptionController::class,'index']);
        Route::delete('/delete/options/{id}',[OptionController::class,'delete']);
        Route::post('store/options',[OptionController::class,'save']);
        Route::get('/order/detail/{id}',[OrderController::class,'orderDetail']);
        Route::put('/update/order/comment/{id}',[OrderController::class,'updateComment']);
        Route::post('/order/payment/complete/{id}',[OrderController::class,'changePaymentStatus']);
        Route::post('update/category/options',[OptionController::class,'updateCategoryOption']);
        
        
    });

});

Route::get('{path}', [DashboardController::class,'index'])->where('path','([A-z\d\-\/_.]+)?');
