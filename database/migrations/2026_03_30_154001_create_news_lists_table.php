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
        Schema::create('news_lists', function (Blueprint $table) {
            $table->id();

            // Đa ngôn ngữ (Theo file SQL / Product pattern)
            $table->string('tenvi');
            $table->string('tenen')->nullable();
            $table->string('tenkhongdauvi')->nullable()->unique(); // Slug VI dùng cho URL
            $table->string('tenkhongdauen')->nullable()->unique(); // Slug EN dùng cho URL

            // Hình ảnh
            $table->string('photo')->nullable();

            // SEO
            $table->string('titlevi')->nullable();
            $table->string('keywordsvi')->nullable();
            $table->text('descriptionvi')->nullable();
            $table->string('titleen')->nullable();
            $table->string('keywordsen')->nullable();
            $table->text('descriptionen')->nullable();

            // Cấu hình
            $table->integer('stt')->default(0); // Sắp xếp
            $table->boolean('hienthi')->default(true); // Hiển thị
            $table->boolean('noibat')->default(false); // Nổi bật
            $table->string('type')->default('tin-tuc'); // Phân loại (để phân biệt với san-pham nếu cần)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_lists');
    }
};
