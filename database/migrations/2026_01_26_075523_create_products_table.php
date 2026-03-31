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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Liên kết Danh mục (Lưu ID integer như file SQL, không ràng buộc cứng)
            $table->integer('id_list')->nullable()->index();
            $table->integer('id_cat')->nullable()->index();
            $table->integer('id_brand')->nullable()->index();

            // Thông tin cơ bản
            $table->string('masp')->nullable(); // Mã sản phẩm
            $table->string('photo')->nullable();
            $table->json('gallery')->nullable(); // Thư viện ảnh (Dùng JSON thay vì bảng table_gallery riêng cho gọn)

            // Đa ngôn ngữ: Tên
            $table->string('tenvi');
            $table->string('tenen')->nullable();
            $table->string('tenkhongdauvi')->nullable();
            $table->string('tenkhongdauen')->nullable();

            // Đa ngôn ngữ: Mô tả & Nội dung
            $table->text('motavi')->nullable();
            $table->text('motaen')->nullable();
            $table->longText('noidungvi')->nullable();
            $table->longText('noidungen')->nullable();

            // Giá bán
            $table->double('gia')->default(0); // Giá cũ
            $table->double('giamoi')->default(0); // Giá mới (giakm)
            $table->tinyInteger('giakm')->default(0); // Phần trăm giảm giá (nếu cần)

            // SEO
            $table->string('titlevi')->nullable();
            $table->string('keywordsvi')->nullable();
            $table->text('descriptionvi')->nullable();
            $table->string('titleen')->nullable();
            $table->string('keywordsen')->nullable();
            $table->text('descriptionen')->nullable();

            // Trạng thái
            $table->integer('stt')->default(0);
            $table->boolean('hienthi')->default(true);
            $table->boolean('noibat')->default(false); // Nổi bật
            $table->boolean('banchay')->default(false); // Bán chạy
            $table->integer('luotxem')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
