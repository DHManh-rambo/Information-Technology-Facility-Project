<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chi_tiet_bo_hoa', function (Blueprint $table) {

            $table->id('ma_chi_tiet');

            $table->unsignedBigInteger('ma_bo_hoa');

            $table->integer('ma_san_pham');

            $table->enum('vai_tro', [
                'HOA_CHINH',
                'HOA_TRANG_TRI',
                'GIAY_GOI',
                'RUY_BANG',
                'PHU_KIEN_TRANG_TRI',
                'THIEP'
            ]);

            $table->integer('so_luong');

            $table->foreign('ma_bo_hoa')
                ->references('ma_bo_hoa')
                ->on('bo_hoa')
                ->onDelete('cascade');

            $table->foreign('ma_san_pham')
                ->references('ma_san_pham')
                ->on('san_pham');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chi_tiet_bo_hoa');
    }
};