<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductListController;
use App\Http\Controllers\Admin\ProductCatController;
use App\Http\Controllers\Admin\NewsListController;
use App\Http\Controllers\Admin\NewsCatController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SeoPageController;
use App\Http\Controllers\Admin\ContactController;


// 1. Nhóm route dành cho KHÁCH (chưa đăng nhập)
Route::middleware('guest:admin')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login.post');
});

// 2. Nhóm route dành cho ADMIN ĐÃ ĐĂNG NHẬP (auth:admin)
Route::middleware('auth:admin')->group(function () {


    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::post('upload-media', [App\Http\Controllers\Admin\UploadController::class, 'upload'])->name('upload.media');



    Route::middleware('role:super_admin')->group(function () {
        Route::get('/settings', function () {
            echo "Chỉ Super Admin mới thấy";
        });
    });


    // Quản lý Sản phẩm
    Route::get('products/get-cats/{id_list}', [ProductController::class, 'getCats'])->name('products.get-cats');
    Route::resource('products', ProductController::class);
    Route::resource('product-lists', ProductListController::class);
    Route::resource('product-cats', ProductCatController::class);

    // Quản lý Tin tức
    Route::get('news/get-cats/{id_list}', [NewsController::class, 'getCats'])->name('news.get-cats');
    Route::resource('news', NewsController::class);
    Route::resource('news-lists', NewsListController::class);
    Route::resource('news-cats', NewsCatController::class);
    
    Route::resource('brands', BrandController::class);
    
    // Quản lý Trang Cá Nhân
    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::resource('seo-pages', SeoPageController::class);
    Route::resource('contacts', ContactController::class)->only(['index', 'show', 'destroy']);

});