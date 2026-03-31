<?php

namespace App\Services;

use App\Models\SeoPage;
use Illuminate\Support\Collection;

class HomeService
{
    public function __construct(
        private ProductService $productService,
        private NewsService $newsService
    ) {}

    /**
     * Lấy toàn bộ dữ liệu cho trang chủ.
     */
    public function getHomeData(): array
    {
        return [
            'featuredProducts' => $this->productService->getListing(['noibat' => true], 8),
            'latestNews' => $this->newsService->getListing(['noibat' => true], 4),
            'seo' => $this->getSeoData('home'),
        ];
    }

    /**
     * Lấy dữ liệu SEO cho trang tĩnh.
     */
    public function getSeoData(string $slug): ?SeoPage
    {
        return SeoPage::where('slug', $slug)->first();
    }
}
