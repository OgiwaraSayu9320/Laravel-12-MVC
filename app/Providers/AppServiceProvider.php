<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Chia sẻ biến $config_langs cho TOÀN BỘ view
        // Ra view chỉ cần gọi $config_langs là có, khỏi cần truyền từ controller
        View::share('config_langs', config('lang.langs'));
        View::share('default_lang', config('lang.default_lang'));
    }
}
