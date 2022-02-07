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

Route::get('products', 'App\Http\Controllers\ProductController@listProducts');
Route::get('product/{id}', 'App\Http\Controllers\ProductController@singleProduct');
Route::post('create-product', 'App\Http\Controllers\ProductController@storeProduct');
Route::post('update-product/{id}', 'App\Http\Controllers\ProductController@updateProduct');
Route::post('delete-product/{id}', 'App\Http\Controllers\ProductController@deleteProduct');
Route::get('categories', 'App\Http\Controllers\ProductController@listCategories');
