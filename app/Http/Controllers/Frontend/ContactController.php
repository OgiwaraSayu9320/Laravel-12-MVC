<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\HomeService;
use App\Services\ContactService;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function __construct(
        private HomeService $homeService,
        private ContactService $contactService
    ) {}

    /**
     * Trang liên hệ.
     */
    public function index(Request $request, $lang)
    {
        $seo = $this->homeService->getSeoData('contact');
        return view('pages.contact.index', compact('seo'));
    }

    /**
     * Xử lý gửi form liên hệ.
     */
    public function store(Request $request, $lang)
    {
        $request->validate([
            'ho_ten'   => 'required|string|max:255',
            'email'    => 'required|email|max:255',
            'phone'    => 'nullable|string|max:20',
            'noi_dung' => 'required|string|min:5',
            'type'     => 'nullable|string|max:50',
        ]);

        $this->contactService->submit($request->all());

        return back()->with('success', __('Cám ơn bạn! Chúng tôi sẽ phản hồi sớm nhất.'));
    }
}
