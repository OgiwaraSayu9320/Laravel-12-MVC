<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductCat;
use App\Models\ProductList;
use Illuminate\Support\Str;

class ProductCatSeeder extends Seeder
{
    public function run(): void
    {
        $list1 = ProductList::where('tenvi', 'Điện thoại & Phụ kiện')->first()->id ?? 1;
        $list2 = ProductList::where('tenvi', 'Laptop & Máy tính')->first()->id ?? 2;

        $cats = [
            ['tenvi' => 'iPhone',               'id_list' => $list1, 'stt' => 1, 'hienthi' => true],
            ['tenvi' => 'Samsung Galaxy',       'id_list' => $list1, 'stt' => 2, 'hienthi' => true],
            ['tenvi' => 'Ốp lưng & Cường lực',  'id_list' => $list1, 'stt' => 3, 'hienthi' => true],
            ['tenvi' => 'Laptop Gaming',        'id_list' => $list2, 'stt' => 1, 'hienthi' => true],
            ['tenvi' => 'MacBook',              'id_list' => $list2, 'stt' => 2, 'hienthi' => true],
            ['tenvi' => 'Chuột & Bàn phím',     'id_list' => $list2, 'stt' => 3, 'hienthi' => false],
        ];

        foreach ($cats as $cat) {
            ProductCat::create([
                'id_list' => $cat['id_list'],

                'tenvi' => $cat['tenvi'],
                'tenen' => null,

                'tenkhongdauvi' => Str::slug($cat['tenvi']),
                'tenkhongdauen' => null,

                'stt' => $cat['stt'],
                'hienthi' => $cat['hienthi'],
                'noibat' => false,
            ]);
        }
    }
}
