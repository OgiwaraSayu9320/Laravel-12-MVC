@extends('layouts.main')

@php
    $lang = app()->getLocale();
@endphp

@section('title', $lang == 'vi' ? $product->titlevi : $product->titleen)

@section('seo')
<meta name="description" content="{{ $lang == 'vi' ? $product->descriptionvi : $product->descriptionen }}">
<meta name="keywords" content="{{ $lang == 'vi' ? $product->keywordsvi : $product->keywordsen }}">
@endsection

@section('content')
    <section style="padding: 40px 0; background: #f3f4f6;">
        <div class="container">
            <ul style="display: flex; list-style: none; padding: 0; font-size: 0.875rem; color: #6b7280; gap: 10px;">
                <li><a href="{{ route('home', ['lang' => $lang]) }}" style="color: inherit; text-decoration: none;">{{ $lang == 'vi' ? 'Trang chủ' : 'Home' }}</a></li>
                <li>/</li>
                <li><a href="{{ route('products.index', ['lang' => $lang]) }}" style="color: inherit; text-decoration: none;">{{ $lang == 'vi' ? 'Sản phẩm' : 'Products' }}</a></li>
                <li>/</li>
                <li style="color: var(--text-color); font-weight: 600;">{{ $lang == 'vi' ? $product->tenvi : $product->tenen }}</li>
            </ul>
        </div>
    </section>

    <section style="padding: 60px 0; background: white;">
        <div class="container" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 60px;">
            <!-- Left: Gallery -->
            <div class="gallery">
                <div style="border: 1px solid var(--border-color); border-radius: 1rem; overflow: hidden; margin-bottom: 20px;">
                    <img src="{{ $product->photo ? asset('storage/' . $product->photo) : asset('admin_assets/images/noimage.png') }}"
                         style="width: 100%; aspect-ratio: 1; object-fit: cover;" id="main-image">
                </div>
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px;">
                    @foreach($product->galleries as $item)
                        <div style="border: 1px solid var(--border-color); border-radius: 0.5rem; overflow: hidden; cursor: pointer;">
                            <img src="{{ asset('storage/' . $item->photo) }}" style="width: 100%; aspect-ratio: 1; object-fit: cover;"
                                 onclick="document.getElementById('main-image').src = this.src">
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Right: Product Info -->
            <div class="product-detail-info">
                <h1 style="font-size: 2.25rem; font-weight: 800; margin: 0 0 15px; color: var(--text-color);">
                    {{ $lang == 'vi' ? $product->tenvi : $product->tenen }}
                </h1>

                <div style="display: flex; gap: 20px; align-items: center; margin-bottom: 25px;">
                    <div style="font-size: 1.5rem; font-weight: 800; color: var(--primary-color);">
                        @if($product->giamoi > 0)
                            {{ number_format($product->giamoi) }} đ
                            <span style="font-size: 1rem; color: #9ca3af; text-decoration: line-through; margin-left: 10px; font-weight: 400;">{{ number_format($product->gia) }} đ</span>
                        @else
                            {{ $product->gia > 0 ? number_format($product->gia) . ' đ' : ($lang == 'vi' ? 'Liên hệ' : 'Contact Us') }}
                        @endif
                    </div>
                </div>

                <div style="background: #f8fafc; border-radius: 1rem; padding: 25px; margin-bottom: 30px;">
                    <div style="display: grid; grid-template-columns: 120px 1fr; gap: 15px; margin-bottom: 10px; font-size: 0.95rem;">
                        <span style="font-weight: 600; color: #64748b;">{{ $lang == 'vi' ? 'Mã sản phẩm' : 'SKU' }}:</span>
                        <span>{{ $product->masp ?: '---' }}</span>

                        <span style="font-weight: 600; color: #64748b;">{{ $lang == 'vi' ? 'Danh mục' : 'Category' }}:</span>
                        <span>{{ $lang == 'vi' ? $product->list->tenvi : $product->list->tenen }}</span>

                        <span style="font-weight: 600; color: #64748b;">{{ $lang == 'vi' ? 'Thương hiệu' : 'Brand' }}:</span>
                        <span>{{ $product->brand ? ($lang == 'vi' ? $product->brand->tenvi : $product->brand->tenen) : '---' }}</span>

                        <span style="font-weight: 600; color: #64748b;">{{ $lang == 'vi' ? 'Lượt xem' : 'Views' }}:</span>
                        <span>{{ number_format($product->luotxem) }}</span>
                    </div>
                </div>

                <div style="margin-bottom: 35px;">
                    <h3 style="font-size: 1.125rem; font-weight: 700; margin-bottom: 10px;">{{ $lang == 'vi' ? 'Mô tả ngắn' : 'Short Description' }}</h3>
                    <div style="color: #64748b; line-height: 1.7;">
                        {!! $lang == 'vi' ? $product->motavi : $product->motaen !!}
                    </div>
                </div>

                <div style="display: flex; gap: 15px;">
                    <button class="btn btn-primary" style="flex-grow: 1; padding: 1rem; font-size: 1.125rem;">
                        {{ $lang == 'vi' ? 'Mua ngay' : 'Buy Now' }}
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Content Detail -->
    <section style="padding: 60px 0; background: #fafafa; border-top: 1px solid var(--border-color);">
        <div class="container">
            <div style="max-width: 900px; margin: 0 auto; background: white; padding: 40px; border-radius: 1rem; border: 1px solid var(--border-color);">
                <h2 style="font-size: 1.5rem; font-weight: 800; margin-bottom: 30px; border-bottom: 2px solid #f3f4f6; padding-bottom: 15px;">
                    {{ $lang == 'vi' ? 'Thông tin chi tiết' : 'Product Details' }}
                </h2>
                <div class="content-detail" style="line-height: 1.8;">
                    {!! $lang == 'vi' ? $product->noidungvi : $product->noidungen !!}
                </div>
            </div>
        </div>
    </section>

    <!-- Related Products -->
    <section style="padding: 80px 0; background: white;">
        <div class="container">
            <h2 class="section-title">{{ $lang == 'vi' ? 'Sản phẩm liên quan' : 'Related Products' }}</h2>
            <div class="product-grid">
                @foreach($related as $item)
                    <div class="product-card">
                        <a href="{{ route('products.show', ['lang' => $lang, 'slug' => $lang == 'vi' ? $item->tenkhongdauvi : $item->tenkhongdauen]) }}">
                            <img src="{{ $item->photo ? asset('storage/' . $item->photo) : asset('admin_assets/images/noimage.png') }}" class="product-img" alt="{{ $lang == 'vi' ? $item->tenvi : $item->tenen }}">
                        </a>
                        <div class="product-info">
                            <a href="{{ route('products.show', ['lang' => $lang, 'slug' => $lang == 'vi' ? $item->tenkhongdauvi : $item->tenkhongdauen]) }}" class="product-name">
                                {{ $lang == 'vi' ? $item->tenvi : $item->tenen }}
                            </a>
                            <div class="product-price">
                                {{ $item->gia > 0 ? number_format($item->gia) . ' đ' : ($lang == 'vi' ? 'Liên hệ' : 'Contact Us') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
