<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bo_hoa', function (Blueprint $table) {

            $table->id('ma_bo_hoa');

            // NULL = bó hoa do hệ thống tạo
            // Có giá trị = bó hoa do khách hàng tự tạo
            $table->integer('ma_khach_hang')->nullable();

            $table->string('ten_bo_hoa', 100);

            $table->enum('loai_bo_hoa', [
                'HE_THONG',
                'TUY_CHON'
            ]);

            $table->enum('kieu_bo_hoa', [
                'ROUND',
                'NATURAL',
                'CONE',
                'CASCADING',
                'ASYMMETRIC',
                'COMPACT',
                'LONG_STEM'
            ]);

            $table->enum('size', [
                'S',
                'M',
                'L',
                'XL',
                'XXL',
                'SPECIAL'
            ]);

            $table->string('concept', 100)->nullable();

            $table->text('loi_nhan')->nullable();

            $table->enum('trang_thai', [
                'DRAFT',
                'ACTIVE',
                'INACTIVE'
            ])->default('DRAFT');

            $table->foreign('ma_khach_hang')
                ->references('ma_khach_hang')
                ->on('khach_hang')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bo_hoa');
    }
};