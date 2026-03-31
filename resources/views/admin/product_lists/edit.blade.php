@extends('admin.layouts.app')
@section('title', 'Chỉnh sửa sản phẩm')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title mb-0">Cập nhật sản phẩm: {{ $product->name }}</h3>
        </div>

        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') {{-- Bắt buộc phải có dòng này --}}

            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label>Tên sản phẩm</label>
                            <input type="text" name="name" class="form-control"
                                value="{{ old('name', $product->name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label>Đường dẫn (Slug)</label>
                            <input type="text" name="slug" class="form-control"
                                value="{{ old('slug', $product->slug) }}" placeholder="Để trống sẽ tự tạo theo tên">
                            <small class="text-muted">Ví dụ: dien-thoai-iphone-15</small>
                        </div>
                        <div class="mb-3">
                            <label>Mô tả ngắn</label>
                            <textarea name="desc" class="form-control editor-short" rows="3">{{ old('desc', $product->desc) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nội dung chi tiết</label>
                            <textarea name="content" class="form-control editor-full" rows="10">{{ old('content', $product->content) }}</textarea>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header">Thư viện ảnh (Gallery)</div>
                            <div class="card-body">

                                {{-- Input upload thêm ảnh --}}
                                <label class="mb-2">Thêm ảnh mới</label>
                                <input type="file" name="gallery[]" class="form-control mb-3" multiple>

                                {{-- Hiển thị ảnh cũ --}}
                                @if ($product->gallery && count($product->gallery) > 0)
                                    <label>Ảnh hiện tại (Tích vào để xóa)</label>
                                    <div class="row g-2 mt-2">   
                                        {{-- Lấy danh sách ảnh --}}
                                        @foreach ($product->galleries as $img)
                                            <div class="item">
                                                <img src="{{ asset('storage/' . $img->photo) }}" alt="{{ $img->name }}">
                                                <p>{{ $img->name }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted small">Chưa có ảnh nào trong thư viện.</p>
                                @endif
                            </div>
                        </div>

                        {{-- Phần SEO (Copy từ trang Create sang) --}}
                        <div class="card card-outline card-info collapsed-card">
                            <div class="card-header">
                                <h3 class="card-title">Cấu hình SEO</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-bs-toggle="collapse"
                                        data-bs-target="#seoBox">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body collapse" id="seoBox">
                                <div class="mb-2">
                                    <label>Title SEO</label>
                                    <input type="text" name="title_seo" class="form-control"
                                        value="{{ old('title_seo', $product->title_seo) }}">
                                </div>
                                <div class="mb-2">
                                    <label>Meta Keywords</label>
                                    <input type="text" name="keyword_seo" class="form-control"
                                        value="{{ old('keyword_seo', $product->keyword_seo) }}"
                                        placeholder="Ngăn cách bằng dấu phẩy. VD: áo thun, áo giá rẻ">
                                </div>
                                <div class="mb-2">
                                    <label>Desc SEO</label>
                                    <textarea name="desc_seo" class="form-control">{{ old('desc_seo', $product->desc_seo) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        {{-- 1. Chọn Danh mục --}}
                        <div class="mb-3">
                            <label>Danh mục Cấp 1</label>
                            <select name="id_list" class="form-select" id="list_select">
                                <option value="">-- Chọn cấp 1 --</option>
                                @foreach ($lists as $item)
                                    <option value="{{ $item->id }}"
                                        {{ $item->id == $product->id_list ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Danh mục Cấp 2</label>
                            <select name="id_cat" class="form-select" id="cat_select">
                                <option value="0">-- Chọn cấp 2 --</option>
                                {{-- Loop sẵn danh sách con của thằng cha hiện tại --}}
                                @foreach ($cats as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ $cat->id == $product->id_cat ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Giá bán</label>
                            <input type="number" name="price" class="form-control"
                                value="{{ old('price', $product->price) }}">
                        </div>

                        {{-- 2. Ảnh đại diện --}}
                        <div class="mb-3">
                            <label>Ảnh đại diện</label>
                            <input type="file" name="image" class="form-control mb-2" onchange="previewImage(this)">

                            {{-- Hiển thị ảnh cũ --}}
                            <div id="image-preview">
                                @if ($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" width="100%"
                                        class="rounded border">
                                @endif
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Trạng thái</label>
                            <select name="status" class="form-select">
                                <option value="1" {{ $product->status == 1 ? 'selected' : '' }}>Hiển thị</option>
                                <option value="0" {{ $product->status == 0 ? 'selected' : '' }}>Ẩn</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Cập nhật</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Hủy</a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        // Hàm xem trước ảnh khi chọn file mới
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    // Thay thế nội dung div preview bằng ảnh mới
                    document.getElementById('image-preview').innerHTML =
                        '<img src="' + e.target.result + '" width="100%" class="rounded border mt-2">';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Khi thay đổi Cấp 1
        document.getElementById('list_select').addEventListener('change', function() {
            let listId = this.value;
            let catSelect = document.getElementById('cat_select');

            // Reset cấp 2 về mặc định
            catSelect.innerHTML = '<option value="0">Loading...</option>';

            if (listId) {
                // Gọi API lấy danh sách con (Bạn cần tạo 1 route API trả về json)
                // Ví dụ này mình giả định bạn có route lấy danh mục
                fetch('/admin/api/get-cats/' + listId)
                    .then(response => response.json())
                    .then(data => {
                        let html = '<option value="0">-- Chọn cấp 2 --</option>';
                        data.forEach(cat => {
                            html += `<option value="${cat.id}">${cat.name}</option>`;
                        });
                        catSelect.innerHTML = html;
                    });
            } else {
                catSelect.innerHTML = '<option value="0">-- Chọn cấp 2 --</option>';
            }
        });
    </script>
@endpush
