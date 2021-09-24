<?php
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

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
    });
});

Route::get('{path}', [DashboardController::class,'index'])->where('path','([A-z\d\-\/_.]+)?');
