<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\AuthAdmin;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ShopController;






Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home.index');

Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{product_slug}', [ShopController::class, 'product_detail'])->name('shop.product.detail');





Route::middleware(['auth'])->group(function(){
    Route::get('/account-dashboard', [UserController::class, 'index'])->name('user.index');
});


Route::middleware(['auth',AuthAdmin::class])->group(function(){
    Route::get('adminsecure', [AdminController::class, 'index'])->name('admin.index');
    Route::get('adminsecure/brands',[AdminController::class,'brands'])->name('admin.brands');
    Route::get('adminsecure/brands/add',[AdminController::class,'add_brand'])->name('admin.brands.add');
    Route::post('adminsecure/brands/store',[AdminController::class,'brand_store'])->name('admin.brands.store');
    Route::get('adminsecure/brands/edit/{id}',[AdminController::class,'edit_brand'])->name('admin.brands.edit');
    Route::put('adminsecure/brands/update',[AdminController::class,'brand_update'])->name('admin.brands.update');
    Route::delete('adminsecure/brands/{id}/delete',[AdminController::class,'brand_delete'])->name('admin.brands.delete');

    Route::get('adminsecure/categories',[AdminController::class,'categories'])->name('admin.categories');
    Route::get('adminsecure/categories/add',[AdminController::class,'category_add'])->name('admin.categories.add');
    Route::post('adminsecure/categories/store',[AdminController::class,'category_store'])->name('admin.categories.store');
    Route::get('adminsecure/category/edit/{id}',[AdminController::class,'category_edit'])->name('admin.category.edit');
    Route::put('adminsecure/category/update',[AdminController::class,'category_update'])->name('admin.category.update');
    Route::delete('adminsecure/category/{id}/delete',[AdminController::class,'category_delete'])->name('admin.category.delete');


    Route::get('adminsecure/products',[AdminController::class,'products'])->name('admin.products');
    Route::get('adminsecure/products/add',[AdminController::class,'product_add'])->name('admin.products.add');
    Route::post('adminsecure/products/store',[AdminController::class,'product_store'])->name('admin.products.store');
    Route::get('adminsecure/products/edit/{id}',[AdminController::class,'product_edit'])->name('admin.products.edit');
    Route::put('adminsecure/products/update',[AdminController::class,'product_update'])->name('admin.products.update');
    Route::delete('adminsecure/products/{id}/delete',[AdminController::class,'product_delete'])->name('admin.products.delete');
    
});