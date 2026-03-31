<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\HomeService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct(private HomeService $homeService) {}

    /**
     * Trang chủ.
     */
    public function index(Request $request, $lang = null)
    {
        $data = $this->homeService->getHomeData();
        return view('pages.home.index', $data);
    }
}
