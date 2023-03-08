<?php

use Illuminate\Support\Facades\Route;

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
Route::post('upload',[\App\Http\Controllers\AnyController::class,'upload'])->name('upload');
Route::post('postCreate',[\App\Http\Controllers\AnyController::class,'postCreate'])->name('postCreate');


Route::post('delete_photo',[\App\Http\Controllers\AnyController::class,'deletePhoto'])->name('delete.photo');
Route::post('delete_photo_in_update',[\App\Http\Controllers\AnyController::class,'deletePhotoInUpdate'])->name('delete.photo.update');
Route::get('edit-product/{id}',[\App\Http\Controllers\AnyController::class,'editProduct']);
Route::get('/get-product-image/{id}',[\App\Http\Controllers\AnyController::class,'getImages'])->name('getProductImage');


Route::put('edit-product/{id}',[\App\Http\Controllers\AnyController::class,'updateProduct'])->name('edit-product.edit');



