<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Traits\HasMultilingual;
use App\Traits\HasImageUpload;

class BrandController extends Controller
{
    use HasMultilingual, HasImageUpload;

    public function getTable()
    {
        return 'brands';
    }

    private $view = 'admin.brands.';
    private $route = 'admin.brands.';

    public function index(Request $request)
    {
        $query = Brand::query()->orderBy('stt', 'asc')->latest();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                foreach ($this->getLangs() as $key => $name) {
                    $q->orWhere('ten' . $key, 'like', "%{$search}%");
                }
            });
        }

        if ($request->filled('hienthi')) {
            $query->where('hienthi', $request->input('hienthi'));
        }

        $brands = $query->paginate(15)->appends($request->query());

        return view($this->view . 'index', compact('brands'));
    }

    public function create()
    {
        return view($this->view . 'create');
    }

    public function store(Request $request)
    {
        $baseRules = [
            'photo' => 'nullable|image|max:2048',
            'stt' => 'nullable|integer|min:0',
            'hienthi' => 'boolean',
            'noibat' => 'boolean',
        ];

        $baseAttrs = [
            'photo' => 'Logo thương hiệu',
            'stt' => 'Số thứ tự',
        ];

        $rules = $this->makeLangRules($baseRules);
        $attrs = $this->makeLangAttributes($baseAttrs);

        $data = $request->validate($rules, [], $attrs);
        $data = $this->processLangData($data);

        if ($request->hasFile('photo')) {
            $data['photo'] = $this->uploadImage($request->file('photo'), 'brands');
        }

        Brand::create($data);

        return redirect()->route($this->route . 'index')->with('success', 'Thêm thương hiệu thành công!');
    }

    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        return view($this->view . 'edit', compact('brand'));
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $baseRules = [
            'photo' => 'nullable|image|max:2048',
            'stt' => 'nullable|integer|min:0',
            'hienthi' => 'boolean',
            'noibat' => 'boolean',
        ];

        $baseAttrs = [
            'photo' => 'Logo thương hiệu',
            'stt' => 'Số thứ tự',
        ];

        $rules = $this->makeLangRules($baseRules, $id);
        $attrs = $this->makeLangAttributes($baseAttrs);

        $data = $request->validate($rules, [], $attrs);
        $data = $this->processLangData($data);

        if ($request->hasFile('photo')) {
            $this->deleteImage($brand->photo);
            $data['photo'] = $this->uploadImage($request->file('photo'), 'brands');
        }

        $brand->update($data);

        return back()->with('success', 'Cập nhật thương hiệu thành công!');
    }

    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        $this->deleteImage($brand->photo);
        $brand->delete();

        return redirect()->route($this->route . 'index')->with('success', 'Xóa thương hiệu thành công!');
    }
}
