<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('brands', function (Blueprint $column) {
            $column->id();
            $column->string('photo')->nullable();

            // Đa ngôn ngữ (Tiếng Việt & Tiếng Anh)
            $column->string('tenvi')->nullable();
            $column->string('tenen')->nullable();

            // Link không dấu (Slug)
            $column->string('tenkhongdauvi')->unique()->nullable();
            $column->string('tenkhongdauen')->unique()->nullable();

            // SEO Fields
            $column->string('titlevi')->nullable();
            $column->string('titleen')->nullable();
            $column->string('keywordsvi')->nullable();
            $column->string('keywordsen')->nullable();
            $column->text('descriptionvi')->nullable();
            $column->text('descriptionen')->nullable();

            // Trạng thái
            $column->integer('stt')->default(0);
            $column->boolean('hienthi')->default(true);
            $column->boolean('noibat')->default(false);

            $column->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
