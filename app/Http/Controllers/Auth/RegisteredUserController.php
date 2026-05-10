<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\NguoiDung;
use App\Models\KhachHang;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'ten_dang_nhap' => ['required', 'string', 'max:50', 'unique:nguoi_dung,ten_dang_nhap'],
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'so_dien_thoai' => ['required', 'string', 'size:10', 'regex:/^\d{10}$/'],
            'password'      => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'ten_dang_nhap.required' => 'Vui lòng nhập tên đăng nhập.',
            'ten_dang_nhap.unique'   => 'Tên đăng nhập đã được sử dụng.',
            'so_dien_thoai.required' => 'Vui lòng nhập số điện thoại.',
            'so_dien_thoai.size'     => 'Số điện thoại phải đúng 10 chữ số.',
            'so_dien_thoai.regex'    => 'Số điện thoại chỉ được chứa chữ số.',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'user',
            ]);

            event(new Registered($user));

            $nguoiDung = NguoiDung::create([
                'ten_dang_nhap' => $request->ten_dang_nhap,
                'mat_khau'      => $request->password,
                'vai_tro'       => 'KHACH_HANG',
            ]);

            KhachHang::create([
                'ma_khach_hang'  => $nguoiDung->ma_nguoi_dung,
                'ten_khach_hang' => $request->name,
                'so_dien_thoai'  => $request->so_dien_thoai,
                'email'          => $request->email,
                'dia_chi'        => null,
                'diem_tich_luy'  => 0,
            ]);
        });

        return redirect()->route('login')
            ->with('status', 'Đăng ký thành công! Vui lòng đăng nhập.');
    }
}
