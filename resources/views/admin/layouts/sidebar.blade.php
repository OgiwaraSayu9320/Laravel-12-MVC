@php
    $isProductActive =
        request()->routeIs('admin.product-lists.*') ||
        request()->routeIs('admin.product-cats.*') ||
        request()->routeIs('admin.products.*');

    $isNewsActive =
        request()->routeIs('admin.news-lists.*') ||
        request()->routeIs('admin.news-cats.*') ||
        request()->routeIs('admin.news.*');
@endphp


<nav class="col-md-2 sidebar">

    <a href="{{ route('admin.dashboard') }}" class="brand-link">
        <span class="brand-text">Admin System</span>
    </a>

    <div class="position-sticky pt-3">
        <ul class="nav flex-column">

            {{-- Dashboard --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                    href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-speedometer2"></i> {{-- Icon Dashboard --}}
                    Dashboard
                </a>
            </li>

            {{-- Users --}}
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-people"></i>
                    Quản lý User
                </a>
            </li>

            {{-- Products --}}
            {{-- Nếu true -> in luôn class 'menu-open' từ server --}}
            <li class="nav-item has-treeview {{ $isProductActive ? 'menu-open' : '' }}">

                {{-- Thẻ cha cũng sáng lên luôn cho đẹp --}}
                <a class="nav-link {{ $isProductActive ? 'active' : '' }}" href="#">
                    <i class="bi bi-box-seam"></i>
                    <span>Quản lý Sản phẩm</span>
                    <i class="bi bi-chevron-right ms-auto arrow-icon"></i>
                </a>

                {{-- Nếu true -> ép style display: block để nó mở sẵn, khỏi đợi JS --}}
                <ul class="nav nav-treeview" style="{{ $isProductActive ? 'display: block;' : 'display: none;' }}">

                    <li class="nav-item">
                        {{-- Dùng dấu * để bao trọn cả index, create, edit... --}}
                        <a href="{{ route('admin.product-lists.index') }}"
                            class="nav-link {{ request()->routeIs('admin.product-lists.*') ? 'active' : '' }}">
                            <i class="bi bi-circle fs-8"></i> Danh Mục Cấp 1
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.product-cats.index') }}"
                            class="nav-link {{ request()->routeIs('admin.product-cats.*') ? 'active' : '' }}">
                            <i class="bi bi-circle fs-8"></i> Danh Mục Cấp 2
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.brands.index') }}"
                            class="nav-link {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                            <i class="bi bi-circle fs-8"></i> Thương hiệu
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.products.index') }}"
                            class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                            <i class="bi bi-circle fs-8"></i> Danh sách sản phẩm
                        </a>
                    </li>

                </ul>
            </li>
            
            {{-- Contacts --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}" href="{{ route('admin.contacts.index') }}">
                    <i class="bi bi-chat-left-dots"></i>
                    Quản lý Liên hệ
                </a>
            </li>

            {{-- News --}}
            <li class="nav-item has-treeview {{ $isNewsActive ? 'menu-open' : '' }}">
                <a class="nav-link {{ $isNewsActive ? 'active' : '' }}" href="#">
                    <i class="bi bi-newspaper"></i>
                    <span>Quản lý Tin tức</span>
                    <i class="bi bi-chevron-right ms-auto arrow-icon"></i>
                </a>
                <ul class="nav nav-treeview" style="{{ $isNewsActive ? 'display: block;' : 'display: none;' }}">
                    <li class="nav-item">
                        <a href="{{ route('admin.news-lists.index') }}"
                            class="nav-link {{ request()->routeIs('admin.news-lists.*') ? 'active' : '' }}">
                            <i class="bi bi-circle fs-8"></i> Danh Mục Cấp 1
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.news-cats.index') }}"
                            class="nav-link {{ request()->routeIs('admin.news-cats.*') ? 'active' : '' }}">
                            <i class="bi bi-circle fs-8"></i> Danh Mục Cấp 2
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.news.index') }}"
                            class="nav-link {{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
                            <i class="bi bi-circle fs-8"></i> Danh sách bài viết
                        </a>
                    </li>
                </ul>
            </li>

            {{-- SEO --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.seo-pages.*') ? 'active' : '' }}" href="{{ route('admin.seo-pages.index') }}">
                    <i class="bi bi-google"></i>
                    Quản lý SEO
                </a>
            </li>

            <li class="nav-header ps-3 pt-3 text-secondary text-uppercase fs-7 fw-bold">
                Cài đặt
            </li>

            {{-- Profile --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}" href="{{ route('admin.profile.index') }}">
                    <i class="bi bi-person-badge"></i>
                    Thông tin cá nhân
                </a>
            </li>

            {{-- Logout --}}
            <li class="nav-item">
                <a class="nav-link text-danger" href="{{ route('admin.logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-right"></i>
                    Đăng xuất
                </a>
            </li>
        </ul>

        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
</nav>
