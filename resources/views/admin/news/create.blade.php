@extends('admin.layouts.app')

@section('title', 'Thêm bài viết mới')

@section('content')
    <div class="container-fluid">
        <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Thêm mới <strong>Bài viết</strong></h1>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.news.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-lg me-1"></i> Hủy
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Lưu dữ liệu
                    </button>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card card-primary card-outline card-outline-tabs shadow-sm mb-4">
                        <div class="card-header p-0 border-bottom-0">
                            <ul class="nav nav-tabs px-3 pt-2" id="langTab" role="tablist">
                                @foreach ($config_langs as $key => $name)
                                    <li class="nav-item">
                                        <a class="nav-link {{ $loop->first ? 'active' : '' }} fw-bold" id="tab-{{ $key }}" data-bs-toggle="pill" href="#content-{{ $key }}" role="tab">{{ $name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="langTabContent">
                                @foreach ($config_langs as $key => $name)
                                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="content-{{ $key }}" role="tabpanel">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Tiêu đề ({{ $name }}) <span class="text-danger">*</span></label>
                                            <input type="text" name="ten{{ $key }}" class="form-control @error('ten' . $key) is-invalid @enderror" value="{{ old('ten' . $key) }}" placeholder="Tiêu đề bài viết...">
                                            @error('ten' . $key) <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Slug ({{ $name }})</label>
                                            <input type="text" name="tenkhongdau{{ $key }}" class="form-control" value="{{ old('tenkhongdau' . $key) }}" placeholder="Để trống sẽ tự tạo">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Mô tả ({{ $name }})</label>
                                            <textarea name="mota{{ $key }}" class="form-control editor-short">{{ old('mota' . $key) }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Nội dung ({{ $name }})</label>
                                            <textarea name="noidung{{ $key }}" class="form-control editor-full">{{ old('noidung' . $key) }}</textarea>
                                        </div>
                                        <hr>
                                        <h6 class="text-primary fw-bold">SEO ({{ $name }})</h6>
                                        <div class="mb-2">
                                            <label class="small fw-bold">Tiêu đề SEO</label>
                                            <input type="text" name="title{{ $key }}" class="form-control" value="{{ old('title' . $key) }}">
                                        </div>
                                        <div class="mb-2">
                                            <label class="small fw-bold">Từ khóa SEO</label>
                                            <input type="text" name="keywords{{ $key }}" class="form-control" value="{{ old('keywords' . $key) }}">
                                        </div>
                                        <div class="mb-2">
                                            <label class="small fw-bold">Mô tả SEO</label>
                                            <textarea name="description{{ $key }}" class="form-control" rows="2">{{ old('description' . $key) }}</textarea>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white fw-bold">Thư viện ảnh (Gallery)</div>
                        <div class="card-body">
                            <input type="file" name="gallery[]" class="form-control" multiple>
                            <small class="text-muted">Giữ Ctrl để chọn nhiều ảnh.</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white py-3 fw-bold">Thông tin chung</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Danh mục cấp 1 <span class="text-danger">*</span></label>
                                <select name="id_list" id="id_list" class="form-select @error('id_list') is-invalid @enderror" required>
                                    <option value="">-- Chọn cấp 1 --</option>
                                    @foreach($lists as $list)
                                        <option value="{{ $list->id }}" {{ old('id_list') == $list->id ? 'selected' : '' }}>{{ $list->tenvi }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Danh mục cấp 2</label>
                                <select name="id_cat" id="id_cat" class="form-select" disabled>
                                    <option value="">-- Chọn cấp 1 trước --</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Ảnh đại diện</label>
                                <input type="file" name="photo" class="form-control mb-2" onchange="previewImage(this)">
                                <div id="photo-preview" class="text-center rounded border bg-light d-flex align-items-center justify-content-center" style="min-height: 150px">
                                    <span class="text-muted small">Xem trước ảnh</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Số thứ tự</label>
                                <input type="number" name="stt" class="form-control" value="{{ old('stt', 0) }}" min="0">
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="hienthi" value="1" id="hienthi" checked>
                                <label class="form-check-label fw-bold" for="hienthi">Hiển thị</label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="noibat" value="1" id="noibat">
                                <label class="form-check-label fw-bold" for="noibat">Nổi bật</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('photo-preview').innerHTML = '<img src="' + e.target.result + '" class="img-fluid rounded border shadow-sm">';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        document.getElementById('id_list').addEventListener('change', function() {
            let listId = this.value;
            let catSelect = document.getElementById('id_cat');
            catSelect.innerHTML = '<option value="">Đang tải...</option>';
            catSelect.disabled = true;

            if (listId) {
                let url = "{{ route('admin.news.get-cats', ':id') }}".replace(':id', listId);
                fetch(url).then(r => r.json()).then(data => {
                    let html = '<option value="">-- Chọn cấp 2 --</option>';
                    if (data.length > 0) {
                        data.forEach(cat => { html += `<option value="${cat.id}">${cat.tenvi}</option>`; });
                        catSelect.disabled = false;
                    } else {
                        html = '<option value="">Không có con</option>';
                        catSelect.disabled = true;
                    }
                    catSelect.innerHTML = html;
                });
            } else {
                catSelect.innerHTML = '<option value="">-- Chọn cấp 1 trước --</option>';
                catSelect.disabled = true;
            }
        });
    </script>
@endpush
