<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('chi_tiet_hoa_don', function (Blueprint $table) {
            $table->id('ma_chi_tiet');

            $table->unsignedBigInteger('ma_hoa_don');
            $table->unsignedBigInteger('ma_san_pham');

            $table->integer('so_luong');
            $table->decimal('gia', 10, 2);

            $table->foreign('ma_hoa_don')
                  ->references('ma_hoa_don')
                  ->on('hoa_don')
                  ->onDelete('cascade');

            $table->foreign('ma_san_pham')
                  ->references('ma_san_pham')
                  ->on('san_pham');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chi_tiet_hoa_don');
    }
};