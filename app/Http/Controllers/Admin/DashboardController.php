<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\News;
use App\Models\ProductList;
use App\Models\ProductCat;
use App\Models\NewsList;
use App\Models\NewsCat;
use App\Models\Brand;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_products'      => Product::count(),
            'visible_products'    => Product::where('hienthi', 1)->count(),
            'total_news'          => News::count(),
            'total_product_lists' => ProductList::count(),
            'total_product_cats'  => ProductCat::count(),
            'total_news_lists'    => NewsList::count(),
            'total_news_cats'     => NewsCat::count(),
            'total_brands'        => Schema::hasTable('brands') ? Brand::count() : 0,
        ];

        $recent_products = Product::latest()->take(5)->get();
        $recent_news = News::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_products', 'recent_news'));
    }
}