<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::resource('categories', App\Http\Controllers\CategoryController::class);
    Route::resource('subcategories', App\Http\Controllers\SubcategoryController::class);
    Route::resource('products', App\Http\Controllers\ProductController::class);
    Route::get('/product-data-table', [ProductController::class,'getData'])->name('products.getData');
    Route::get('/products/search', [ProductController::class,'search'])->name('products.search');
});
