@extends('layouts.main')

@php
    $lang = app()->getLocale();
@endphp

@section('title', $seo ? ($lang == 'vi' ? $seo->titlevi : $seo->titleen) : 'Products')

@section('seo')
@if($seo)
    <meta name="description" content="{{ $lang == 'vi' ? $seo->descriptionvi : $seo->descriptionen }}">
    <meta name="keywords" content="{{ $lang == 'vi' ? $seo->keywordsvi : $seo->keywordsen }}">
@endif
@endsection

@section('content')
    <section style="padding: 40px 0; background: #eef2ff;">
        <div class="container" style="text-align: center;">
            <h1 style="font-size: 2.5rem; font-weight: 800; margin-bottom: 10px;">
                {{ $lang == 'vi' ? 'Sản Phẩm Của Chúng Tôi' : 'Our Products' }}
            </h1>
            <p style="color: #6366f1; font-weight: 600;">
                {{ $lang == 'vi' ? 'Chất lượng hàng đầu - Giá cả cạnh tranh' : 'Top Quality - Competitive Price' }}
            </p>
        </div>
    </section>

    <section style="padding: 60px 0;">
        <div class="container" style="display: grid; grid-template-columns: 280px 1fr; gap: 40px;">
            <!-- Sidebar Filters -->
            <aside class="sidebar">
                <div style="background: white; border: 1px solid var(--border-color); border-radius: 1rem; padding: 25px; position: sticky; top: 100px;">
                    <h3 style="font-size: 1.125rem; font-weight: 700; margin-bottom: 20px; border-bottom: 2px solid var(--primary-color); padding-bottom: 10px;">
                        {{ $lang == 'vi' ? 'Danh mục sản phẩm' : 'Categories' }}
                    </h3>
                    <ul style="list-style: none; padding: 0;">
                        <li>
                            <a href="{{ route('products.index', ['lang' => $lang]) }}"
                               style="display: block; padding: 10px 0; color: {{ !request('id_list') ? 'var(--primary-color)' : 'var(--text-color)' }}; text-decoration: none; font-weight: {{ !request('id_list') ? '700' : '500' }};">
                                {{ $lang == 'vi' ? 'Tất cả sản phẩm' : 'All Products' }}
                            </a>
                        </li>
                        @foreach($lists as $item)
                            <li>
                                <a href="{{ route('products.index', ['lang' => $lang, 'id_list' => $item->id]) }}"
                                   style="display: block; padding: 10px 0; color: {{ request('id_list') == $item->id ? 'var(--primary-color)' : 'var(--text-color)' }}; text-decoration: none; font-weight: {{ request('id_list') == $item->id ? '700' : '500' }}; border-top: 1px solid #f3f4f6;">
                                    {{ $lang == 'vi' ? $item->tenvi : $item->tenen }}
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <form action="{{ route('products.index', ['lang' => $lang]) }}" method="GET" style="margin-top: 30px;">
                        @if(request('id_list')) <input type="hidden" name="id_list" value="{{ request('id_list') }}"> @endif
                        <h3 style="font-size: 1.125rem; font-weight: 700; margin-bottom: 15px;">{{ $lang == 'vi' ? 'Tìm kiếm' : 'Search' }}</h3>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="{{ $lang == 'vi' ? 'Nhập tên sản phẩm...' : 'Enter product name...' }}"
                               style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 0.5rem; margin-bottom: 10px;">
                        <button type="submit" class="btn btn-primary" style="width: 100%;">{{ $lang == 'vi' ? 'Lọc kết quả' : 'Filter' }}</button>
                    </form>
                </div>
            </aside>

            <!-- Product List -->
            <div class="content">
                <div class="product-grid">
                    @forelse($products as $product)
                        <div class="product-card">
                            @php
                                $slug = $lang == 'vi' ? $product->tenkhongdauvi : $product->tenkhongdauen;
                                $slug = $slug ?: 'product-' . $product->id;
                            @endphp
                            <a href="{{ route('products.show', ['lang' => $lang, 'slug' => $slug]) }}">
                                <img src="{{ $product->photo ? asset('storage/' . $product->photo) : asset('admin_assets/images/noimage.png') }}" class="product-img" alt="{{ $lang == 'vi' ? $product->tenvi : $product->tenen }}">
                            </a>
                            <div class="product-info">
                                <a href="{{ route('products.show', ['lang' => $lang, 'slug' => $slug]) }}" class="product-name">
                                    {{ $lang == 'vi' ? $product->tenvi : $product->tenen }}
                                </a>
                                <div class="product-price">
                                    @if($product->giamoi > 0)
                                        {{ number_format($product->giamoi) }} đ
                                    @else
                                        {{ $product->gia > 0 ? number_format($product->gia) . ' đ' : ($lang == 'vi' ? 'Liên hệ' : 'Contact Us') }}
                                    @endif
                                </div>
                                <a href="{{ route('products.show', ['lang' => $lang, 'slug' => $lang == 'vi' ? $product->tenkhongdauvi : $product->tenkhongdauen]) }}" class="btn btn-primary" style="width: 100%; text-align: center; margin-top: auto;">
                                    {{ $lang == 'vi' ? 'Chi tiết' : 'Details' }}
                                </a>
                            </div>
                        </div>
                    @empty
                        <div style="grid-column: 1/-1; text-align: center; padding: 100px 0;">
                            <p style="font-size: 1.25rem; color: #9ca3af;">{{ $lang == 'vi' ? 'Không tìm thấy sản phẩm nào khớp với yêu cầu.' : 'No products found.' }}</p>
                            <a href="{{ route('products.index', ['lang' => $lang]) }}" style="color: var(--primary-color); text-decoration: underline;">{{ $lang == 'vi' ? 'Xem lại tất cả sản phẩm' : 'Reset filters' }}</a>
                        </div>
                    @endforelse
                </div>

                <div style="margin-top: 50px;">
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </section>
@endsection
