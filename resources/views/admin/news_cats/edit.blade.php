@extends('admin.layouts.app')

@section('title', 'Cập nhật danh mục tin tức cấp 2')

@section('content')
    <div class="container-fluid">
        <form action="{{ route('admin.news-cats.update', $cat->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Cập nhật <strong>Danh mục cấp 2</strong>: <span class="text-primary">{{ $cat->tenvi }}</span></h1>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.news-cats.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-lg me-1"></i> Hủy
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Lưu dữ liệu
                    </button>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
                    <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-md-8">
                    <div class="card card-primary card-outline card-outline-tabs shadow-sm">
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
                                            <label class="form-label fw-bold">Tên danh mục ({{ $name }}) <span class="text-danger">*</span></label>
                                            <input type="text" name="ten{{ $key }}" class="form-control @error('ten' . $key) is-invalid @enderror" value="{{ old('ten' . $key, $cat->{"ten$key"}) }}" required>
                                            @error('ten' . $key) <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Slug ({{ $name }})</label>
                                            <input type="text" name="tenkhongdau{{ $key }}" class="form-control" value="{{ old('tenkhongdau' . $key, $cat->{"tenkhongdau$key"}) }}">
                                        </div>
                                        <hr>
                                        <h6 class="text-primary fw-bold">SEO ({{ $name }})</h6>
                                        <div class="mb-2">
                                            <label class="small fw-bold">Tiêu đề SEO</label>
                                            <input type="text" name="title{{ $key }}" class="form-control" value="{{ old('title' . $key, $cat->{"title$key"}) }}">
                                        </div>
                                        <div class="mb-2">
                                            <label class="small fw-bold">Từ khóa SEO</label>
                                            <input type="text" name="keywords{{ $key }}" class="form-control" value="{{ old('keywords' . $key, $cat->{"keywords$key"}) }}">
                                        </div>
                                        <div class="mb-2">
                                            <label class="small fw-bold">Mô tả SEO</label>
                                            <textarea name="description{{ $key }}" class="form-control" rows="2">{{ old('description' . $key, $cat->{"description$key"}) }}</textarea>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white py-3 fw-bold">Thông tin chung</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Danh mục cấp 1 <span class="text-danger">*</span></label>
                                <select name="id_list" class="form-select @error('id_list') is-invalid @enderror" required>
                                    <option value="">-- Chọn cấp 1 --</option>
                                    @foreach($lists as $list)
                                        <option value="{{ $list->id }}" {{ old('id_list', $cat->id_list) == $list->id ? 'selected' : '' }}>{{ $list->tenvi }}</option>
                                    @endforeach
                                </select>
                                @error('id_list') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Ảnh đại diện</label>
                                <input type="file" name="photo" class="form-control mb-2" onchange="previewImage(this)">
                                <div id="photo-preview" class="text-center rounded border bg-light d-flex align-items-center justify-content-center" style="min-height: 150px">
                                    @if($cat->photo && Storage::disk('public')->exists($cat->photo))
                                        <img src="{{ asset('storage/' . $cat->photo) }}" class="img-fluid rounded border shadow-sm">
                                    @else
                                        <span class="text-muted small">Cập nhật ảnh</span>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Số thứ tự</label>
                                <input type="number" name="stt" class="form-control" value="{{ old('stt', $cat->stt) }}" min="0">
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="hienthi" value="1" id="hienthi" {{ $cat->hienthi ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="hienthi">Hiển thị</label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="noibat" value="1" id="noibat" {{ $cat->noibat ? 'checked' : '' }}>
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
    </script>
@endpush
