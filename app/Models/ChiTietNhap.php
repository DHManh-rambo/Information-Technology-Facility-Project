<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChiTietNhap extends Model
{
    use HasFactory;

    protected $table = 'chi_tiet_nhap';
    protected $primaryKey = 'ma_chi_tiet_nhap';
    public $incrementing = true;
    protected $keyType = 'int';

   
    public $timestamps = false;

    protected $fillable = [
        'ma_phieu_nhap',
        'ma_san_pham',
        'so_luong',
        'gia_nhap',
    ];

    protected $casts = [
        'so_luong' => 'integer',
        'gia_nhap' => 'decimal:2',
    ];

    
    public function phieuNhap()
    {
        return $this->belongsTo(PhieuNhap::class, 'ma_phieu_nhap', 'ma_phieu_nhap');
    }

   
    public function sanPham()
    {
        return $this->belongsTo(SanPham::class, 'ma_san_pham', 'ma_san_pham');
    }
}