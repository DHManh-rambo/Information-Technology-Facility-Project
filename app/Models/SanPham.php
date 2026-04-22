<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SanPham extends Model
{
    use HasFactory;

    protected $table = 'san_pham';
    protected $primaryKey = 'ma_san_pham';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'ten_san_pham',
        'gia',
        'so_luong',
        'loai_san_pham',
        'mo_ta',
    ];

    protected $casts = [
        'gia' => 'decimal:2',
        'so_luong' => 'integer',
    ];

    
    public function chiTietHoaDons()
    {
        return $this->hasMany(ChiTietHoaDon::class, 'ma_san_pham', 'ma_san_pham');
    }


    public function chiTietNhaps()
    {
        return $this->hasMany(ChiTietNhap::class, 'ma_san_pham', 'ma_san_pham');
    }
}