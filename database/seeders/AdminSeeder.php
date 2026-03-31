<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin; // Đảm bảo bạn đã có Model Admin

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kiểm tra nếu chưa có admin thì mới tạo (tránh lỗi Duplicate entry khi chạy lại)
        if (!Admin::where('email', 'nguyentri9320@gmail.com')->exists()) {

            Admin::create([
                'name' => 'Administrator',
                'email' => 'nguyentri9320@gmail.com',
                'password' => Hash::make('123qwe'), // Mật khẩu: 123456
                'role' => 'admin', // Set quyền cao nhất
            ]);

        }
    }
}