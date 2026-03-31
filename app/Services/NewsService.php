<?php

namespace App\Services;

use App\Models\News;
use App\Models\NewsList;
use App\Models\NewsCat;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

class NewsService
{
    /**
     * Lấy danh sách bài viết có phân trang và lọc.
     */
    public function getListing(array $filters = [], int $perPage = 12): LengthAwarePaginator
    {
        $lang = App::getLocale();
        $query = News::with(['list', 'cat'])
            ->where('hienthi', true)
            ->orderBy('stt', 'asc')
            ->latest();

        if (!empty($filters['id_list'])) {
            $query->where('id_list', $filters['id_list']);
        }

        if (!empty($filters['id_cat'])) {
            $query->where('id_cat', $filters['id_cat']);
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
     * Lấy chi tiết bài viết qua slug.
     */
    public function getDetail(string $slug): ?News
    {
        $lang = App::getLocale();
        return News::where('tenkhongdau' . $lang, $slug)
            ->where('hienthi', true)
            ->with(['list', 'cat', 'galleries'])
            ->firstOrFail();
    }

    /**
     * Lấy bài viết liên quan.
     */
    public function getRelated(News $news, int $limit = 8): Collection
    {
        return News::where('id_list', $news->id_list)
            ->where('id', '!=', $news->id)
            ->where('hienthi', true)
            ->orderBy('stt', 'asc')
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * Tăng lượt xem bài viết.
     */
    public function incrementView(News $news): void
    {
        $news->increment('luotxem');
    }

    /**
     * Lấy toàn bộ danh mục cấp 1 phục vụ menu/filter.
     */
    public function getAllLists(): Collection
    {
        return NewsList::where('hienthi', true)
            ->orderBy('stt', 'asc')
            ->get();
    }
}
