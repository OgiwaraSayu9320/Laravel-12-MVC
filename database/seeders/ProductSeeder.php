<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductList;
use App\Models\ProductCat;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $list1 = ProductList::where('tenvi', 'Điện thoại & Phụ kiện')->first()->id ?? 1;
        $list2 = ProductList::where('tenvi', 'Laptop & Máy tính')->first()->id ?? 2;

        $cat1 = ProductCat::where('tenvi', 'iPhone')->first()->id ?? 1;
        $cat2 = ProductCat::where('tenvi', 'Samsung Galaxy')->first()->id ?? 2;
        $cat3 = ProductCat::where('tenvi', 'Laptop Gaming')->first()->id ?? 4;


        $products = [
            ['tenvi' => 'iPhone 15 Pro Max 256GB', 'gia' => 34000000, 'id_list' => $list1, 'id_cat' => $cat1],
            ['tenvi' => 'iPhone 14 Pro 128GB', 'gia' => 25000000, 'id_list' => $list1, 'id_cat' => $cat1],
            ['tenvi' => 'Samsung Galaxy S24 Ultra', 'gia' => 32000000, 'id_list' => $list1, 'id_cat' => $cat2],
            ['tenvi' => 'Samsung Galaxy Z Fold 6', 'gia' => 45000000, 'id_list' => $list1, 'id_cat' => $cat2],
            ['tenvi' => 'Ốp lưng iPhone chống sốc', 'gia' => 450000, 'id_list' => $list1, 'id_cat' => $cat3],
            ['tenvi' => 'ASUS ROG Strix G16', 'gia' => 38000000, 'id_list' => $list2, 'id_cat' => $cat3],
            ['tenvi' => 'MacBook Pro M3 Max 14"', 'gia' => 65000000, 'id_list' => $list2, 'id_cat' => $cat3],
            ['tenvi' => 'Lenovo Legion 5 Pro', 'gia' => 32000000, 'id_list' => $list2, 'id_cat' => $cat3],
            ['tenvi' => 'iPhone 13 128GB (cũ)', 'gia' => 14000000, 'id_list' => $list1, 'id_cat' => $cat1],
            ['tenvi' => 'Tai nghe Sony WH-1000XM5', 'gia' => 8500000, 'id_list' => $list1, 'id_cat' => null],
            ['tenvi' => 'Chuột Logitech G Pro X', 'gia' => 1800000, 'id_list' => $list2, 'id_cat' => null],
            ['tenvi' => 'Bàn phím cơ Keychron K8', 'gia' => 2200000, 'id_list' => $list2, 'id_cat' => null],
            ['tenvi' => 'Galaxy Buds 3 Pro', 'gia' => 5200000, 'id_list' => $list1, 'id_cat' => $cat2],
            ['tenvi' => 'Dell XPS 13 Plus', 'gia' => 42000000, 'id_list' => $list2, 'id_cat' => null],
            ['tenvi' => 'Ốp lưng Samsung chống ố vàng', 'gia' => 380000, 'id_list' => $list1, 'id_cat' => $cat2],
            ['tenvi' => 'MacBook Air M2 13"', 'gia' => 28000000, 'id_list' => $list2, 'id_cat' => null],
            ['tenvi' => 'Tai nghe AirPods Pro 2', 'gia' => 6200000, 'id_list' => $list1, 'id_cat' => null],
            ['tenvi' => 'Máy tính bảng iPad Air 5', 'gia' => 18000000, 'id_list' => $list1, 'id_cat' => null],
        ];

        foreach ($products as $product) {
            Product::create([
                'tenvi' => $product['tenvi'],
                'gia' => $product['gia'],
                'giamoi' => 0,
                'giakm' => 0,
                'id_list' => $product['id_list'],
                'id_cat' => $product['id_cat'],
                'hienthi' => true,
            ]);
        }
    }
}
