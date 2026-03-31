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
        Schema::create('product_lists', function (Blueprint $table) {
            $table->id();

            // Đa ngôn ngữ (Theo file SQL)
            $table->string('tenvi');
            $table->string('tenen')->nullable();
            $table->string('tenkhongdauvi')->nullable(); // Slug VI
            $table->string('tenkhongdauen')->nullable(); // Slug EN

            // Hình ảnh
            $table->string('photo')->nullable();

            // SEO (Theo file SQL)
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
            $table->string('type')->default('san-pham'); // Để phân loại nếu dùng chung bảng

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_lists');
    }
};
