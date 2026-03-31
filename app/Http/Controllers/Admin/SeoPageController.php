<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SeoPage;
use App\Traits\HasImageUpload;

class SeoPageController extends Controller
{
    use HasImageUpload;

    // Danh sách các trang tĩnh
    public function index()
    {
        $pages = SeoPage::all();
        return view('admin.seo_pages.index', compact('pages'));
    }

    // Form chỉnh sửa
    public function edit($id)
    {
        $page = SeoPage::findOrFail($id);
        return view('admin.seo_pages.edit', compact('page'));
    }

    // Cập nhật
    public function update(Request $request, $id)
    {
        $page = SeoPage::findOrFail($id);
        
        $data = $request->validate([
            'title_seo' => 'required|max:255',
            'desc_seo' => 'nullable',
            'keyword_seo' => 'nullable',
            'image_seo' => 'nullable|image|max:2048'
        ], [], [
            'title_seo' => 'Tiêu đề SEO',
            'desc_seo' => 'Mô tả SEO',
            'keyword_seo' => 'Từ khóa SEO',
            'image_seo' => 'Ảnh đại diện SEO'
        ]);

        // Xử lý upload ảnh SEO
        if ($request->hasFile('image_seo')) {
            $this->deleteImage($page->image_seo);
            $data['image_seo'] = $this->uploadImage($request->file('image_seo'), 'seos');
        }

        $page->update($data);

        return redirect()->route('admin.seo-pages.index')->with('success', 'Cập nhật SEO thành công');
    }
}