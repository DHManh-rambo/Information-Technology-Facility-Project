<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    
    public function dashboard()
    {
        
        $user = Auth::user()->load('khachHang');

        return view('customer.dashboard', compact('user'));
    }
}