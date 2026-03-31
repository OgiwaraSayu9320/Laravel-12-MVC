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
        Schema::create('news', function (Blueprint $table) {
            $table->id();

            // Liên kết Danh mục (Lưu ID integer để tương thích logic và linh hoạt kiểu dữ liệu)
            $table->integer('id_list')->nullable()->index();
            $table->integer('id_cat')->nullable()->index();

            // Thông tin cơ bản
            $table->string('photo')->nullable(); // Hình ảnh đại diện bài viết

            // Đa ngôn ngữ: Tên
            $table->string('tenvi');
            $table->string('tenen')->nullable();
            $table->string('tenkhongdauvi')->nullable()->unique(); // Slug VI cho URL
            $table->string('tenkhongdauen')->nullable()->unique(); // Slug EN cho URL

            // Đa ngôn ngữ: Mô tả & Nội dung (Khác biệt: Không có giá bán hay mã sản phẩm)
            $table->text('motavi')->nullable();
            $table->text('motaen')->nullable();
            $table->longText('noidungvi')->nullable();
            $table->longText('noidungen')->nullable();

            // SEO
            $table->string('titlevi')->nullable();
            $table->string('keywordsvi')->nullable();
            $table->text('descriptionvi')->nullable();
            $table->string('titleen')->nullable();
            $table->string('keywordsen')->nullable();
            $table->text('descriptionen')->nullable();

            // Trạng thái (Tương tự Product để đồng bộ quản lý)
            $table->integer('stt')->default(0);
            $table->boolean('hienthi')->default(true);
            $table->boolean('noibat')->default(false);
            $table->integer('luotxem')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
