@extends('admin.layouts.app')

@section('title', 'Cập nhật SEO: ' . $page->name)

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">SEO cho trang: <span class="text-primary">{{ $page->name }}</span></h1>
            <a href="{{ route('admin.seo-pages.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Quay lại
            </a>
        </div>

        <form action="{{ route('admin.seo-pages.update', $page->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-8">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="card-title mb-0">Nội dung SEO</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="title_seo" class="form-label fw-bold">Tiêu đề SEO (Title)</label>
                                <input type="text" name="title_seo" id="title_seo" class="form-control @error('title_seo') is-invalid @enderror" value="{{ old('title_seo', $page->title_seo) }}" required>
                                @error('title_seo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="keyword_seo" class="form-label fw-bold">Từ khóa SEO (Keywords)</label>
                                <input type="text" name="keyword_seo" id="keyword_seo" class="form-control @error('keyword_seo') is-invalid @enderror" value="{{ old('keyword_seo', $page->keyword_seo) }}">
                                <small class="text-muted">Phân cách các từ khóa bằng dấu phẩy (,)</small>
                                @error('keyword_seo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-0">
                                <label for="desc_seo" class="form-label fw-bold">Mô tả SEO (Description)</label>
                                <textarea name="desc_seo" id="desc_seo" rows="5" class="form-control @error('desc_seo') is-invalid @enderror">{{ old('desc_seo', $page->desc_seo) }}</textarea>
                                @error('desc_seo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="card-title mb-0">Hình ảnh đại diện (OG Image)</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3 text-center">
                                @if ($page->image_seo && Storage::disk('public')->exists($page->image_seo))
                                    <img src="{{ asset('storage/' . $page->image_seo) }}" class="img-fluid rounded border mb-3 shadow-sm" style="max-height: 200px;">
                                @else
                                    <div class="bg-light p-4 rounded border mb-3 text-muted">
                                        <i class="bi bi-image" style="font-size: 3rem;"></i><br>
                                        Chưa có ảnh
                                    </div>
                                @endif
                            </div>
                            <div class="mb-0">
                                <label for="image_seo" class="form-label fw-bold">Chọn ảnh mới</label>
                                <input type="file" name="image_seo" id="image_seo" class="form-control @error('image_seo') is-invalid @enderror">
                                <small class="text-muted">Ảnh hiển thị khi chia sẻ Link (Facebook, Zalo...)</small>
                                @error('image_seo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary py-2 fw-bold">
                            <i class="bi bi-save me-1"></i> Lưu thay đổi
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
