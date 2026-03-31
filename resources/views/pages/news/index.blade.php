@extends('layouts.main')

@php
    $lang = app()->getLocale();
@endphp

@section('title', $seo ? ($lang == 'vi' ? $seo->titlevi : $seo->titleen) : 'News')

@section('seo')
@if($seo)
    <meta name="description" content="{{ $lang == 'vi' ? $seo->descriptionvi : $seo->descriptionen }}">
    <meta name="keywords" content="{{ $lang == 'vi' ? $seo->keywordsvi : $seo->keywordsen }}">
@endif
@endsection

@section('content')
    <section style="padding: 60px 0; background: white;">
        <div class="container">
            <h1 class="section-title">{{ $lang == 'vi' ? 'Tin tức & Sự kiện' : 'News & Events' }}</h1>

            <div class="news-list" style="max-width: 900px; margin: 0 auto;">
                @forelse($news as $item)
                    <article style="display: grid; grid-template-columns: 300px 1fr; gap: 30px; margin-bottom: 50px; background: white; border-bottom: 1px solid #f3f4f6; padding-bottom: 50px; align-items: flex-start;">
                        <a href="{{ route('news.show', ['lang' => $lang, 'slug' => $lang == 'vi' ? $item->tenkhongdauvi : $item->tenkhongdauen]) }}"
                           style="border-radius: 1rem; overflow: hidden; display: block; border: 1px solid var(--border-color); aspect-ratio: 16/9;">
                            <img src="{{ $item->photo ? asset('storage/' . $item->photo) : asset('admin_assets/images/noimage.png') }}"
                                 style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s;"
                                 onmouseover="this.style.transform='scale(1.1)'"
                                 onmouseout="this.style.transform='scale(1)'">
                        </a>

                        <div class="news-content">
                            <h3 style="font-size: 1.5rem; font-weight: 800; margin: 0 0 15px;">
                                <a href="{{ route('news.show', ['lang' => $lang, 'slug' => $lang == 'vi' ? $item->tenkhongdauvi : $item->tenkhongdauen]) }}"
                                   style="text-decoration: none; color: var(--text-color); transition: color 0.3s;">
                                    {{ $lang == 'vi' ? $item->tenvi : $item->tenen }}
                                </a>
                            </h3>

                            <div style="font-size: 0.875rem; color: #9ca3af; margin-bottom: 15px; display: flex; gap: 20px;">
                                <span>📅 {{ $item->created_at->format('d/m/Y') }}</span>
                                <span>👁️ {{ number_format($item->luotxem) }} {{ $lang == 'vi' ? 'lượt xem' : 'views' }}</span>
                            </div>

                            <p style="color: #64748b; line-height: 1.7; margin-bottom: 25px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ $lang == 'vi' ? $item->motavi : $item->motaen }}
                            </p>

                            <a href="{{ route('news.show', ['lang' => $lang, 'slug' => $lang == 'vi' ? $item->tenkhongdauvi : $item->tenkhongdauen]) }}"
                               style="color: var(--primary-color); font-weight: 700; text-decoration: none; border-bottom: 2px solid var(--primary-color); padding-bottom: 3px;">
                                {{ $lang == 'vi' ? 'Tiếp tục đọc' : 'Continue reading' }}
                            </a>
                        </div>
                    </article>
                @empty
                    <div style="text-align: center; padding: 100px 0;">
                        <p style="font-size: 1.25rem; color: #9ca3af;">{{ $lang == 'vi' ? 'Hiện chưa có bài viết nào.' : 'No news articles found.' }}</p>
                    </div>
                @endforelse

                <div style="margin-top: 40px;">
                    {{ $news->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </section>
@endsection
