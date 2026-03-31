<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\NewsService;
use App\Services\HomeService;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function __construct(
        private NewsService $newsService,
        private HomeService $homeService
    ) {}

    /**
     * Danh sách bài viết.
     */
    public function index(Request $request, $lang)
    {
        $filters = $request->only(['id_list', 'id_cat', 'search']);
        $news = $this->newsService->getListing($filters);
        $lists = $this->newsService->getAllLists();
        $seo = $this->homeService->getSeoData('news');

        return view('pages.news.index', compact('news', 'lists', 'seo'));
    }

    /**
     * Chi tiết bài viết.
     */
    public function show(Request $request, $lang, $slug)
    {
        $news = $this->newsService->getDetail($slug);
        $this->newsService->incrementView($news);
        $related = $this->newsService->getRelated($news);

        return view('pages.news.show', compact('news', 'related'));
    }
}
