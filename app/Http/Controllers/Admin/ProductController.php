<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\ProductList;
use App\Models\ProductCat;
use App\Models\Brand;
use App\Traits\HasMultilingual;
use App\Traits\HasImageUpload;



class ProductController extends Controller
{
    use HasMultilingual;
    use HasImageUpload;

    // Cần khai báo cái này để Trait biết đang làm việc với bảng nào (để check unique slug)
    public function getTable()
    {
        return 'products';
    }

    private $view = 'admin.products.';
    private $route = 'admin.products.';


    public function index(Request $request)
    {
        $query = Product::with(['list', 'cat'])->latest();

        // Tìm kiếm ĐA NGÔN NGỮ (Dynamic Search)
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {

                foreach ($this->getLangs() as $key => $name) {
                    $q->orWhere('ten' . $key, 'like', "%{$search}%");
                }
                $q->orWhere('masp', 'like', "%{$search}%");
            });
        }


        if ($request->filled('id_list'))
            $query->where('id_list', $request->input('id_list'));
        if ($request->filled('id_cat')) {
            $query->where('id_cat', $request->input('id_cat'));
        }

        if ($request->filled('id_brand')) {
            $query->where('id_brand', $request->input('id_brand'));
        }

        if ($request->filled('hienthi')) {
            $query->where('hienthi', $request->input('hienthi'));
        }

        $products = $query->paginate(15)->appends($request->query());
        $lists = ProductList::where('hienthi', true)->orderBy('stt', 'asc')->get();
        $cats = ProductCat::where('hienthi', true)->orderBy('stt', 'asc')->get();
        $brands = Brand::where('hienthi', true)->orderBy('stt', 'asc')->get();

        return view($this->view . 'index', compact('products', 'lists', 'cats', 'brands'));
    }

    public function create()
    {
        $lists = ProductList::where('hienthi', true)->orderBy('stt', 'asc')->get();
        $brands = Brand::where('hienthi', true)->orderBy('stt', 'asc')->get();
        return view($this->view . 'create', compact('lists', 'brands'));
    }

    public function store(Request $request)
    {
        $baseRules = [
            'gia' => 'required|numeric|min:0',
            'id_list' => 'required|integer|not_in:0',
            'id_cat' => 'nullable|integer',
            'id_brand' => 'nullable|integer',
            'photo' => 'nullable|image',
            'gallery.*' => 'image',
            'hienthi' => 'boolean',
            'masp' => 'nullable|max:50|unique:products,masp',
        ];


        $baseAttrs = [
            'gia' => 'Giá bán',
            'id_list' => 'Danh mục cấp 1',
            'photo' => 'Ảnh đại diện',
            'masp' => 'Mã sản phẩm',
        ];

        $rules = $this->makeLangRules($baseRules); // Tự sinh rule vi, en...
        $attrs = $this->makeLangAttributes($baseAttrs); // Tự sinh tên hiển thị vi, en...

        $data = $request->validate($rules, [], $attrs);

        if (empty($data['masp'])) {
            $data['masp'] = 'SP' . date('dmY') . rand(100, 999);
        }

        $data = $this->processLangData($data);

        // Upload ảnh đại diện
        if ($request->hasFile('photo')) {
            $data['photo'] = $this->uploadImage($request->file('photo'), 'products');
        }

        // Tạo Product (Eloquent)
        $product = Product::create($data);

        // Tạo Gallery qua Relationship (Eloquent)
        if ($request->hasFile('gallery')) {
            $default = $this->getDefaultLang();
            $tenDefault = $data['ten' . $default] ?? '';

            foreach ($request->file('gallery') as $key => $file) {
                $path = $this->uploadImage($file, 'products/gallery');
                $product->galleries()->create([
                    'photo' => $path,
                    'tenvi' => $tenDefault,
                    'type' => 'san-pham',
                    'stt' => $key + 1,
                    'hienthi' => 1
                ]);
            }
        }

        return redirect()->route($this->route . 'index')->with('success', 'Thêm mới thành công!');
    }

    public function edit($id)
    {
        // Model findOrFail: tự động 404 nếu không thấy
        $product = Product::with('galleries')->findOrFail($id);
        $lists = ProductList::where('hienthi', true)->get();
        $cats = ProductCat::where('id_list', $product->id_list)->where('hienthi', true)->get();
        $brands = Brand::where('hienthi', true)->orderBy('stt', 'asc')->get();

        return view($this->view . 'edit', compact('product', 'lists', 'cats', 'brands'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $baseRules = [
            'id_list' => 'required|integer|not_in:0',
            'id_cat' => 'nullable|integer',
            'id_brand' => 'nullable|integer',
            'photo' => 'nullable|image|max:2048',
            'gallery.*' => 'image|max:2048',
            'hienthi' => 'boolean',
            'masp' => 'nullable|max:50|unique:products,masp,' . $id,
            'gia' => 'nullable|numeric|min:0',
        ];

        $baseAttrs = [
            'gia' => 'Giá bán',
            'id_list' => 'Danh mục cấp 1',
            'photo' => 'Ảnh đại diện',
            'masp' => 'Mã sản phẩm',
        ];

        $rules = $this->makeLangRules($baseRules, $id);
        $attrs = $this->makeLangAttributes($baseAttrs);

        $data = $request->validate($rules, [], $attrs);

        if (empty($data['masp'])) {
            $data['masp'] = 'SP' . date('dmY') . rand(100, 999);
        }

        $data = $this->processLangData($data);

        // Xử lý Ảnh đại diện
        if ($request->hasFile('photo')) {
            $this->deleteImage($product->photo);
            $data['photo'] = $this->uploadImage($request->file('photo'), 'products');
        }

        // Cập nhật Product
        $product->update($data);

        // Xóa Gallery đã chọn 
        if ($request->has('delete_gallery')) {
            $galleriesToDelete = $product->galleries()->whereIn('id', $request->delete_gallery)->get();
            foreach ($galleriesToDelete as $gallery) {
                $this->deleteImage($gallery->photo);
                $gallery->delete();
            }
        }

        // Thêm Gallery mới 
        if ($request->hasFile('gallery')) {
            $lastStt = $product->galleries()->max('stt') ?? 0;

            $default = $this->getDefaultLang();
            $tenDefault = $data['ten' . $default] ?? '';

            foreach ($request->file('gallery') as $key => $file) {
                $path = $this->uploadImage($file, 'products/gallery');
                $product->galleries()->create([
                    'photo' => $path,
                    'tenvi' => $tenDefault,
                    'type' => 'san-pham',
                    'stt' => $lastStt + $key + 1,
                    'hienthi' => 1
                ]);
            }
        }

        return back()->with('success', 'Cập nhật thành công!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Xóa ảnh đại diện
        $this->deleteImage($product->photo);

        // Xóa ảnh Gallery (Loop qua relationship để xóa file)
        foreach ($product->galleries as $gallery) {
            $this->deleteImage($gallery->photo);
            // $gallery->delete(); // Không cần gọi dòng này nếu đã set onDelete('cascade') trong migration
        }

        // Nếu chưa set cascade trong database thì dùng dòng dưới để xóa records gallery:
        $product->galleries()->delete();

        // Xóa Product
        $product->delete();


        return redirect()->route($this->route . 'index')->with('success', 'Xóa thành công!');
    }

    // --- Helper function đã được thay thế bằng Trait (HasImageUpload) ---

    // API Get Cats
    public function getCats($id_list)
    {
        return response()->json(
            ProductCat::where('id_list', $id_list)->where('hienthi', true)->orderBy('stt')->get(['id', 'tenvi'])
        );
    }
}