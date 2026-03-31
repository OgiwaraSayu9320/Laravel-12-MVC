<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Hiển thị trang cá nhân
     */
    public function index()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.profile.index', compact('admin'));
    }

    /**
     * Cập nhật thông tin cơ bản
     */
    public function update(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('admins')->ignore($admin->id),
            ],
        ], [
            'name.required'  => 'Vui lòng nhập họ tên.',
            'email.required' => 'Vui lòng nhập email.',
            'email.unique'   => 'Email này đã được sử dụng bởi tài khoản khác.',
        ]);

        $admin->update($data);

        return back()->with('success', 'Cập nhật thông tin cá nhân thành công!');
    }

    /**
     * Cập nhật mật khẩu
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password'     => 'required|min:6|confirmed',
        ], [
            'old_password.required' => 'Vui lòng nhập mật khẩu cũ.',
            'password.required'     => 'Vui lòng nhập mật khẩu mới.',
            'password.min'          => 'Mật khẩu mới phải từ 6 ký tự trở lên.',
            'password.confirmed'    => 'Xác nhận mật khẩu mới không khớp.',
        ]);

        $admin = Auth::guard('admin')->user();

        // Kiểm tra mật khẩu cũ
        if (!Hash::check($request->old_password, $admin->password)) {
            return back()->withErrors(['old_password' => 'Mật khẩu cũ không chính xác.']);
        }

        // Cập nhật mật khẩu mới
        $admin->password = Hash::make($request->password);
        $admin->save();

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }
}
