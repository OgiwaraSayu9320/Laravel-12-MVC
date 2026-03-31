@extends('admin.layouts.app')
@section('title', 'Cập nhật sản phẩm')

@section('content')
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            {{-- CỘT TRÁI: THÔNG TIN ĐA NGÔN NGỮ & GALLERY --}}
            <div class="col-md-8">
                <div class="card card-primary card-outline card-outline-tabs">
                    <div class="card-header p-0 border-bottom-0">
                        <ul class="nav nav-tabs" id="langTab" role="tablist">
                            @foreach ($config_langs as $key => $name)
                                <li class="nav-item">
                                    <a class="nav-link {{ $loop->first ? 'active' : '' }}" 
                                       id="tab-{{ $key }}" data-bs-toggle="pill" 
                                       href="#content-{{ $key }}" role="tab">
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
                                        <label class="form-label">Tên sản phẩm ({{ $name }})</label>
                                        <input type="text" name="ten{{ $key }}" 
                                               class="form-control @error('ten'.$key) is-invalid @enderror"
                                               value="{{ old('ten'.$key, $product->{'ten'.$key}) }}">
                                        @error('ten'.$key) <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    {{-- Đường dẫn (Slug) --}}
                                    <div class="mb-3">
                                        <label class="form-label">Đường dẫn / Slug ({{ $name }})</label>
                                        <input type="text" name="tenkhongdau{{ $key }}" class="form-control"
                                               value="{{ old('tenkhongdau'.$key, $product->{'tenkhongdau'.$key}) }}">
                                        <small class="text-muted">Để trống sẽ tự tạo theo tên</small>
                                    </div>

                                    {{-- Mô tả ngắn --}}
                                    <div class="mb-3">
                                        <label class="form-label">Mô tả ({{ $name }})</label>
                                        <textarea name="mota{{ $key }}" class="form-control editor-short" rows="3">{{ old('mota'.$key, $product->{'mota'.$key}) }}</textarea>
                                    </div>

                                    {{-- Nội dung chi tiết --}}
                                    <div class="mb-3">
                                        <label class="form-label">Nội dung ({{ $name }})</label>
                                        <textarea name="noidung{{ $key }}" class="form-control editor-full">{{ old('noidung'.$key, $product->{'noidung'.$key}) }}</textarea>
                                    </div>

                                    <hr>
                                    {{-- SEO theo ngôn ngữ --}}
                                    <h6 class="text-primary"><i class="bi bi-google"></i> Cấu hình SEO ({{ $name }})</h6>
                                    <div class="row">
                                        <div class="col-12 mb-2">
                                            <label>Title SEO</label>
                                            <input type="text" name="title{{ $key }}" class="form-control" 
                                                   value="{{ old('title'.$key, $product->{'title'.$key}) }}">
                                        </div>
                                        <div class="col-12 mb-2">
                                            <label>Keywords SEO</label>
                                            <input type="text" name="keywords{{ $key }}" class="form-control" 
                                                   value="{{ old('keywords'.$key, $product->{'keywords'.$key}) }}">
                                        </div>
                                        <div class="col-12 mb-2">
                                            <label>Description SEO</label>
                                            <textarea name="description{{ $key }}" class="form-control" rows="2">{{ old('description'.$key, $product->{'description'.$key}) }}</textarea>
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
                        <label class="mb-2">Tải thêm ảnh mới</label>
                        <input type="file" name="gallery[]" class="form-control mb-3" multiple>
                        
                        @if($product->galleries->count() > 0)
                            <div class="row g-2">
                                @foreach($product->galleries as $gal)
                                    <div class="col-md-2 col-4 text-center">
                                        <div class="border p-1 rounded position-relative">
                                            <img src="{{ asset('storage/' . $gal->photo) }}" class="img-fluid rounded" style="height: 100px; object-fit: cover;">
                                            <div class="form-check mt-2 d-flex justify-content-center">
                                                {{-- QUAN TRỌNG: value là ID của gallery --}}
                                                <input class="form-check-input border-danger" type="checkbox" 
                                                       name="delete_gallery[]" value="{{ $gal->id }}" id="gal_{{ $gal->id }}">
                                                <label class="form-check-label text-danger small ms-1" for="gal_{{ $gal->id }}">Xóa</label>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted small">Chưa có ảnh nào trong thư viện.</p>
                        @endif
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
                            <label class="form-label fw-bold">Danh mục cấp 1 (*)</label>
                            <select name="id_list" class="form-select" id="list_select" required>
                                <option value="">-- Chọn danh mục --</option>
                                @foreach ($lists as $list)
                                    <option value="{{ $list->id }}" {{ $product->id_list == $list->id ? 'selected' : '' }}>
                                        {{ $list->tenvi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Danh mục cấp 2 --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Danh mục cấp 2</label>
                            <select name="id_cat" class="form-select" id="cat_select">
                                <option value="">-- Chọn cấp 2 --</option>
                                @foreach ($cats as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ old('id_cat', $product->id_cat) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->tenvi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Thương hiệu --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Thương hiệu</label>
                            <select name="id_brand" class="form-select @error('id_brand') is-invalid @enderror">
                                <option value="">-- Chọn thương hiệu --</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}"
                                        {{ old('id_brand', $product->id_brand) == $brand->id ? 'selected' : '' }}>
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
                            <label class="form-label fw-bold">Giá bán</label>
                            <div class="input-group">
                                <input type="number" name="gia" class="form-control" value="{{ old('gia', $product->gia) }}" min="0">
                                <span class="input-group-text">VNĐ</span>
                            </div>
                        </div>

                        {{-- Ảnh đại diện --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Ảnh đại diện</label>
                            <input type="file" name="photo" class="form-control mb-2" onchange="previewImage(this)">
                            <div id="photo-preview" class="text-center">
                                @if($product->photo)
                                    <img src="{{ asset('storage/' . $product->photo) }}" class="img-fluid rounded border">
                                @endif
                            </div>
                        </div>

                        {{-- Trạng thái --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Trạng thái</label>
                            <select name="hienthi" class="form-select">
                                <option value="1" {{ $product->hienthi == 1 ? 'selected' : '' }}>Hiển thị</option>
                                <option value="0" {{ $product->hienthi == 0 ? 'selected' : '' }}>Ẩn</option>
                            </select>
                        </div>

                        <hr>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Cập nhật</button>
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
            reader.onload = function (e) {
                document.getElementById('photo-preview').innerHTML = 
                    '<img src="'+e.target.result+'" class="img-fluid rounded border mt-2">';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Ajax load danh mục cấp 2
    document.getElementById('list_select').addEventListener('change', function() {
        let listId = this.value;
        let catSelect = document.getElementById('cat_select');
        
        catSelect.innerHTML = '<option value="">Đang tải...</option>';
        catSelect.disabled = true;

        if(listId) {
            // Sử dụng route Laravel để tạo URL chuẩn
            let url = "{{ route('admin.products.get-cats', ':id') }}";
            url = url.replace(':id', listId);

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    let html = '<option value="">-- Chọn cấp 2 --</option>';
                    data.forEach(cat => {
                        html += `<option value="${cat.id}">${cat.tenvi}</option>`;
                    });
                    catSelect.innerHTML = html;
                    catSelect.disabled = false;
                })
                .catch(err => {
                    console.error('Lỗi:', err);
                    catSelect.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
                });
        } else {
            catSelect.innerHTML = '<option value="">-- Chọn cấp 2 --</option>';
            catSelect.disabled = true;
        }
    });
</script>
@endpush