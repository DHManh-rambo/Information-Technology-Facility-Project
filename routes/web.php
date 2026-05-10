<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NguoiDungController;
use App\Http\Controllers\KhachHangController;
use App\Http\Controllers\NhanVienController;
use App\Http\Controllers\SanPhamController;
use App\Http\Controllers\HoaDonController;
use App\Http\Controllers\DonHangController;
use App\Http\Controllers\PhieuNhapController;
use App\Http\Controllers\BaoCaoController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('user.home');
});

// Trang chủ public — ai cũng vào được, có/không đăng nhập
Route::get('/trang-chu', function () {
    return view('user.trangchu');
})->name('user.home');

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Yêu cầu đăng nhập)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Profile cá nhân
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // -------------------------------------------------------
    // STAFF — admin + nhân viên + shipper
    // -------------------------------------------------------
    Route::middleware('staff')->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::prefix('san-pham')->name('san-pham.')->controller(SanPhamController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}/edit-data', 'editData')->name('edit-data');
            Route::put('/{id}', 'update')->name('update');
            Route::patch('/{id}/toggle', 'toggleTrangThai')->name('toggle');
        });

        Route::prefix('don-hang')->name('don-hang.')->controller(DonHangController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/{id}/confirm', 'confirm')->name('confirm');
            Route::post('/{id}/cancel', 'cancel')->name('cancel');
        });

        Route::prefix('hoa-don')->name('hoa-don.')->controller(HoaDonController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{id}', 'show')->name('show');
            Route::delete('/{id}', 'destroy')->name('destroy');
        });

        Route::prefix('phieu-nhap')->name('phieu-nhap.')->controller(PhieuNhapController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}/edit-data', 'editData')->name('edit-data');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
            Route::post('/{id}/confirm', 'confirm')->name('confirm');
        });
    });

    // -------------------------------------------------------
    // ADMIN ONLY — yêu cầu role = 'admin'
    // -------------------------------------------------------
    Route::middleware('admin')->group(function () {

        Route::prefix('nguoi-dung')->name('nguoi-dung.')->group(function () {
            Route::get('/', [NguoiDungController::class, 'index'])->name('index');
            Route::post('/', [NguoiDungController::class, 'store'])->name('store');
            Route::put('/{id}', [NguoiDungController::class, 'update'])->name('update');
            Route::delete('/{id}', [NguoiDungController::class, 'destroy'])->name('destroy');
            Route::get('/{id}/edit-data', [NguoiDungController::class, 'editData'])->name('edit-data');
        });

        Route::prefix('khach-hang')->name('khach-hang.')->group(function () {
            Route::get('/', [KhachHangController::class, 'index'])->name('index');
            Route::put('/{id}', [KhachHangController::class, 'update'])->name('update');
            Route::delete('/{id}', [KhachHangController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('nhan-vien')->name('nhan-vien.')->controller(NhanVienController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::put('/{ma_nhan_vien}', 'update')->name('update');
            Route::delete('/{ma_nhan_vien}', 'destroy')->name('destroy');
        });

        Route::prefix('bao-cao')->name('bao-cao.')->controller(BaoCaoController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/doanh-thu', 'doanhThu')->name('doanh-thu');
            Route::get('/loi-nhuan', 'loiNhuan')->name('loi-nhuan');
            Route::get('/san-pham', 'sanPhamBanChay')->name('san-pham');
            Route::get('/ton-kho', 'tonKho')->name('ton-kho');
            Route::get('/khach-hang', 'khachHang')->name('khach-hang');
        });
    });
});

require __DIR__.'/auth.php';