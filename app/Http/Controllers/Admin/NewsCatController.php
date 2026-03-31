<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NewsCat;
use App\Models\NewsList;
use App\Traits\HasMultilingual;
use App\Traits\HasImageUpload;

class NewsCatController extends Controller
{
    use HasMultilingual, HasImageUpload;

    public function getTable()
    {
        return 'news_cats';
    }

    private $view = 'admin.news_cats.';
    private $route = 'admin.news-cats.';

    public function index(Request $request)
    {
        $query = NewsCat::with('list')->orderBy('stt', 'asc')->latest();

        // Tìm kiếm đa ngôn ngữ
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                foreach ($this->getLangs() as $key => $name) {
                    $q->orWhere('ten' . $key, 'like', "%{$search}%");
                }
            });
        }

        if ($request->filled('id_list')) {
            $query->where('id_list', $request->input('id_list'));
        }

        if ($request->filled('hienthi')) {
            $query->where('hienthi', $request->input('hienthi'));
        }

        $cats = $query->paginate(15)->appends($request->query());
        $lists = NewsList::where('hienthi', true)->orderBy('stt', 'asc')->get();

        return view($this->view . 'index', compact('cats', 'lists'));
    }

    public function create()
    {
        $lists = NewsList::where('hienthi', true)->orderBy('stt', 'asc')->get();
        return view($this->view . 'create', compact('lists'));
    }

    public function store(Request $request)
    {
        $baseRules = [
            'id_list' => 'required|integer|not_in:0',
            'photo' => 'nullable|image|max:2048',
            'stt' => 'nullable|integer|min:0',
            'hienthi' => 'boolean',
            'noibat' => 'boolean',
        ];

        $baseAttrs = [
            'id_list' => 'Danh mục cấp 1',
            'photo' => 'Ảnh đại diện',
            'stt' => 'Số thứ tự',
        ];

        $rules = $this->makeLangRules($baseRules);
        $attrs = $this->makeLangAttributes($baseAttrs);

        $data = $request->validate($rules, [], $attrs);

        $data = $this->processLangData($data);

        if ($request->hasFile('photo')) {
            $data['photo'] = $this->uploadImage($request->file('photo'), 'news_cats');
        }

        NewsCat::create($data);

        return redirect()->route($this->route . 'index')->with('success', 'Thêm danh mục cấp 2 thành công!');
    }

    public function edit($id)
    {
        $cat = NewsCat::findOrFail($id);
        $lists = NewsList::where('hienthi', true)->orderBy('stt', 'asc')->get();

        return view($this->view . 'edit', compact('cat', 'lists'));
    }

    public function update(Request $request, $id)
    {
        $cat = NewsCat::findOrFail($id);

        $baseRules = [
            'id_list' => 'required|integer|not_in:0',
            'photo' => 'nullable|image|max:2048',
            'stt' => 'nullable|integer|min:0',
            'hienthi' => 'boolean',
            'noibat' => 'boolean',
        ];

        $baseAttrs = [
            'id_list' => 'Danh mục cấp 1',
            'photo' => 'Ảnh đại diện',
            'stt' => 'Số thứ tự',
        ];

        $rules = $this->makeLangRules($baseRules, $id);
        $attrs = $this->makeLangAttributes($baseAttrs);

        $data = $request->validate($rules, [], $attrs);

        $data = $this->processLangData($data);

        if ($request->hasFile('photo')) {
            $this->deleteImage($cat->photo);
            $data['photo'] = $this->uploadImage($request->file('photo'), 'news_cats');
        }

        $cat->update($data);

        return back()->with('success', 'Cập nhật danh mục cấp 2 thành công!');
    }

    public function destroy($id)
    {
        $cat = NewsCat::findOrFail($id);
        $this->deleteImage($cat->photo);
        $cat->delete();

        return redirect()->route($this->route . 'index')->with('success', 'Xóa danh mục cấp 2 thành công!');
    }
}
