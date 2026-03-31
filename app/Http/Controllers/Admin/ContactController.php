<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Services\ContactService;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    private string $view  = 'admin.contacts.';
    private string $route = 'admin.contacts.';

    public function __construct(private ContactService $contactService) {}

    /**
     * Danh sách liên hệ.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['type', 'da_doc', 'search']);
        $items = $this->contactService->getListing($filters);

        return view($this->view . 'index', compact('items'));
    }

    /**
     * Xem chi tiết liên hệ.
     */
    public function show($id)
    {
        $item = Contact::findOrFail($id);
        
        // Tự động đánh dấu đã đọc khi xem
        if (!$item->da_doc) {
            $this->contactService->markAsRead($item);
        }

        return view($this->view . 'show', compact('item'));
    }

    /**
     * Xoá liên hệ.
     */
    public function destroy($id)
    {
        $item = Contact::findOrFail($id);
        $item->delete();

        return redirect()->route($this->route . 'index')->with('success', 'Đã xoá liên hệ thành công!');
    }
}
