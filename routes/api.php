<?php

use Illuminate\Support\Facades\Route;

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

// Product Routes
Route::get('products', 'App\Http\Controllers\ProductController@listProducts');
Route::get('products/{id}', 'App\Http\Controllers\ProductController@singleProduct');
Route::post('products/create', 'App\Http\Controllers\ProductController@storeProduct');
Route::patch('products/update/{id}', 'App\Http\Controllers\ProductController@updateProduct');
Route::delete('products/delete/{id}', 'App\Http\Controllers\ProductController@deleteProduct');

// Category Routes
Route::get('categories', 'App\Http\Controllers\CategoryController@listCategories');
