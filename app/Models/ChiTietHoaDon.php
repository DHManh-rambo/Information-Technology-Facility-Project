<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChiTietHoaDon extends Model
{
    use HasFactory;

    protected $table = 'chi_tiet_hoa_don';
    protected $primaryKey = 'ma_chi_tiet';
    public $incrementing = true;
    protected $keyType = 'int';

    
    public $timestamps = false;

    protected $fillable = [
        'ma_hoa_don',
        'ma_san_pham',
        'so_luong',
        'gia',
    ];

    protected $casts = [
        'so_luong' => 'integer',
        'gia' => 'decimal:2',
    ];

    
    public function hoaDon()
    {
        return $this->belongsTo(HoaDon::class, 'ma_hoa_don', 'ma_hoa_don');
    }

    
    public function sanPham()
    {
        return $this->belongsTo(SanPham::class, 'ma_san_pham', 'ma_san_pham');
    }
    public function getThanhTienAttribute()
    {
        return $this->so_luong * $this->gia;
    }
}
