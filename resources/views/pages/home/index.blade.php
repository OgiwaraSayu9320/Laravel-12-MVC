@extends('layouts.main')

@php
    $lang = app()->getLocale();
@endphp

@section('title', $seo ? ($lang == 'vi' ? $seo->titlevi : $seo->titleen) : 'Home')

@section('seo')
@if($seo)
    <meta name="description" content="{{ $lang == 'vi' ? $seo->descriptionvi : $seo->descriptionen }}">
    <meta name="keywords" content="{{ $lang == 'vi' ? $seo->keywordsvi : $seo->keywordsen }}">
@endif
@endsection

@section('content')
    <!-- Hero Section -->
    <section class="hero" style="background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%); padding: 100px 0; color: white; text-align: center;">
        <div class="container">
            <h1 style="font-size: 3.5rem; font-weight: 800; margin-bottom: 20px;">
                {{ $lang == 'vi' ? 'Giải Pháp CMS Đa Ngôn Ngữ' : 'Multilingual CMS Solutions' }}
            </h1>
            <p style="font-size: 1.25rem; opacity: 0.9; max-width: 700px; margin: 0 auto 40px;">
                {{ $lang == 'vi' ? 'Nền tảng quản lý nội dung mạnh mẽ, linh hoạt và tối ưu SEO cho doanh nghiệp.' : 'Powerful, flexible and SEO-optimized content management platform for businesses.' }}
            </p>
            <a href="{{ route('products.index', ['lang' => $lang]) }}" class="btn btn-primary" style="background: white; color: var(--primary-color);">
                {{ $lang == 'vi' ? 'Khám phá ngay' : 'Explore Now' }}
            </a>
        </div>
    </section>

    <!-- Featured Products -->
    <section style="padding: 80px 0; background: white;">
        <div class="container">
            <h2 class="section-title">{{ $lang == 'vi' ? 'Sản phẩm nổi bật' : 'Featured Products' }}</h2>
            <div class="product-grid">
                @forelse($featuredProducts as $product)
                    <div class="product-card">
                        <a href="{{ route('products.show', ['lang' => $lang, 'slug' => $product->slug]) }}">
                            <img src="{{ $product->photo ? asset('storage/' . $product->photo) : asset('admin_assets/images/noimage.png') }}" class="product-img" alt="{{ $product->name }}">
                        </a>
                        <div class="product-info">
                            <a href="{{ route('products.show', ['lang' => $lang, 'slug' => $product->slug]) }}" class="product-name">
                                {{ $product->name }}
                            </a>
                            <div class="product-price">
                                @if($product->giamoi > 0)
                                    {{ number_format($product->giamoi) }} đ
                                    <span style="font-size: 0.9rem; color: #9ca3af; text-decoration: line-through; margin-left: 10px;">{{ number_format($product->gia) }} đ</span>
                                @else
                                    {{ $product->gia > 0 ? number_format($product->gia) . ' đ' : ($lang == 'vi' ? 'Liên hệ' : 'Contact Us') }}
                                @endif
                            </div>
                            <a href="{{ route('products.show', ['lang' => $lang, 'slug' => $product->slug]) }}" class="btn btn-primary" style="width: 100%; text-align: center;">
                                {{ $lang == 'vi' ? 'Xem chi tiết' : 'View Detail' }}
                            </a>
                        </div>
                    </div>
                @empty
                    <p style="text-align: center; grid-column: 1/-1;">{{ $lang == 'vi' ? 'Đang cập nhật sản phẩm...' : 'Updating products...' }}</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Latest News -->
    <section style="padding: 80px 0; background: var(--bg-color);">
        <div class="container">
            <h2 class="section-title">{{ $lang == 'vi' ? 'Tin tức mới nhất' : 'Latest News' }}</h2>
            <div class="news-grid">
                @forelse($latestNews as $item)
                    <div class="news-card">
                        <a href="{{ route('news.show', ['lang' => $lang, 'slug' => $item->slug]) }}">
                            <img src="{{ $item->photo ? asset('storage/' . $item->photo) : asset('admin_assets/images/noimage.png') }}" class="news-img" alt="{{ $item->name }}">
                        </a>
                        <div class="news-info">
                            <a href="{{ route('news.show', ['lang' => $lang, 'slug' => $item->slug]) }}" class="news-title">
                                {{ $item->name }}
                            </a>
                            <p class="news-desc">
                                {{ $item->desc }}
                            </p>
                            <a href="{{ route('news.show', ['lang' => $lang, 'slug' => $item->slug]) }}" style="color: var(--primary-color); font-weight: 600; text-decoration: none;">
                                {{ $lang == 'vi' ? 'Đọc thêm' : 'Read more' }} →
                            </a>
                        </div>
                    </div>
                @empty
                    <p style="text-align: center; grid-column: 1/-1;">{{ $lang == 'vi' ? 'Đang cập nhật tin tức...' : 'Updating news...' }}</p>
                @endforelse
            </div>
        </div>
    </section>
@endsection
