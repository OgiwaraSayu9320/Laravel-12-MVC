<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('seo_pages', function (Blueprint $table) {
            $table->id();

            // Định danh trang (Ví dụ: 'home', 'about', 'contact', 'setting')
            $table->string('slug')->unique();
            $table->string('name'); // Tên hiển thị (Ví dụ: Cấu hình Trang Chủ)

            // 3 chỉ số SEO thần thánh
            $table->string('title_seo')->nullable();
            $table->text('desc_seo')->nullable();
            $table->string('keyword_seo')->nullable();

            // Hình ảnh đại diện khi share Facebook/Zalo (og:image)
            $table->string('image_seo')->nullable();

            // Nếu bạn muốn làm cấu hình chung (Logo, Hotline) thì thêm cột value dạng json
            // $table->json('meta_data')->nullable(); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seo_pages');
    }
};
