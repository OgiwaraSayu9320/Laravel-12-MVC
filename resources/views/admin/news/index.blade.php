@extends('admin.layouts.app')

@section('title', 'Quản lý tin tức')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Quản lý tin tức</h1>
            <a href="{{ route('admin.news.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Thêm mới
            </a>
        </div>

        <form method="GET" action="{{ route('admin.news.index') }}" class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="Tìm tiêu đề bài viết..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select name="id_list" id="id_list" class="form-select text-truncate">
                            <option value="">-- Cấp 1 --</option>
                            @foreach($lists as $list)
                                <option value="{{ $list->id }}" {{ request('id_list') == $list->id ? 'selected' : '' }}>{{ $list->tenvi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="id_cat" id="id_cat" class="form-select text-truncate" {{ request('id_list') ? '' : 'disabled' }}>
                            <option value="">-- Cấp 2 --</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="hienthi" class="form-select">
                            <option value="">-- Trạng thái --</option>
                            <option value="1" {{ request('hienthi') == '1' ? 'selected' : '' }}>Hiển thị</option>
                            <option value="0" {{ request('hienthi') == '0' ? 'selected' : '' }}>Ẩn</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">Lọc</button>
                        <a href="{{ route('admin.news.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
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
                                <th width="30%">Tiêu đề</th>
                                <th width="20%">Danh mục</th>
                                <th width="10%">Trạng thái</th>
                                <th width="10%">Nổi bật</th>
                                <th width="15%" class="text-end">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($news as $item)
                                <tr>
                                    <td>{{ $item->stt }}</td>
                                    <td>
                                        @if ($item->photo && Storage::disk('public')->exists($item->photo))
                                            <a href="{{ route('admin.news.edit', $item->id) }}">
                                                <img src="{{ asset('storage/' . $item->photo) }}" width="50" height="50" class="rounded object-fit-cover">
                                            </a>
                                        @else
                                            <img src="https://placehold.co/50x50?text=No+Img" class="rounded">
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold"><a href="{{ route('admin.news.edit', $item->id) }}">{{ $item->tenvi }}</a></div>
                                        <small class="text-muted"><i class="bi bi-eye me-1"></i> {{ $item->luotxem }} lượt xem</small>
                                    </td>
                                    <td>
                                        <div class="small">
                                            L1: <span class="text-primary">{{ $item->list->tenvi ?? '---' }}</span> <br>
                                            L2: <span class="text-info">{{ $item->cat->tenvi ?? '---' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge {{ $item->hienthi ? 'bg-success' : 'bg-secondary' }}">{{ $item->hienthi ? 'Hiển thị' : 'Ẩn' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $item->noibat ? 'bg-danger' : 'bg-light text-dark' }}">{{ $item->noibat ? 'Nổi bật' : 'Thường' }}</span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.news.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('admin.news.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này?');">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">Không tìm thấy bài viết nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($news->hasPages())
                <div class="card-footer d-flex justify-content-end py-3">
                    {{ $news->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const allCats = @json($cats);
            const listSelect = document.getElementById('id_list');
            const catSelect = document.getElementById('id_cat');
            const currentCatId = "{{ request('id_cat') }}";

            function renderCats(listId) {
                catSelect.innerHTML = '<option value="">-- Cấp 2 --</option>';
                if (!listId) {
                    catSelect.disabled = true;
                    return;
                }

                const filteredCats = allCats.filter(cat => cat.id_list == listId);
                if (filteredCats.length > 0) {
                    catSelect.disabled = false;
                    filteredCats.forEach(cat => {
                        const opt = document.createElement('option');
                        opt.value = cat.id;
                        opt.textContent = cat.tenvi;
                        if (currentCatId && currentCatId == cat.id) opt.selected = true;
                        catSelect.appendChild(opt);
                    });
                } else {
                    catSelect.disabled = true;
                    catSelect.innerHTML = '<option value="">Không có mục con</option>';
                }
            }

            listSelect.addEventListener('change', function() { renderCats(this.value); });
            if (listSelect.value) renderCats(listSelect.value);
        });
    </script>
@endsection
