<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductList;
use App\Models\ProductCat;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

class ProductService
{
    /**
     * Lấy danh sách sản phẩm có phân trang và lọc.
     */
    public function getListing(array $filters = [], int $perPage = 12): LengthAwarePaginator
    {
        $lang = App::getLocale();
        $query = Product::with(['list', 'cat', 'brand'])
            ->where('hienthi', true)
            ->orderBy('stt', 'asc')
            ->latest();

        if (!empty($filters['id_list'])) {
            $query->where('id_list', $filters['id_list']);
        }

        if (!empty($filters['id_cat'])) {
            $query->where('id_cat', $filters['id_cat']);
        }

        if (!empty($filters['id_brand'])) {
            $query->where('id_brand', $filters['id_brand']);
        }

        if (!empty($filters['search'])) {
            $query->where('ten' . $lang, 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['noibat'])) {
            $query->where('noibat', true);
        }

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Lấy chi tiết sản phẩm qua slug (tenkhongdauvi hoặc tenkhongdauen).
     */
    public function getDetail(string $slug): ?Product
    {
        $lang = App::getLocale();
        return Product::where('tenkhongdau' . $lang, $slug)
            ->where('hienthi', true)
            ->with(['list', 'cat', 'brand', 'galleries'])
            ->firstOrFail();
    }

    /**
     * Lấy sản phẩm liên quan.
     */
    public function getRelated(Product $product, int $limit = 8): Collection
    {
        return Product::where('id_list', $product->id_list)
            ->where('id', '!=', $product->id)
            ->where('hienthi', true)
            ->orderBy('stt', 'asc')
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * Tăng lượt xem sản phẩm.
     */
    public function incrementView(Product $product): void
    {
        $product->increment('luotxem');
    }

    /**
     * Lấy toàn bộ danh mục cấp 1 phục vụ menu/filter.
     */
    public function getAllLists(): Collection
    {
        return ProductList::where('hienthi', true)
            ->orderBy('stt', 'asc')
            ->get();
    }

    /**
     * Lấy danh mục cấp 2 theo cấp 1.
     */
    public function getCatsByList(int $listId): Collection
    {
        return ProductCat::where('id_list', $listId)
            ->where('hienthi', true)
            ->orderBy('stt', 'asc')
            ->get();
    }
}
