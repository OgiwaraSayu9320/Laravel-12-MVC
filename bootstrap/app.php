<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        // Đăng ký thêm route Admin tại đây
        then: function () {
            Route::middleware('web')
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Cấu hình hướng điều hướng khi chưa đăng nhập
        $middleware->redirectGuestsTo(function (Request $request) {
            // Nếu đường dẫn bắt đầu bằng admin/* thì chuyển về trang login của admin
            if ($request->is('admin/*')) {
                return route('admin.login');
            }

            // Mặc định cho user thường (nếu bạn chưa làm user login thì tạm thời để null hoặc route('login') nếu có)
            return route('login');
        });

        // Đăng ký alias cho middleware phân quyền admin role
        $middleware->alias([
            'role' => \App\Http\Middleware\AdminRoleMiddleware::class,
            'setlocale' => \App\Http\Middleware\SetLocale::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
