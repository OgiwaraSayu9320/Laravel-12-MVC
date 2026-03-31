@extends('admin.layouts.app')

@section('title', 'Danh sách sản phẩm')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Quản lý sản phẩm</h1>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Thêm mới
            </a>
        </div>

        <form method="GET" action="{{ route('admin.products.index') }}" class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4 col-sm-12">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            {{-- Sửa placeholder cho đúng thực tế --}}
                            <input type="text" name="search" class="form-control"
                                placeholder="Tìm theo tên, mã sản phẩm..." value="{{ request('search') }}">
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <select name="id_list" id="id_list" class="form-select">
                            <option value="">-- Tất cả cấp 1 --</option>
                            @foreach ($lists as $list)
                                {{-- Sửa $list->name thành $list->tenvi --}}
                                <option value="{{ $list->id }}" {{ request('id_list') == $list->id ? 'selected' : '' }}>
                                    {{ $list->tenvi }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select name="id_cat" id="id_cat" class="form-select text-truncate"
                            {{ request('id_list') ? '' : 'disabled' }}>
                            <option value="">-- Cấp 2 --</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select name="id_brand" class="form-select text-truncate">
                            <option value="">-- Thương hiệu --</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ request('id_brand') == $brand->id ? 'selected' : '' }}>{{ $brand->tenvi }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-1 col-sm-6">
                        <select name="hienthi" class="form-select">
                            <option value="">-- Trạng thái --</option>
                            <option value="1" {{ request('hienthi') == '1' ? 'selected' : '' }}>Hiển thị</option>
                            <option value="0" {{ request('hienthi') == '0' ? 'selected' : '' }}>Ẩn</option>
                        </select>
                    </div>

                    <div class="col-md-12 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-funnel-fill me-1"></i> Lọc
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
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
                                <th width="5%">ID</th>
                                <th width="10%">Hình ảnh</th>
                                <th width="25%">Tên sản phẩm</th>
                                <th width="20%">Danh mục</th>
                                <th width="15%">Thương hiệu</th>
                                <th width="10%">Trạng thái</th>
                                <th width="10%" class="text-end">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td>
                                        @if ($product->photo && Storage::disk('public')->exists($product->photo))
                                            <a href="{{ route('admin.products.edit', $product->id) }}">
                                                <img src="{{ asset('storage/' . $product->photo) }}" width="50"
                                                    height="50" class="rounded object-fit-cover">
                                            </a>
                                        @else
                                            <img src="https://placehold.co/50x50?text=No+Img" class="rounded">
                                        @endif
                                    </td>
                                    <td>
                                        {{-- tenvi --}}
                                        <div class="fw-bold">
                                            <a
                                                href="{{ route('admin.products.edit', $product->id) }}">{{ $product->tenvi }}</a>
                                        </div>
                                        <small class="text-muted">Mã: {{ $product->masp ?? '---' }}</small>
                                    </td>
                                    <td>
                                        <div class="small">
                                            C1: {{ $product->list->tenvi ?? '---' }} <br>
                                            C2: {{ $product->cat->tenvi ?? '---' }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-success small fw-bold">{{ $product->brand->tenvi ?? '---' }}</span>
                                    </td>
                                    <td>
                                        {{-- hienthi --}}
                                        @if ($product->hienthi)
                                            <span class="badge bg-success">Hiển thị</span>
                                        @else
                                            <span class="badge bg-secondary">Ẩn</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.products.edit', $product->id) }}"
                                            class="btn btn-sm btn-warning me-1">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này? hành động này không thể hoàn tác');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <p class="text-muted mb-0">Không tìm thấy sản phẩm nào.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Phân trang --}}
            <div class="card-footer d-flex justify-content-end py-3">
                {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    {{-- SCRIPT XỬ LÝ DROPDOWN --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Lấy dữ liệu từ Controller (Lưu ý: Controller trả về mảng phẳng, cần lọc bằng JS)
            const allCats = @json($cats);
            const listSelect = document.getElementById('id_list');
            const catSelect = document.getElementById('id_cat');
            const currentCatId = "{{ request('id_cat') }}"; // Lấy giá trị đang chọn trên URL (nếu có)

            function renderCats(listId) {
                // Xóa cũ
                catSelect.innerHTML = '<option value="">-- Chọn cấp 2 --</option>';

                if (!listId) {
                    catSelect.disabled = true;
                    return;
                }

                // Lọc danh mục cấp 2 theo id_list được chọn
                // Chú ý: dùng == thay vì === vì id có thể là string hoặc number
                const filteredCats = allCats.filter(cat => cat.id_list == listId);

                if (filteredCats.length > 0) {
                    catSelect.disabled = false;
                    filteredCats.forEach(cat => {
                        const option = document.createElement('option');
                        option.value = cat.id;
                        option.textContent = cat.tenvi; // Dùng tenvi

                        // Check selected
                        if (currentCatId && currentCatId == cat.id) {
                            option.selected = true;
                        }
                        catSelect.appendChild(option);
                    });
                } else {
                    catSelect.disabled = true;
                    const option = document.createElement('option');
                    option.textContent = "Không có mục con";
                    catSelect.appendChild(option);
                }
            }

            // Sự kiện khi thay đổi cấp 1
            listSelect.addEventListener('change', function() {
                renderCats(this.value);
            });

            // Chạy ngay khi load trang (để fill lại dữ liệu nếu đang search)
            if (listSelect.value) {
                renderCats(listSelect.value);
            }
        });
    </script>
@endsection
