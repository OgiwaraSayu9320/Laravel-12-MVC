<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use App\Services\HomeService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService,
        private HomeService $homeService
    ) {}

    /**
     * Danh sách sản phẩm.
     */
    public function index(Request $request, $lang)
    {
        $filters = $request->only(['id_list', 'id_cat', 'id_brand', 'search']);
        $products = $this->productService->getListing($filters);
        $lists = $this->productService->getAllLists();
        $seo = $this->homeService->getSeoData('products');

        return view('pages.products.index', compact('products', 'lists', 'seo'));
    }

    /**
     * Chi tiết sản phẩm.
     */
    public function show(Request $request, $lang, $slug)
    {
        $product = $this->productService->getDetail($slug);
        $this->productService->incrementView($product);
        $related = $this->productService->getRelated($product);

        return view('pages.products.show', compact('product', 'related'));
    }
}
