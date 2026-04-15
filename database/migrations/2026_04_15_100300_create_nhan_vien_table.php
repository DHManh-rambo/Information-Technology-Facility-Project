<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('nhan_vien', function (Blueprint $table) {
            $table->unsignedBigInteger('ma_nhan_vien')->primary();
            $table->string('ten_nhan_vien');
            $table->enum('chuc_vu', [
                'CSKH','VAN_HANH','THIET_KE','ONLINE','SHIPPER','KHAC'
            ]);
            $table->text('cong_viec')->nullable();
            $table->decimal('luong', 10, 2);

            $table->foreign('ma_nhan_vien')
                  ->references('ma_nguoi_dung')
                  ->on('nguoi_dung')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nhan_vien');
    }
};