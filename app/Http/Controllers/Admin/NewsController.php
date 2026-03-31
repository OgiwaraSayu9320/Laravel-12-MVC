<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News;
use App\Models\NewsList;
use App\Models\NewsCat;
use App\Traits\HasMultilingual;
use App\Traits\HasImageUpload;

class NewsController extends Controller
{
    use HasMultilingual, HasImageUpload;

    public function getTable()
    {
        return 'news';
    }

    private $view = 'admin.news.';
    private $route = 'admin.news.';

    public function index(Request $request)
    {
        $query = News::with(['list', 'cat'])->orderBy('stt', 'asc')->latest();

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

        if ($request->filled('id_cat')) {
            $query->where('id_cat', $request->input('id_cat'));
        }

        if ($request->filled('hienthi')) {
            $query->where('hienthi', $request->input('hienthi'));
        }

        $news = $query->paginate(15)->appends($request->query());
        $lists = NewsList::where('hienthi', true)->orderBy('stt', 'asc')->get();
        $cats = NewsCat::where('hienthi', true)->orderBy('stt', 'asc')->get();

        return view($this->view . 'index', compact('news', 'lists', 'cats'));
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
            'id_cat' => 'nullable|integer',
            'photo' => 'nullable|image|max:2048',
            'gallery.*' => 'image|max:2048',
            'stt' => 'nullable|integer|min:0',
            'hienthi' => 'boolean',
            'noibat' => 'boolean',
        ];

        $baseAttrs = [
            'id_list' => 'Danh mục cấp 1',
            'photo' => 'Ảnh đại diện bài viết',
        ];

        $rules = $this->makeLangRules($baseRules);
        $attrs = $this->makeLangAttributes($baseAttrs);

        $data = $request->validate($rules, [], $attrs);

        $data = $this->processLangData($data);

        if ($request->hasFile('photo')) {
            $data['photo'] = $this->uploadImage($request->file('photo'), 'news');
        }

        $item = News::create($data);

        // Gallery
        if ($request->hasFile('gallery')) {
            $default = $this->getDefaultLang();
            $tenDefault = $data['ten' . $default] ?? '';

            foreach ($request->file('gallery') as $key => $file) {
                $path = $this->uploadImage($file, 'news/gallery');
                $item->galleries()->create([
                    'photo' => $path,
                    'tenvi' => $tenDefault,
                    'type' => 'bai-viet',
                    'stt' => $key + 1,
                    'hienthi' => 1
                ]);
            }
        }

        return redirect()->route($this->route . 'index')->with('success', 'Thêm bài viết mới thành công!');
    }

    public function edit($id)
    {
        $news = News::with('galleries')->findOrFail($id);
        $lists = NewsList::where('hienthi', true)->orderBy('stt', 'asc')->get();
        $cats = NewsCat::where('id_list', $news->id_list)->where('hienthi', true)->get();

        return view($this->view . 'edit', compact('news', 'lists', 'cats'));
    }

    public function update(Request $request, $id)
    {
        $item = News::findOrFail($id);

        $baseRules = [
            'id_list' => 'required|integer|not_in:0',
            'id_cat' => 'nullable|integer',
            'photo' => 'nullable|image|max:2048',
            'gallery.*' => 'image|max:2048',
            'stt' => 'nullable|integer|min:0',
            'hienthi' => 'boolean',
            'noibat' => 'boolean',
        ];

        $baseAttrs = [
            'id_list' => 'Danh mục cấp 1',
            'photo' => 'Ảnh đại diện',
        ];

        $rules = $this->makeLangRules($baseRules, $id);
        $attrs = $this->makeLangAttributes($baseAttrs);

        $data = $request->validate($rules, [], $attrs);

        $data = $this->processLangData($data);

        if ($request->hasFile('photo')) {
            $this->deleteImage($item->photo);
            $data['photo'] = $this->uploadImage($request->file('photo'), 'news');
        }

        $item->update($data);

        // Delete gallery items
        if ($request->has('delete_gallery')) {
            $toDelete = $item->galleries()->whereIn('id', $request->delete_gallery)->get();
            foreach ($toDelete as $gal) {
                $this->deleteImage($gal->photo);
                $gal->delete();
            }
        }

        // Add new gallery items
        if ($request->hasFile('gallery')) {
            $lastStt = $item->galleries()->max('stt') ?? 0;
            $default = $this->getDefaultLang();
            $tenDefault = $data['ten' . $default] ?? '';

            foreach ($request->file('gallery') as $key => $file) {
                $path = $this->uploadImage($file, 'news/gallery');
                $item->galleries()->create([
                    'photo' => $path,
                    'tenvi' => $tenDefault,
                    'type' => 'bai-viet',
                    'stt' => $lastStt + $key + 1,
                    'hienthi' => 1
                ]);
            }
        }

        return back()->with('success', 'Cập nhật bài viết thành công!');
    }

    public function destroy($id)
    {
        $item = News::findOrFail($id);

        // Delete photo
        $this->deleteImage($item->photo);

        // Delete gallery
        foreach ($item->galleries as $gal) {
            $this->deleteImage($gal->photo);
        }
        $item->galleries()->delete();

        $item->delete();

        return redirect()->route($this->route . 'index')->with('success', 'Xóa bài viết thành công!');
    }

    // Ajax call for level 2 dropdown
    public function getCats($id_list)
    {
        return response()->json(
            NewsCat::where('id_list', $id_list)->where('hienthi', true)->orderBy('stt')->get(['id', 'tenvi'])
        );
    }
}
