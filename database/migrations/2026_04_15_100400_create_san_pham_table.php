<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('san_pham', function (Blueprint $table) {
            $table->id('ma_san_pham');
            $table->string('ten_san_pham');
            $table->decimal('gia', 10, 2);
            $table->integer('so_luong');

            $table->enum('loai_san_pham', [
                'HOA_TUOI','HOA_GIA','SAN_PHAM_PREMIUM',
                'CHAU_HOA_GIA','CHAU_HOA_TUOI',
                'CAY_CANH','HOA_SAP','HOA_GIAY_NHUN',
                'TERRARIUM','PHU_KIEN','QUA_TANG'
            ]);

            $table->text('mo_ta')->nullable();
            $table->string('hinh_anh')->nullable();

            $table->enum('trang_thai', ['DANG_BAN','NGUNG_BAN'])
                  ->default('DANG_BAN'); 

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('san_pham');
    }
};