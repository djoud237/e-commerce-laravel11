<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\AuthAdmin;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;






Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home.index');



Route::middleware(['auth'])->group(function(){
    Route::get('/account-dashboard', [UserController::class, 'index'])->name('user.index');
});


Route::middleware(['auth',AuthAdmin::class])->group(function(){
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/brands',[AdminController::class,'brands'])->name('admin.brands');
    Route::get('/admin/brands/add',[AdminController::class,'add_brand'])->name('admin.brands.add');
    Route::post('/admin/brands/store',[AdminController::class,'brand_store'])->name('admin.brands.store');
    Route::get('/admin/brands/edit/{id}',[AdminController::class,'edit_brand'])->name('admin.brands.edit');
    Route::put('/admin/brands/update',[AdminController::class,'brand_update'])->name('admin.brands.update');
    Route::delete('/admin/brands/{id}/delete',[AdminController::class,'brand_delete'])->name('admin.brands.delete');

    Route::get('/admin/categories',[AdminController::class,'categories'])->name('admin.categories');
    Route::get('/admin/categories/add',[AdminController::class,'category_add'])->name('admin.categories.add');
    Route::post('/admin/categories/store',[AdminController::class,'category_store'])->name('admin.categories.store');
    Route::get('/admin/category/edit/{id}',[AdminController::class,'category_edit'])->name('admin.category.edit');
    Route::put('/admin/category/update',[AdminController::class,'category_update'])->name('admin.category.update');
    Route::delete('/admin/category/{id}/delete',[AdminController::class,'category_delete'])->name('admin.category.delete');


    Route::get('/admin/products',[AdminController::class,'products'])->name('admin.products');
    Route::get('/admin/products/add',[AdminController::class,'product_add'])->name('admin.products.add');
    Route::post('/admin/products/store',[AdminController::class,'product_store'])->name('admin.products.store');
    Route::get('/admin/products/edit/{id}',[AdminController::class,'product_edit'])->name('admin.products.edit');
    Route::put('/admin/products/update',[AdminController::class,'product_update'])->name('admin.products.update');
    
});