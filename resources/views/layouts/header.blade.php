@php
    $lang = app()->getLocale();
    $langs = config('lang.langs');
    $currentRoute = request()->route()->getName();
@endphp

<header class="header">
    <div class="container header-inner">
        <a href="{{ route('home', ['lang' => $lang]) }}" class="logo">ANTIGRAVITY</a>

        <nav>
            <ul class="nav-menu">
                <li>
                    <a href="{{ route('home', ['lang' => $lang]) }}"
                       class="nav-link {{ $currentRoute == 'home' ? 'active' : '' }}">
                        {{ $lang == 'vi' ? 'Trang chủ' : 'Home' }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('products.index', ['lang' => $lang]) }}"
                       class="nav-link {{ str_contains($currentRoute, 'products') ? 'active' : '' }}">
                        {{ $lang == 'vi' ? 'Sản phẩm' : 'Products' }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('news.index', ['lang' => $lang]) }}"
                       class="nav-link {{ str_contains($currentRoute, 'news') ? 'active' : '' }}">
                        {{ $lang == 'vi' ? 'Tin tức' : 'News' }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('contact', ['lang' => $lang]) }}"
                       class="nav-link {{ $currentRoute == 'contact' ? 'active' : '' }}">
                        {{ $lang == 'vi' ? 'Liên hệ' : 'Contact' }}
                    </a>
                </li>
            </ul>
        </nav>

        <div class="lang-switcher">
            @foreach($langs as $code => $name)
                <a href="{{ route(request()->route()->getName(), array_merge(request()->route()->parameters(), ['lang' => $code])) }}"
                   class="lang-btn {{ $lang == $code ? 'active' : '' }}">
                    {{ strtoupper($code) }}
                </a>
            @endforeach
        </div>
    </div>
</header>
