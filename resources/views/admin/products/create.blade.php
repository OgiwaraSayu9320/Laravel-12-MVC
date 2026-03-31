@extends('admin.layouts.app')
@section('title', 'Thêm mới sản phẩm')

@section('content')
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            {{-- CỘT TRÁI: THÔNG TIN ĐA NGÔN NGỮ & GALLERY --}}
            <div class="col-md-8">
                <div class="card card-primary card-outline card-outline-tabs">
                    <div class="card-header p-0 border-bottom-0">
                        <ul class="nav nav-tabs" id="langTab" role="tablist">
                            @foreach ($config_langs as $key => $name)
                                <li class="nav-item">
                                    <a class="nav-link {{ $loop->first ? 'active' : '' }}" id="tab-{{ $key }}"
                                        data-bs-toggle="pill" href="#content-{{ $key }}" role="tab">
                                        {{ $name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="card-body">
                        <div class="tab-content" id="langTabContent">
                            @foreach ($config_langs as $key => $name)
                                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                    id="content-{{ $key }}" role="tabpanel">

                                    {{-- Tên sản phẩm --}}
                                    <div class="mb-3">
                                        <label class="form-label">Tên sản phẩm ({{ $name }}) <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="ten{{ $key }}"
                                            class="form-control @error('ten' . $key) is-invalid @enderror"
                                            value="{{ old('ten' . $key) }}" placeholder="Nhập tên sản phẩm...">
                                        @error('ten' . $key)
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Đường dẫn (Slug) --}}
                                    <div class="mb-3">
                                        <label class="form-label">Đường dẫn / Slug ({{ $name }})</label>
                                        <input type="text" name="tenkhongdau{{ $key }}" class="form-control"
                                            value="{{ old('tenkhongdau' . $key) }}"
                                            placeholder="Để trống sẽ tự tạo theo tên">
                                    </div>

                                    {{-- Mô tả ngắn --}}
                                    <div class="mb-3">
                                        <label class="form-label">Mô tả ngắn ({{ $name }})</label>
                                        <textarea name="mota{{ $key }}" class="form-control editor-short">{{ old('mota' . $key) }}</textarea>
                                    </div>

                                    {{-- Nội dung chi tiết --}}
                                    <div class="mb-3">
                                        <label class="form-label">Nội dung chi tiết ({{ $name }})</label>
                                        <textarea name="noidung{{ $key }}" class="form-control editor-full">{{ old('noidung' . $key) }}</textarea>
                                    </div>

                                    <hr>
                                    {{-- SEO theo ngôn ngữ --}}
                                    <h6 class="text-primary"><i class="bi bi-google"></i> Cấu hình SEO
                                        ({{ $name }})
                                    </h6>
                                    <div class="row">
                                        <div class="col-12 mb-2">
                                            <label>Title SEO</label>
                                            <input type="text" name="title{{ $key }}" class="form-control"
                                                value="{{ old('title' . $key) }}">
                                        </div>
                                        <div class="col-12 mb-2">
                                            <label>Keywords SEO</label>
                                            <input type="text" name="keywords{{ $key }}" class="form-control"
                                                value="{{ old('keywords' . $key) }}" placeholder="Từ khóa 1, từ khóa 2...">
                                        </div>
                                        <div class="col-12 mb-2">
                                            <label>Description SEO</label>
                                            <textarea name="description{{ $key }}" class="form-control" rows="2">{{ old('description' . $key) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- THƯ VIỆN ẢNH --}}
                <div class="card mt-3">
                    <div class="card-header bg-light fw-bold">Thư viện ảnh (Gallery)</div>
                    <div class="card-body">
                        <label class="mb-2">Chọn nhiều ảnh kèm theo</label>
                        <input type="file" name="gallery[]" class="form-control" multiple>
                        <small class="text-muted">Giữ phím Ctrl (hoặc Cmd) để chọn nhiều ảnh cùng lúc.</small>
                    </div>
                </div>
            </div>

            {{-- CỘT PHẢI: THÔNG TIN CHUNG --}}
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">Xuất bản</div>
                    <div class="card-body">
                        {{-- Danh mục cấp 1 --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Danh mục cấp 1 <span class="text-danger">*</span></label>
                            <select name="id_list" class="form-select @error('id_list') is-invalid @enderror"
                                id="list_select" required>
                                <option value="">-- Chọn danh mục --</option>
                                @foreach ($lists as $list)
                                    <option value="{{ $list->id }}"
                                        {{ old('id_list') == $list->id ? 'selected' : '' }}>
                                        {{ $list->tenvi }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_list')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Danh mục cấp 2 --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Danh mục cấp 2</label>
                            <select name="id_cat" class="form-select" id="cat_select" disabled>
                                <option value="">-- Chọn cấp 1 trước --</option>
                            </select>
                        </div>

                        {{-- Thương hiệu --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Thương hiệu</label>
                            <select name="id_brand" class="form-select @error('id_brand') is-invalid @enderror">
                                <option value="">-- Chọn thương hiệu --</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}"
                                        {{ old('id_brand') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->tenvi }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_brand')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Giá bán --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Giá bán <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="gia" class="form-control" value="{{ old('gia', 0) }}"
                                    min="0">
                                <span class="input-group-text">VNĐ</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Mã sản phẩm <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" name="masp" class="form-control"
                                    value="{{ old('masp', '') }}">
                            </div>
                        </div>

                        {{-- Ảnh đại diện --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Ảnh đại diện</label>
                            <input type="file" name="photo" class="form-control mb-2"
                                onchange="previewImage(this)">
                            <div id="photo-preview" class="text-center"
                                style="min-height: 100px; border: 1px dashed #ddd; display: flex; align-items: center; justify-content: center;">
                                <span class="text-muted small">Chưa chọn ảnh</span>
                            </div>
                        </div>

                        {{-- Trạng thái --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Trạng thái</label>
                            <select name="hienthi" class="form-select">
                                <option value="1" selected>Hiển thị</option>
                                <option value="0">Ẩn</option>
                            </select>
                        </div>

                        <hr>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Lưu </button>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Hủy bỏ</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        // Preview ảnh đại diện
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('photo-preview').innerHTML =
                        '<img src="' + e.target.result + '" class="img-fluid rounded border">';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Ajax load danh mục cấp 2
        document.getElementById('list_select').addEventListener('change', function() {
            let listId = this.value;
            let catSelect = document.getElementById('cat_select');

            // Reset
            catSelect.innerHTML = '<option value="">Đang tải...</option>';
            catSelect.disabled = true;

            if (listId) {
                // Sử dụng Route Helper của Laravel để tạo URL chuẩn
                // Đảm bảo bạn đã đặt name cho route api là 'admin.products.get_cats'
                let url = "{{ route('admin.products.get-cats', ':id') }}";
                url = url.replace(':id', listId);

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        let html = '<option value="">-- Chọn cấp 2 --</option>';
                        if (data.length > 0) {
                            data.forEach(cat => {
                                html += `<option value="${cat.id}">${cat.tenvi}</option>`;
                            });
                            catSelect.disabled = false;
                        } else {
                            html = '<option value="">Không có danh mục con</option>';
                            catSelect.disabled = true;
                        }
                        catSelect.innerHTML = html;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        catSelect.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
                    });
            } else {
                catSelect.innerHTML = '<option value="">-- Chọn cấp 1 trước --</option>';
                catSelect.disabled = true;
            }
        });
    </script>
@endpush
