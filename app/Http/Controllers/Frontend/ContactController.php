<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\HomeService;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function __construct(private HomeService $homeService) {}

    /**
     * Trang liên hệ.
     */
    public function index(Request $request, $lang)
    {
        $seo = $this->homeService->getSeoData('contact');
        return view('pages.contact.index', compact('seo'));
    }
}
