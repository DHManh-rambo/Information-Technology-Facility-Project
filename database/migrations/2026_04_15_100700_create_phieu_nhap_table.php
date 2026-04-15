<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('phieu_nhap', function (Blueprint $table) {
            $table->id('ma_phieu_nhap');

            $table->dateTime('ngay_nhap');
            $table->unsignedBigInteger('ma_nhan_vien');

            $table->foreign('ma_nhan_vien')
                  ->references('ma_nhan_vien')
                  ->on('nhan_vien');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phieu_nhap');
    }
};