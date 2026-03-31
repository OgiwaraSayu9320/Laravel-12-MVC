@extends('admin.layouts.app')

@section('title', 'Quản lý thương hiệu')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Quản lý Thương hiệu</h1>
            <a href="{{ route('admin.brands.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Thêm mới
            </a>
        </div>

        <form method="GET" action="{{ route('admin.brands.index') }}" class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="Tìm tên thương hiệu..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="hienthi" class="form-select">
                            <option value="">-- Trạng thái --</option>
                            <option value="1" {{ request('hienthi') == '1' ? 'selected' : '' }}>Hiển thị</option>
                            <option value="0" {{ request('hienthi') == '0' ? 'selected' : '' }}>Ẩn</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">Lọc</button>
                        <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
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
                                <th width="5%">STT</th>
                                <th width="15%">Logo</th>
                                <th width="40%">Tên thương hiệu</th>
                                <th width="15%">Trạng thái</th>
                                <th width="15%">Nổi bật</th>
                                <th width="10%" class="text-end">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($brands as $item)
                                <tr>
                                    <td>{{ $item->stt }}</td>
                                    <td>
                                        @if ($item->photo && Storage::disk('public')->exists($item->photo))
                                            <img src="{{ asset('storage/' . $item->photo) }}" width="80" height="auto" class="rounded p-1 border bg-white">
                                        @else
                                            <img src="https://placehold.co/80x40/f8f9fa/adb5bd?text=No+Logo" class="rounded border">
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold"><a href="{{ route('admin.brands.edit', $item->id) }}">{{ $item->tenvi }}</a></div>
                                        @if($item->tenen) <small class="text-muted">EN: {{ $item->tenen }}</small> @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $item->hienthi ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $item->hienthi ? 'Hiển thị' : 'Ẩn' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $item->noibat ? 'bg-danger' : 'bg-light text-dark' }}">
                                            {{ $item->noibat ? 'Nổi bật' : 'Thường' }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.brands.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('admin.brands.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa thương hiệu này?');">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Không tìm thấy dữ liệu.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($brands->hasPages())
                <div class="card-footer d-flex justify-content-end py-3">
                    {{ $brands->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
@endsection
