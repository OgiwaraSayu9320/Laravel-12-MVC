@extends('admin.layouts.app')

@section('title', 'Quản lý SEO các trang tĩnh')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Quản lý SEO các trang</h1>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th width="5%">ID</th>
                                <th width="20%">Tên trang</th>
                                <th width="15%">Slug</th>
                                <th width="30%">Tiêu đề SEO</th>
                                <th width="20%">Ảnh đại diện</th>
                                <th width="10%" class="text-end">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pages as $page)
                                <tr>
                                    <td>{{ $page->id }}</td>
                                    <td><strong>{{ $page->name }}</strong></td>
                                    <td><code class="text-primary">{{ $page->slug }}</code></td>
                                    <td>{{ $page->title_seo ?? '---' }}</td>
                                    <td>
                                        @if ($page->image_seo && Storage::disk('public')->exists($page->image_seo))
                                            <img src="{{ asset('storage/' . $page->image_seo) }}" height="40" class="rounded shadow-xs">
                                        @else
                                            <span class="text-muted small">Chưa có ảnh</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.seo-pages.edit', $page->id) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil-square"></i> Cập nhật
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Không có dữ liệu trang.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
