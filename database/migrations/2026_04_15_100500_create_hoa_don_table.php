<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hoa_don', function (Blueprint $table) {
            $table->id('ma_hoa_don');

            $table->unsignedBigInteger('ma_khach_hang');

            $table->dateTime('ngay_dat')->useCurrent();

            $table->decimal('tong_tien', 10, 2)->default(0);

            $table->enum('trang_thai', [
               'PENDING','CONFIRMED','PROCESSING',
               'OUT_FOR_DELIVERY','DELIVERED','CANCELLED'
            ])->default('PENDING');

            $table->enum('trang_thai_thanh_toan', [
                'CHUA_THANH_TOAN','DA_THANH_TOAN','CHO'
            ])->default('CHUA_THANH_TOAN');

            $table->enum('phuong_thuc_thanh_toan', ['NGAN_HANG','COD']);

            $table->text('dia_chi_giao');
            $table->string('so_dien_thoai', 15);

            $table->dateTime('ngay_giao')->nullable();

            $table->unsignedBigInteger('ma_nhan_vien_giao')->nullable();
            $table->foreign('ma_khach_hang')
                  ->references('ma_khach_hang')
                  ->on('khach_hang');

            $table->foreign('ma_nhan_vien_giao')
                  ->references('ma_nhan_vien')
                  ->on('nhan_vien')
                  ->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hoa_don');
    }
};