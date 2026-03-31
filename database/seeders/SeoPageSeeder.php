<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SeoPage;

class SeoPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            ['slug' => 'home', 'name' => 'Trang chủ'],
            ['slug' => 'about', 'name' => 'Giới thiệu'],
            ['slug' => 'contact', 'name' => 'Liên hệ'],
            ['slug' => 'products', 'name' => 'Sản phẩm'],
            ['slug' => 'news', 'name' => 'Tin tức'],
        ];

        foreach ($pages as $page) {
            SeoPage::updateOrCreate(
                ['slug' => $page['slug']],
                ['name' => $page['name']]
            );
        }
    }
}