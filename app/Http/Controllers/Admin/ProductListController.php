<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\ProductList;
use App\Traits\HasMultilingual;
use App\Traits\HasImageUpload;

class ProductListController extends Controller
{
    use HasMultilingual;
    use HasImageUpload;

    public function getTable()
    {
        return 'product_lists'; 
    }

    private $view = 'admin.product_lists.'; 
    private $route = 'admin.product_lists.';  
    public function index(Request $request)
    {
        $query = ProductList::query()->latest('stt');

        // Tìm kiếm đa ngôn ngữ
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                foreach ($this->getLangs() as $key => $name) {
                    $q->orWhere('ten' . $key, 'like', "%{$search}%");
                }
            });
        }

        if ($request->filled('hienthi')) {
            $query->where('hienthi', $request->boolean('hienthi'));
        }

        $lists = $query->paginate(15)->appends($request->query());

        return view($this->view . 'index', compact('lists'));
    }

    public function create()
    {
        return view($this->view . 'create');
    }

    public function store(Request $request)
    {
        $baseRules = [
            'photo' => 'nullable|image|max:2048',
            'hienthi' => 'boolean',
            'stt' => 'nullable|integer|min:0',
            'noibat' => 'boolean',
        ];

        $baseAttrs = [
            'photo' => 'Ảnh đại diện',
            'stt' => 'Số thứ tự',
        ];

        $rules = $this->makeLangRules($baseRules);
        $attrs = $this->makeLangAttributes($baseAttrs);

        $data = $request->validate($rules, [], $attrs);

        $data = $this->processLangData($data);

        // Xử lý ảnh
        if ($request->hasFile('photo')) {
            $data['photo'] = $this->uploadImage($request->file('photo'), 'product-lists');
        }

        ProductList::create($data);

        return redirect()
            ->route($this->route . 'index')
            ->with('success', 'Thêm danh mục cấp 1 thành công!');
    }

    public function edit($id)
    {
        $list = ProductList::findOrFail($id);
        return view($this->view . 'edit', compact('list'));
    }

    public function update(Request $request, $id)
    {
        $list = ProductList::findOrFail($id);

        $baseRules = [
            'photo' => 'nullable|image|max:2048',
            'hienthi' => 'boolean',
            'stt' => 'nullable|integer|min:0',
            'noibat' => 'boolean',
        ];

        $baseAttrs = [
            'photo' => 'Ảnh đại diện',
            'stt' => 'Số thứ tự',
        ];

        $rules = $this->makeLangRules($baseRules, $id);
        $attrs = $this->makeLangAttributes($baseAttrs);

        $data = $request->validate($rules, [], $attrs);

        $data = $this->processLangData($data);

        // Xử lý ảnh đại diện
        if ($request->hasFile('photo')) {
            $this->deleteImage($list->photo);
            $data['photo'] = $this->uploadImage($request->file('photo'), 'product-lists');
        }

        $list->update($data);

        return back()->with('success', 'Cập nhật danh mục cấp 1 thành công!');
    }

    public function destroy($id)
    {
        $list = ProductList::findOrFail($id);

        // Xóa ảnh đại diện nếu có
        $this->deleteImage($list->photo);
        $list->products()->update(['id_list' => null]);
        $list->cats()->update(['id_list' => null]);

        $list->delete();

        return redirect()
            ->route($this->route . 'index')
            ->with('success', 'Xóa danh mục cấp 1 thành công!');
    }

    // --- Helper đã được thay thế bằng Trait (HasImageUpload) ---
}