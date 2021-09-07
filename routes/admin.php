<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;

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

Route::get('/', [DashboardController::class,'index'])->name('index');
Route::get('/login',[DashboardController::class,'login'])->name('login');
Route::get('/dashboard',[DashboardController::class,'index'])->middleware('adminauth');
Route::get('{path}', [DashboardController::class,'index'])->where('path','([A-z\d\-\/_.]+)?');
