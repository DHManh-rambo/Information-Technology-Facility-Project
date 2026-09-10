<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoHoa extends Model
{
    use HasFactory;

    protected $table = 'bo_hoa';

    protected $primaryKey = 'ma_bo_hoa';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'ma_khach_hang',
        'ten_bo_hoa',
        'loai_bo_hoa',
        'kieu_bo_hoa',
        'size',
        'concept',
        'loi_nhan',
        'trang_thai',
    ];

    public function khachHang()
    {
        return $this->belongsTo(
            KhachHang::class,
            'ma_khach_hang',
            'ma_khach_hang'
        );
    }

    public function chiTietBoHoa()
    {
        return $this->hasMany(
            ChiTietBoHoa::class,
            'ma_bo_hoa',
            'ma_bo_hoa'
        );
    }
}