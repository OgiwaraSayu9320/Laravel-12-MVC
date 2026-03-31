@extends('layouts.main')

@php
    $lang = app()->getLocale();
@endphp

@section('title', $lang == 'vi' ? $news->titlevi : $news->titleen)

@section('seo')
<meta name="description" content="{{ $lang == 'vi' ? $news->descriptionvi : $news->descriptionen }}">
<meta name="keywords" content="{{ $lang == 'vi' ? $news->keywordsvi : $news->keywordsen }}">
@endsection

@section('content')
    <section style="padding: 40px 0; background: #fafafa;">
        <div class="container">
            <ul style="display: flex; list-style: none; padding: 0; font-size: 0.875rem; color: #6b7280; gap: 10px;">
                <li><a href="{{ route('home', ['lang' => $lang]) }}" style="color: inherit; text-decoration: none;">{{ $lang == 'vi' ? 'Trang chủ' : 'Home' }}</a></li>
                <li>/</li>
                <li><a href="{{ route('news.index', ['lang' => $lang]) }}" style="color: inherit; text-decoration: none;">{{ $lang == 'vi' ? 'Tin tức' : 'News' }}</a></li>
                <li>/</li>
                <li style="color: var(--text-color); font-weight: 600;">{{ $lang == 'vi' ? $news->tenvi : $news->tenen }}</li>
            </ul>
        </div>
    </section>

    <article style="padding: 60px 0; background: white;">
        <div class="container" style="max-width: 900px;">
            <header style="margin-bottom: 40px;">
                <h1 style="font-size: 2.75rem; font-weight: 800; line-height: 1.2; margin-bottom: 20px; color: var(--text-color);">
                    {{ $lang == 'vi' ? $news->tenvi : $news->tenen }}
                </h1>
                <div style="display: flex; gap: 20px; font-size: 0.875rem; color: #9ca3af; padding-bottom: 20px; border-bottom: 1px solid #f3f4f6;">
                    <span>📅 {{ $news->created_at->format('d/m/Y') }}</span>
                    <span>👁️ {{ number_format($news->luotxem) }} {{ $lang == 'vi' ? 'lượt xem' : 'views' }}</span>
                    <span>📂 {{ $news->list ? ($lang == 'vi' ? $news->list->tenvi : $news->list->tenen) : '' }}</span>
                </div>
            </header>

            @if($news->photo)
                <div style="margin-bottom: 40px; border-radius: 1.5rem; overflow: hidden; border: 1px solid var(--border-color);">
                    <img src="{{ asset('storage/' . $news->photo) }}" style="width: 100%; object-fit: cover;">
                </div>
            @endif

            <div class="news-content-detail" style="line-height: 1.8; font-size: 1.125rem; color: #374151;">
                {!! $lang == 'vi' ? $news->noidungvi : $news->noidungen !!}
            </div>

            @if($news->galleries->count() > 0)
                <div style="margin-top: 50px; display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px;">
                    @foreach($news->galleries as $gallery)
                        <div style="border-radius: 1rem; overflow: hidden; border: 1px solid var(--border-color);">
                            <img src="{{ asset('storage/' . $gallery->photo) }}" style="width: 100%; aspect-ratio: 4/3; object-fit: cover;">
                        </div>
                    @endforeach
                </div>
            @endif

            <div style="margin-top: 60px; padding: 40px; background: #f8fafc; border-radius: 1.5rem;">
                <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 25px;">{{ $lang == 'vi' ? 'Bài viết liên quan' : 'Related Articles' }}</h3>
                <ul style="list-style: none; padding: 0;">
                    @foreach($related as $item)
                        <li style="margin-bottom: 15px;">
                            <a href="{{ route('news.show', ['lang' => $lang, 'slug' => $lang == 'vi' ? $item->tenkhongdauvi : $item->tenkhongdauen]) }}"
                               style="color: var(--text-color); text-decoration: none; font-weight: 500; display: flex; gap: 10px; transition: color 0.3s;"
                               onmouseover="this.style.color='var(--primary-color)'"
                               onmouseout="this.style.color='var(--text-color)'">
                                <span>▶</span>
                                {{ $lang == 'vi' ? $item->tenvi : $item->tenen }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </article>
@endsection
