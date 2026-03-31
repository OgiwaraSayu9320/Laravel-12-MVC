@extends('admin.layouts.app')

@section('title', 'Thông tin cá nhân')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12 mb-4">
            <h1 class="h3 mb-0 text-gray-800">Trang cá nhân của bạn</h1>
            <p class="text-muted small">Quản lý thông tin tài khoản và bảo mật mật khẩu.</p>
        </div>

        <div class="col-md-6 mb-4">
            {{-- Cập nhật thông tin --}}
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="m-0 font-weight-bold text-primary"><i class="bi bi-person-circle me-1"></i> Thông tin tài khoản</h5>
                </div>
                <div class="card-body">
                    @if(session('success') && !session('password_update'))
                        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label fw-bold">Họ và tên</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $admin->name) }}">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Email đăng nhập</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $admin->email) }}">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Vai trò</label>
                            <input type="text" class="form-control bg-light" value="{{ strtoupper($admin->role) }}" readonly disabled>
                            <small class="text-muted">Vai trò hệ thống không thể tự thay đổi.</small>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mt-2">
                            <i class="bi bi-save me-1"></i> Lưu thông tin
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            {{-- Đổi mật khẩu --}}
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="m-0 font-weight-bold text-danger"><i class="bi bi-shield-lock me-1"></i> Đổi mật khẩu</h5>
                </div>
                <div class="card-body">
                    @if(session('success') && session('password_update'))
                        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Mật khẩu cũ</label>
                            <input type="password" name="old_password" class="form-control @error('old_password') is-invalid @enderror" placeholder="••••••••">
                            @error('old_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Mật khẩu mới</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••">
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Xác nhận mật khẩu mới</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••">
                        </div>

                        <button type="submit" class="btn btn-danger w-100 mt-2">
                            <i class="bi bi-key me-1"></i> Cập nhật mật khẩu
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
