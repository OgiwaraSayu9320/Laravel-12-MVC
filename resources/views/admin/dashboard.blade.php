@extends('admin.layouts.app')

@section('title', 'Bảng điều khiển')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        {{-- Card Thống kê --}}
        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1 small opacity-75">Sản Phẩm</h6>
                            <h2 class="mb-0 fw-bold">{{ $stats['total_products'] }}</h2>
                        </div>
                        <div class="fs-1 opacity-50"><i class="bi bi-box-seam"></i></div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0 pb-3">
                    <a href="{{ route('admin.products.index') }}" class="text-white text-decoration-none small">
                        Xem chi tiết <i class="bi bi-arrow-right-short"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1 small opacity-75">Tin Tức</h6>
                            <h2 class="mb-0 fw-bold">{{ $stats['total_news'] }}</h2>
                        </div>
                        <div class="fs-1 opacity-50"><i class="bi bi-newspaper"></i></div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0 pb-3">
                    <a href="{{ route('admin.news.index') }}" class="text-white text-decoration-none small">
                        Xem chi tiết <i class="bi bi-arrow-right-short"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1 small opacity-75">Danh Mục SP</h6>
                            <h2 class="mb-0 fw-bold">{{ $stats['total_product_lists'] + $stats['total_product_cats'] }}</h2>
                        </div>
                        <div class="fs-1 opacity-50"><i class="bi bi-list-stars"></i></div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0 pb-3">
                    <span class="text-white small opacity-75">Cấp 1 & Cấp 2</span>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1 small opacity-75">Thương Hiệu</h6>
                            <h2 class="mb-0 fw-bold">{{ $stats['total_brands'] }}</h2>
                        </div>
                        <div class="fs-1 opacity-50"><i class="bi bi-patch-check"></i></div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0 pb-3">
                    <span class="text-white small opacity-75">Hãng sản xuất</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        {{-- Bảng Sản phẩm mới --}}
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold text-primary">Sản phẩm mới cập nhật</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr class="small text-uppercase text-muted">
                                    <th class="ps-3">ID</th>
                                    <th>Tên sản phẩm</th>
                                    <th class="text-end pe-3">Ngày tạo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_products as $p)
                                <tr>
                                    <td class="ps-3 text-muted small">#{{ $p->id }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $p->tenvi }}</div>
                                        <div class="small text-muted">{{ number_format($p->gia) }}đ</div>
                                    </td>
                                    <td class="text-end pe-3 text-muted small">{{ $p->created_at->format('d/m/Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bảng Tin tức mới --}}
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold text-success">Bài viết mới nhất</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr class="small text-uppercase text-muted">
                                    <th class="ps-3">ID</th>
                                    <th>Tiêu đề</th>
                                    <th class="text-end pe-3">Lượt xem</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_news as $n)
                                <tr>
                                    <td class="ps-3 text-muted small">#{{ $n->id }}</td>
                                    <td class="fw-bold text-truncate" style="max-width: 250px;">{{ $n->tenvi }}</td>
                                    <td class="text-end pe-3 text-muted"><i class="bi bi-eye"></i> {{ $n->luotxem }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection