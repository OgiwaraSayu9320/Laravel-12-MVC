<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Lấy ngôn ngữ từ segment đầu tiên (ví dụ: /vi/san-pham)
        $lang = $request->segment(1);
        $langs = config('lang.langs');

        // Nếu ngôn ngữ nằm trong danh sách hỗ trợ
        if (array_key_exists($lang, $langs)) {
            App::setLocale($lang);
        } else {
            // Mặc định về ngôn ngữ cấu hình nếu không thấy hoặc không khớp
            App::setLocale(config('lang.default_lang', 'vi'));
        }

        return $next($request);
    }
}
