@extends('admin.layouts.app')

@section('title', 'Xem chi tiết liên hệ')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Xem chi tiết liên hệ</h1>
        <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-4 h-100">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 fw-bold">Thông tin người gửi</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3 d-flex align-items-center">
                        <div class="bg-light p-2 rounded me-3 text-primary"><i class="bi bi-person fs-4"></i></div>
                        <div>
                            <div class="small text-muted">Họ tên</div>
                            <div class="fw-bold fs-5">{{ $item->ho_ten }}</div>
                        </div>
                    </div>
                    <hr class="my-3 opacity-25">
                    <div class="mb-3 d-flex align-items-center">
                        <div class="bg-light p-2 rounded me-3 text-success"><i class="bi bi-envelope fs-4"></i></div>
                        <div>
                            <div class="small text-muted">Email</div>
                            <div class="fw-bold">{{ $item->email }}</div>
                        </div>
                    </div>
                    <hr class="my-3 opacity-25">
                    <div class="mb-3 d-flex align-items-center">
                        <div class="bg-light p-2 rounded me-3 text-info"><i class="bi bi-telephone fs-4"></i></div>
                        <div>
                            <div class="small text-muted">Điện thoại</div>
                            <div class="fw-bold">{{ $item->phone ?? '---' }}</div>
                        </div>
                    </div>
                    <hr class="my-3 opacity-25">
                    <div class="mb-3 d-flex align-items-center">
                        <div class="bg-light p-2 rounded me-3 text-warning"><i class="bi bi-tag fs-4"></i></div>
                        <div>
                            <div class="small text-muted">Loại liên hệ</div>
                            <div class="fw-bold text-uppercase">{{ $item->type }}</div>
                        </div>
                    </div>
                    <hr class="my-3 opacity-25">
                    <div class="mb-0 d-flex align-items-center">
                        <div class="bg-light p-2 rounded me-3 text-secondary"><i class="bi bi-calendar-event fs-4"></i></div>
                        <div>
                            <div class="small text-muted">Ngày gửi</div>
                            <div class="fw-bold">{{ $item->created_at->format('d/m/Y H:i:s') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-light py-3 border-bottom-0">
                    <h5 class="mb-0 fw-bold">Nội dung tin nhắn</h5>
                </div>
                <div class="card-body">
                    <div class="p-4 bg-light rounded" style="min-height: 250px; line-height: 1.8; white-space: pre-wrap;">{{ $item->noi_dung }}</div>
                </div>
                <div class="card-footer bg-transparent py-3 border-top-0 d-flex justify-content-between align-items-center">
                    <div>
                        @if($item->da_doc)
                            <span class="badge bg-success-subtle text-success px-3 py-2 border border-success-subtle"><i class="bi bi-check2-circle me-1"></i> Trạng thái: Đã đọc</span>
                        @else
                            <span class="badge bg-danger-subtle text-danger px-3 py-2 border border-danger-subtle"><i class="bi bi-exclamation-circle me-1"></i> Trạng thái: Chưa đọc</span>
                        @endif
                    </div>
                    <form action="{{ route('admin.contacts.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa liên hệ này?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-trash me-1"></i> Xóa vĩnh viễn
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
