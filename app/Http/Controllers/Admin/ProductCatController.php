<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\ProductCat;
use App\Models\ProductList;
use App\Traits\HasMultilingual;
use App\Traits\HasImageUpload;

class ProductCatController extends Controller
{
    use HasMultilingual;
    use HasImageUpload;

    // 1. Tên bảng trong DB (Sửa lại cho đúng nếu bảng của bác tên khác)
    public function getTable()
    {
        return 'product_cats';
    }

    private $view = 'admin.product_cats.';
    private $route = 'admin.product_cats.';

    public function index(Request $request)
    {
        // Kéo thêm list (Cấp 1) ra để hiển thị ngoài bảng cho dễ nhìn
        $query = ProductCat::with('list')->orderBy('stt', 'asc')->latest();

        // Tìm kiếm Đa ngôn ngữ
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                foreach ($this->getLangs() as $key => $name) {
                    $q->orWhere('ten' . $key, 'like', "%{$search}%");
                }
            });
        }

        // Lọc theo Cấp 1 hoặc Trạng thái
        if ($request->filled('id_list'))
            $query->where('id_list', $request->input('id_list'));
        if ($request->filled('hienthi'))
            $query->where('hienthi', $request->input('hienthi'));

        $cats = $query->paginate(10)->appends($request->query());

        // Truyền $lists ra view để làm bộ lọc (Filter)
        $lists = ProductList::where('hienthi', true)->orderBy('stt', 'asc')->get();

        return view($this->view . 'index', compact('cats', 'lists'));
    }

    public function create()
    {
        // Lấy Cấp 1 ra để đổ vào the Select
        $lists = ProductList::where('hienthi', true)->orderBy('stt', 'asc')->get();
        return view($this->view . 'create', compact('lists'));
    }

    public function store(Request $request)
    {
        $baseRules = [
            'id_list' => 'required|integer|not_in:0',
            'photo' => 'nullable|image|max:2048',
            'stt' => 'nullable|integer',
            'hienthi' => 'boolean',
        ];

        $baseAttrs = [
            'id_list' => 'Danh mục cấp 1',
            'photo' => 'Ảnh đại diện',
            'stt' => 'Số thứ tự',
        ];

        // Dùng Trait (Siêu nhàn)
        $rules = $this->makeLangRules($baseRules);
        $attrs = $this->makeLangAttributes($baseAttrs);

        $data = $request->validate($rules, [], $attrs);

        // Xử lý mặc định nếu rỗng
        $data['hienthi'] = $request->input('hienthi', 1);
        $data['stt'] = $request->input('stt', 1);

        // Tự sinh Slug và auto fill ngôn ngữ bằng Trait
        $data = $this->processLangData($data);

        // Upload ảnh
        if ($request->hasFile('photo')) {
            $data['photo'] = $this->uploadImage($request->file('photo'), 'product_cats');
        }

        ProductCat::create($data);

        return redirect()->route($this->route . 'index')->with('success', 'Thêm mới danh mục thành công!');
    }

    public function edit($id)
    {
        $cat = ProductCat::findOrFail($id);
        $lists = ProductList::where('hienthi', true)->orderBy('stt', 'asc')->get();

        return view($this->view . 'edit', compact('cat', 'lists'));
    }

    public function update(Request $request, $id)
    {
        $cat = ProductCat::findOrFail($id);

        $baseRules = [
            'id_list' => 'required|integer|not_in:0',
            'photo' => 'nullable|image|max:2048',
            'stt' => 'nullable|integer',
            'hienthi' => 'boolean',
        ];

        $baseAttrs = [
            'id_list' => 'Danh mục cấp 1',
            'photo' => 'Ảnh đại diện',
            'stt' => 'Số thứ tự',
        ];

        // Truyền $id vào để nó không bắt lỗi trùng Slug với chính nó
        $rules = $this->makeLangRules($baseRules, $id);
        $attrs = $this->makeLangAttributes($baseAttrs);

        $data = $request->validate($rules, [], $attrs);

        $data['hienthi'] = $request->input('hienthi', 1);
        $data['stt'] = $request->input('stt', 1);

        $data = $this->processLangData($data);

        // Đổi ảnh mới thì xóa ảnh cũ
        if ($request->hasFile('photo')) {
            $this->deleteImage($cat->photo);
            $data['photo'] = $this->uploadImage($request->file('photo'), 'product_cats');
        }

        $cat->update($data);

        return back()->with('success', 'Cập nhật danh mục thành công!');
    }

    public function destroy($id)
    {
        $cat = ProductCat::findOrFail($id);

        // 1. Check xem có danh mục con (Cấp 3) hoặc sản phẩm nào đang dùng Cấp 2 này không
        // (Tùy logic dự án, nếu đang có sản phẩm thì chặn không cho xóa)
        // if ($cat->products()->count() > 0) {
        //     return back()->with('error', 'Không thể xóa vì đang có sản phẩm thuộc danh mục này!');
        // }

        // 2. Xóa ảnh
        $this->deleteImage($cat->photo);

        // 3. Xóa record
        $cat->delete();

        return redirect()->route($this->route . 'index')->with('success', 'Xóa danh mục thành công!');
    }

    // --- Helpers đã được thay thế bằng Trait (HasImageUpload) ---
}