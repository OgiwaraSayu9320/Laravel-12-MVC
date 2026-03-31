@extends('admin.layouts.app')

@section('title', 'Danh mục tin tức cấp 1')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Danh mục tin tức cấp 1</h1>
            <a href="{{ route('admin.news-lists.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Thêm mới
            </a>
        </div>

        <form method="GET" action="{{ route('admin.news-lists.index') }}" class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="Tìm tên danh mục..." value="{{ request('search') }}">
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
                        <a href="{{ route('admin.news-lists.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
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
                                <th width="10%">Hình</th>
                                <th width="45%">Tiêu đề</th>
                                <th width="15%">Trạng thái</th>
                                <th width="15%">Nổi bật</th>
                                <th width="10%" class="text-end">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($lists as $item)
                                <tr>
                                    <td>
                                        <input type="number" class="form-control form-control-sm update-stt" value="{{ $item->stt }}" 
                                               style="width: 60px" readonly>
                                    </td>
                                    <td>
                                        @if ($item->photo && Storage::disk('public')->exists($item->photo))
                                            <img src="{{ asset('storage/' . $item->photo) }}" width="50" height="50" class="rounded object-fit-cover">
                                        @else
                                            <img src="https://placehold.co/50x50?text=No+Img" class="rounded">
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold"><a href="{{ route('admin.news-lists.edit', $item->id) }}">{{ $item->tenvi }}</a></div>
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
                                        <a href="{{ route('admin.news-lists.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('admin.news-lists.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa danh mục sẽ cập nhật id_list của các bài viết và danh mục con về NULL. Tiếp tục?');">
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
            @if($lists->hasPages())
                <div class="card-footer d-flex justify-content-end py-3">
                    {{ $lists->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
@endsection
