@php
    $lang = app()->getLocale();
@endphp

<footer class="footer">
    <div class="container footer-grid">
        <div class="footer-col">
            <a href="#" class="logo" style="color: white; margin-bottom: 20px; display: block;">ANTIGRAVITY</a>
            <p style="color: #9ca3af; font-size: 0.875rem;">
                {{ $lang == 'vi' ? 'Hệ thống quản lý nội dung đa ngôn ngữ mạnh mẽ.' : 'Powerful multilingual content management system.' }}
            </p>
        </div>

        <div class="footer-col">
            <h4 class="footer-title">{{ $lang == 'vi' ? 'Liên kết nhanh' : 'Quick Links' }}</h4>
            <ul class="footer-links">
                <li><a href="{{ route('home', ['lang' => $lang]) }}" class="footer-link">{{ $lang == 'vi' ? 'Trang chủ' : 'Home' }}</a></li>
                <li><a href="{{ route('products.index', ['lang' => $lang]) }}" class="footer-link">{{ $lang == 'vi' ? 'Sản phẩm' : 'Products' }}</a></li>
                <li><a href="{{ route('news.index', ['lang' => $lang]) }}" class="footer-link">{{ $lang == 'vi' ? 'Tin tức' : 'News' }}</a></li>
                <li><a href="{{ route('contact', ['lang' => $lang]) }}" class="footer-link">{{ $lang == 'vi' ? 'Liên hệ' : 'Contact' }}</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h4 class="footer-title">{{ $lang == 'vi' ? 'Liên hệ' : 'Contact Us' }}</h4>
            <ul class="footer-links">
                <li style="color: #9ca3af; margin-bottom: 8px;">Email: support@antigravity.site</li>
                <li style="color: #9ca3af; margin-bottom: 8px;">Phone: +84 123 456 789</li>
                <li style="color: #9ca3af; margin-bottom: 8px;">Address: District 1, Ho Chi Minh City</li>
            </ul>
        </div>
    </div>

    <div class="footer-bottom">
        <p>&copy; {{ date('Y') }} Antigravity System. All rights reserved.</p>
    </div>
</footer>
