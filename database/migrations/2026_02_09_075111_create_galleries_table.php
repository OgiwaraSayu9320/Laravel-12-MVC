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
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();

            // 1. Liên kết
            $table->string('photo'); // Đường dẫn ảnh
            $table->integer('id_parent')->default(0)->index(); // ID của Sản phẩm/Bài viết
            $table->string('type')->default('san-pham'); // Phân loại: 'san-pham', 'slider', 'doi-tac'...

            // 2. Thông tin ảnh (Đa ngôn ngữ)
            $table->string('tenvi')->nullable(); // Tên ảnh tiếng Việt
            $table->string('tenen')->nullable(); // Tên ảnh tiếng Anh

            // 3. Quản lý
            $table->integer('stt')->default(1); // Sắp xếp
            $table->boolean('hienthi')->default(true); // Trạng thái hiển thị

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};
