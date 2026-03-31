@extends('admin.layouts.app')

@section('title', 'Danh sách danh mục cấp 2')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Quản lý danh mục cấp 2</h1>
            <a href="{{ route('admin.product-cats.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Thêm mới
            </a>
        </div>

        <form method="GET" action="{{ route('admin.product-cats.index') }}" class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <div class="col-md-5 col-sm-12">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="Tìm theo tên danh mục..."
                                value="{{ request('search') }}">
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <select name="hienthi" class="form-select">
                            <option value="">-- Trạng thái --</option>
                            <option value="1" {{ request('hienthi') == '1' ? 'selected' : '' }}>Hiển thị</option>
                            <option value="0" {{ request('hienthi') == '0' ? 'selected' : '' }}>Ẩn</option>
                        </select>
                    </div>

                    <div class="col-md-4 col-sm-6 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-funnel-fill me-1"></i> Lọc
                        </button>
                        <a href="{{ route('admin.product-cats.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-repeat me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th width="8%">ID</th>
                                <th width="12%">Ảnh</th>
                                <th width="30%">Tên danh mục</th>
                                <th width="12%">Số SP</th>
                                <th width="10%">Trạng thái</th>
                                <th width="16%" class="text-end">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($cats as $cats)
                                <tr>
                                    <td>{{ $cats->id }}</td>
                                    <td>
                                        @if ($cats->photo && Storage::disk('public')->exists($cats->photo))
                                            <img src="{{ Storage::url($cats->photo) }}" alt="{{ $cats->tenvi }}"
                                                width="60" height="60" class="rounded object-fit-cover shadow-sm">
                                        @else
                                            <img src="https://placehold.co/60x60?text=No+Img" class="rounded"
                                                alt="No image">
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $cats->tenvi }}</div>
                                        @if ($cats->tenen)
                                            <small class="text-muted">EN: {{ $cats->tenen }}</small>
                                        @endif
                                    </td>
                                    <td class="">
                                        <span class="badge bg-info-subtle text-info">
                                            {{ $cats->products_count ?? $cats->products()->count() }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($cats->hienthi)
                                            <span class="badge bg-success px-3">Hiển thị</span>
                                        @else
                                            <span class="badge bg-secondary px-3">Ẩn</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.product-cats.edit', $cats->id) }}"
                                            class="btn btn-sm btn-outline-warning me-1" title="Sửa">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <form action="{{ route('admin.product-cats.destroy', $cats->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Xác nhận xóa danh mục cấp 1 này?\nCác sản phẩm và cấp 2 sẽ được gỡ liên kết (id_list = null).');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="bi bi-folder-x me-2 fs-4"></i>
                                        Chưa có danh mục cấp 2 nào
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer bg-white d-flex justify-content-end py-3">
                {{-- {{ $cats->appends(request()->query())->links() }} --}}
            </div>
        </div>
    </div>
@endsection
