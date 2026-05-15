<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\SanPham;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user()->load('khachHang');

        
        $sanPhams = SanPham::where('trang_thai', 'DANG_BAN')
            ->orderBy('ma_san_pham', 'desc')
            ->get();

        return view('customer.Dashboard', compact('user', 'sanPhams'));
    }
}