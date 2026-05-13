<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Dashboard dùng chung cho Admin và Nhân Viên.
     * Blade sẽ tự kiểm tra role để ẩn/hiện chức năng.
     */
    public function dashboard()
    {
        $user = Auth::user();

        return view('AdminStaffDashboard', compact('user'));
    }
}