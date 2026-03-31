<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NewsList;
use App\Traits\HasMultilingual;
use App\Traits\HasImageUpload;

class NewsListController extends Controller
{
    use HasMultilingual, HasImageUpload;

    public function getTable()
    {
        return 'news_lists';
    }

    private $view = 'admin.news_lists.';
    private $route = 'admin.news-lists.';

    public function index(Request $request)
    {
        $query = NewsList::query()->orderBy('stt', 'asc')->latest();

        // Tìm kiếm đa ngôn ngữ
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
            'stt' => 'nullable|integer|min:0',
            'hienthi' => 'boolean',
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

        if ($request->hasFile('photo')) {
            $data['photo'] = $this->uploadImage($request->file('photo'), 'news_lists');
        }

        NewsList::create($data);

        return redirect()->route($this->route . 'index')->with('success', 'Thêm danh mục cấp 1 thành công!');
    }

    public function edit($id)
    {
        $list = NewsList::findOrFail($id);
        return view($this->view . 'edit', compact('list'));
    }

    public function update(Request $request, $id)
    {
        $list = NewsList::findOrFail($id);

        $baseRules = [
            'photo' => 'nullable|image|max:2048',
            'stt' => 'nullable|integer|min:0',
            'hienthi' => 'boolean',
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

        if ($request->hasFile('photo')) {
            $this->deleteImage($list->photo);
            $data['photo'] = $this->uploadImage($request->file('photo'), 'news_lists');
        }

        $list->update($data);

        return back()->with('success', 'Cập nhật danh mục cấp 1 thành công!');
    }

    public function destroy($id)
    {
        $list = NewsList::findOrFail($id);
        $this->deleteImage($list->photo);
        $list->delete(); // Relationship boot() handles child updates

        return redirect()->route($this->route . 'index')->with('success', 'Xóa danh mục cấp 1 thành công!');
    }
}
