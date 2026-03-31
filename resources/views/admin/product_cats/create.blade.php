@extends('admin.layouts.app')
@section('title', 'Thêm mới sản phẩm')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title mb-0">Thêm sản phẩm mới</h3>
        </div>

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        {{-- Tên sản phẩm --}}
                        <div class="mb-3">
                            <label class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required
                                placeholder="Nhập tên sản phẩm...">
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Slug (Tự động hoặc nhập tay) --}}
                        <div class="mb-3">
                            <label class="form-label">Đường dẫn (Slug)</label>
                            <input type="text" name="slug" class="form-control" value="{{ old('slug') }}"
                                placeholder="Để trống sẽ tự tạo theo tên">
                        </div>

                        {{-- Mô tả ngắn --}}
                        <div class="mb-3">
                            <label class="form-label editor-short">Mô tả ngắn</label>
                            <textarea name="desc" class="form-control" rows="3">{{ old('desc') }}</textarea>
                        </div>

                        {{-- Nội dung chi tiết (Chờ gắn CKEditor sau này) --}}
                        <div class="mb-3">
                            <label class="form-label">Nội dung chi tiết</label>
                            <textarea name="content" class="form-control editor-full" rows="10">{{ old('content') }}</textarea>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header">Thư viện ảnh (Gallery)</div>
                            <div class="card-body">
                                <input type="file" name="gallery[]" class="form-control" multiple>
                                <small class="text-muted">Giữ phím Ctrl để chọn nhiều ảnh</small>
                            </div>
                        </div>

                        {{-- SEO BOX (Mặc định đóng) --}}
                        <div class="card card-outline card-info collapsed-card mt-4">
                            <div class="card-header bg-light">
                                <h3 class="card-title text-info mb-0" style="font-size: 1rem;"><i class="bi bi-google"></i>
                                    Cấu hình SEO</h3>
                                <div class="card-tools float-end">
                                    <button type="button" class="btn btn-tool btn-sm" data-bs-toggle="collapse"
                                        data-bs-target="#seoBox">
                                        <i class="bi bi-plus-lg"></i> Mở rộng
                                    </button>
                                </div>
                            </div>
                            <div class="card-body collapse" id="seoBox">
                                <div class="mb-2">
                                    <label>Meta Title</label>
                                    <input type="text" name="title_seo" class="form-control"
                                        value="{{ old('title_seo') }}">
                                </div>
                                <div class="mb-2">
                                    <label>Meta Keywords</label>
                                    <input type="text" name="keyword_seo" class="form-control"
                                        value="{{ old('keyword_seo') }}">
                                </div>
                                <div class="mb-2">
                                    <label>Meta Description</label>
                                    <textarea name="desc_seo" class="form-control">{{ old('desc_seo') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        {{-- Chọn Danh mục --}}
                        <div class="card mb-3">
                            <div class="card-header bg-primary text-white">Phân loại</div>
                            <div class="card-body">
                                {{-- Cấp 1 --}}
                                <div class="mb-3">
                                    <label class="form-label">Danh mục Cấp 1 <span class="text-danger">*</span></label>
                                    <select name="id_list" class="form-select" id="list_select" required>
                                        <option value="">-- Chọn danh mục --</option>
                                        @foreach ($lists as $item)
                                            <option value="{{ $item->id }}"
                                                {{ old('id_list') == $item->id ? 'selected' : '' }}>
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Cấp 2 (Ajax load) --}}
                                <div class="mb-3">
                                    <label class="form-label">Danh mục Cấp 2</label>
                                    <select name="id_cat" class="form-select" id="cat_select">
                                        <option value="0">-- Chọn cấp 1 trước --</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Giá bán --}}
                        <div class="card mb-3">
                            <div class="card-body">
                                <label class="form-label">Giá bán (VNĐ)</label>
                                <input type="number" name="price" class="form-control" value="{{ old('price', 0) }}"
                                    min="0">
                            </div>
                        </div>

                        {{-- Ảnh đại diện --}}
                        <div class="card mb-3">
                            <div class="card-header">Ảnh đại diện</div>
                            <div class="card-body text-center">
                                <input type="file" name="image" class="form-control mb-2"
                                    onchange="previewImage(this)">
                                <div id="image-preview"
                                    style="min-height: 150px; border: 1px dashed #ddd; display: flex; align-items: center; justify-content: center;">
                                    <span class="text-muted">Chưa chọn ảnh</span>
                                </div>
                            </div>
                        </div>

                        {{-- Trạng thái --}}
                        <div class="mb-3">
                            <label class="form-label">Trạng thái</label>
                            <select name="status" class="form-select">
                                <option value="1">Hiển thị</option>
                                <option value="0">Ẩn</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer text-end">
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Hủy bỏ</a>
                <button type="submit" class="btn btn-primary px-4">Lưu sản phẩm</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        // 1. Preview ảnh trước khi upload
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('image-preview').innerHTML =
                        '<img src="' + e.target.result + '" width="100%" class="rounded">';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // 2. Ajax load danh mục cấp 2
        document.addEventListener('DOMContentLoaded', function() {
            const listSelect = document.getElementById('list_select');
            const catSelect = document.getElementById('cat_select');

            listSelect.addEventListener('change', function() {
                let listId = this.value;

                // Reset dropdown cấp 2
                catSelect.innerHTML = '<option value="0">Đang tải...</option>';

                if (listId) {
                    // Gọi Route Ajax mà ta đã định nghĩa
                    fetch('{{ url('admin/products/get-cats') }}/' + listId)
                        .then(response => response.json())
                        .then(data => {
                            let html = '<option value="0">-- Chọn cấp 2 --</option>';
                            if (data.length > 0) {
                                data.forEach(cat => {
                                    html += `<option value="${cat.id}">${cat.name}</option>`;
                                });
                            } else {
                                html = '<option value="0">Không có danh mục con</option>';
                            }
                            catSelect.innerHTML = html;
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            catSelect.innerHTML = '<option value="0">Lỗi tải dữ liệu</option>';
                        });
                } else {
                    catSelect.innerHTML = '<option value="0">-- Chọn cấp 1 trước --</option>';
                }
            });
        });
    </script>
@endpush
