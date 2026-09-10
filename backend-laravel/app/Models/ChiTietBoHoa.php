<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChiTietBoHoa extends Model
{
    use HasFactory;

    protected $table = 'chi_tiet_bo_hoa';

    protected $primaryKey = 'ma_chi_tiet';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'ma_bo_hoa',
        'ma_san_pham',
        'vai_tro',
        'so_luong',
    ];

    public function boHoa()
    {
        return $this->belongsTo(
            BoHoa::class,
            'ma_bo_hoa',
            'ma_bo_hoa'
        );
    }

    public function sanPham()
    {
        return $this->belongsTo(
            SanPham::class,
            'ma_san_pham',
            'ma_san_pham'
        );
    }
}