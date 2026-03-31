<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductList;
use Illuminate\Support\Str;

class ProductListSeeder extends Seeder
{
    public function run(): void
    {
        $lists = [
            ['tenvi' => 'Điện thoại & Phụ kiện', 'stt' => 1, 'hienthi' => true],
            ['tenvi' => 'Laptop & Máy tính',     'stt' => 2, 'hienthi' => true],
            ['tenvi' => 'Âm thanh & Loa',        'stt' => 3, 'hienthi' => true],
            ['tenvi' => 'Đồ gia dụng thông minh','stt' => 4, 'hienthi' => true],
            ['tenvi' => 'Phụ kiện thời trang',   'stt' => 5, 'hienthi' => false],
        ];

        foreach ($lists as $list) {
            ProductList::create([
                'tenvi' => $list['tenvi'],
                'tenen' => null,

                'tenkhongdauvi' => Str::slug($list['tenvi']),
                'tenkhongdauen' => null,

                'stt' => $list['stt'],
                'hienthi' => $list['hienthi'],
                'noibat' => false,
                'type' => 'san-pham',
            ]);
        }
    }
}
