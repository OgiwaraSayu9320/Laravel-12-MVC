<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\NewsController;
use App\Http\Controllers\Frontend\ContactController;

// Redirection mặc định từ / sang /vi
Route::get('/', function () {
    return redirect('/vi');
});

// Nhóm route Frontend có Middleware setlocale
Route::middleware(['setlocale'])->group(function () {

    // Route cho trang quản lý profile admin (vẫn giữ prefix admin nhưng dùng auth)
    // Lưu ý: Route admin thực tế nằm trong routes/admin.php và được load bởi bootstrap/app.php

    // Route cho Login mặc định (Laravel yêu cầu route name 'login')
    Route::get('/login', function () {
        return redirect()->route('admin.login');
    })->name('login');

    // Nhóm route theo ngôn ngữ
    Route::prefix('{lang}')->group(function () {

        // Trang chủ
        Route::get('/', [HomeController::class, 'index'])->name('home');

        // Sản phẩm
        Route::get('/san-pham', [ProductController::class, 'index'])->name('products.index');
        Route::get('/san-pham/{slug}', [ProductController::class, 'show'])->name('products.show');

        // Tin tức
        Route::get('/tin-tuc', [NewsController::class, 'index'])->name('news.index');
        Route::get('/tin-tuc/{slug}', [NewsController::class, 'show'])->name('news.show');

        // Liên hệ
        Route::get('/lien-he', [ContactController::class, 'index'])->name('contact');
        Route::post('/lien-he', [ContactController::class, 'store'])->name('contact.store');

    });
});