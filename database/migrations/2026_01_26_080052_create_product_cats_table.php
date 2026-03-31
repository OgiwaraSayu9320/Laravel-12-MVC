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
        Schema::create('product_cats', function (Blueprint $table) {
            $table->id();

            // Liên kết Cấp 1
            $table->integer('id_list')->nullable()->index();

            // Đa ngôn ngữ
            $table->string('tenvi');
            $table->string('tenen')->nullable();
            $table->string('tenkhongdauvi')->nullable();
            $table->string('tenkhongdauen')->nullable();

            $table->string('photo')->nullable();

            // SEO
            $table->string('titlevi')->nullable();
            $table->string('keywordsvi')->nullable();
            $table->text('descriptionvi')->nullable();
            $table->string('titleen')->nullable();
            $table->string('keywordsen')->nullable();
            $table->text('descriptionen')->nullable();

            $table->integer('stt')->default(0);
            $table->boolean('hienthi')->default(true);
            $table->boolean('noibat')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_cats');
    }
};
