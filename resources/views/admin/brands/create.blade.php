@extends('admin.layouts.app')

@section('title', 'Thêm thương hiệu mới')

@section('content')
    <div class="container-fluid">
        <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Thêm mới <strong>Thương hiệu</strong></h1>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-lg me-1"></i> Hủy
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Lưu dữ liệu
                    </button>
                </div>
            </div>

            <div class="row">
                {{-- CỘT TRÁI: ĐA NGÔN NGỮ --}}
                <div class="col-md-8">
                    <div class="card card-primary card-outline card-outline-tabs shadow-sm">
                        <div class="card-header p-0 border-bottom-0">
                            <ul class="nav nav-tabs px-3 pt-2" id="langTab" role="tablist">
                                @foreach ($config_langs as $key => $name)
                                    <li class="nav-item">
                                        <a class="nav-link {{ $loop->first ? 'active' : '' }} fw-bold" id="tab-{{ $key }}"
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

                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Tên thương hiệu ({{ $name }}) <span class="text-danger">*</span></label>
                                            <input type="text" name="ten{{ $key }}" class="form-control @error('ten' . $key) is-invalid @enderror" value="{{ old('ten' . $key) }}" placeholder="Tên thương hiệu (vd: Nike, Adidas)...">
                                            @error('ten' . $key)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Slug ({{ $name }})</label>
                                            <input type="text" name="tenkhongdau{{ $key }}" class="form-control" value="{{ old('tenkhongdau' . $key) }}" placeholder="Để trống sẽ tự tạo">
                                        </div>

                                        <hr>
                                        <h6 class="text-primary fw-bold"><i class="bi bi-search me-1"></i> SEO ({{ $name }})</h6>
                                        <div class="row g-2">
                                            <div class="col-12 mb-2">
                                                <label class="small fw-bold">Tiêu đề SEO</label>
                                                <input type="text" name="title{{ $key }}" class="form-control" value="{{ old('title' . $key) }}">
                                            </div>
                                            <div class="col-12 mb-2">
                                                <label class="small fw-bold">Từ khóa SEO</label>
                                                <input type="text" name="keywords{{ $key }}" class="form-control" value="{{ old('keywords' . $key) }}">
                                            </div>
                                            <div class="col-12">
                                                <label class="small fw-bold">Mô tả SEO</label>
                                                <textarea name="description{{ $key }}" class="form-control" rows="2">{{ old('description' . $key) }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CỘT PHẢI: CHUNG --}}
                <div class="col-md-4">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white py-3 fw-bold">Thông tin chung</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Logo thương hiệu</label>
                                <input type="file" name="photo" class="form-control mb-2" onchange="previewImage(this)">
                                <div id="photo-preview" class="text-center rounded border d-flex align-items-center justify-content-center bg-light" style="min-height: 150px">
                                    <span class="text-muted small">Xem trước logo</span>
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
                    document.getElementById('photo-preview').innerHTML = '<img src="' + e.target.result + '" class="img-fluid rounded border shadow-sm p-1 bg-white">';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush
