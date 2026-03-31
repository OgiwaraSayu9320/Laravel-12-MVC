@extends('admin.layouts.app')

@section('title', 'Quản lý Liên hệ')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Quản lý Liên hệ</h1>
        </div>

        <form method="GET" action="{{ route('admin.contacts.index') }}" class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="Tìm tên, email, sđt..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="type" class="form-select">
                            <option value="">-- Loại liên hệ --</option>
                            <option value="lien-he" {{ request('type') == 'lien-he' ? 'selected' : '' }}>Liên hệ</option>
                            <option value="tu-van" {{ request('type') == 'tu-van' ? 'selected' : '' }}>Tư vấn</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="da_doc" class="form-select">
                            <option value="">-- Trạng thái --</option>
                            <option value="0" {{ request('da_doc') === '0' ? 'selected' : '' }}>Chưa đọc</option>
                            <option value="1" {{ request('da_doc') === '1' ? 'selected' : '' }}>Đã đọc</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">Lọc</button>
                        <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
                    </div>
                </div>
            </div>
        </form>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th width="5%">ID</th>
                                <th width="20%">Họ tên</th>
                                <th width="20%">Email / Điện thoại</th>
                                <th width="15%">Loại</th>
                                <th width="15%">Ngày gửi</th>
                                <th width="10%">Trạng thái</th>
                                <th width="15%" class="text-end">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items as $item)
                                <tr class="{{ !$item->da_doc ? 'fw-bold border-start border-4 border-primary' : '' }}">
                                    <td>{{ $item->id }}</td>
                                    <td>
                                        <a href="{{ route('admin.contacts.show', $item->id) }}" class="text-decoration-none text-dark">
                                            {{ $item->ho_ten }}
                                        </a>
                                    </td>
                                    <td>
                                        <div><i class="bi bi-envelope me-1"></i> {{ $item->email }}</div>
                                        <div class="small text-muted"><i class="bi bi-telephone me-1"></i> {{ $item->phone ?? '---' }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info text-dark">{{ $item->type }}</span>
                                    </td>
                                    <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if($item->da_doc)
                                            <span class="badge bg-success">Đã đọc</span>
                                        @else
                                            <span class="badge bg-danger">Chưa đọc</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.contacts.show', $item->id) }}" class="btn btn-sm btn-outline-primary" title="Xem chi tiết">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.contacts.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa liên hệ này?');">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" title="Xóa"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">Không tìm thấy yêu cầu liên hệ nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($items->hasPages())
                <div class="card-footer d-flex justify-content-end py-3">
                    {{ $items->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
@endsection
