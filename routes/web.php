<?php

use App\Http\Controllers\NguoiDungController;
use App\Http\Controllers\KhachHangController;
use App\Http\Controllers\NhanVienController;
use App\Http\Controllers\SanPhamController;
use App\Http\Controllers\HoaDonController;
use App\Http\Controllers\DonHangController;
use App\Http\Controllers\PhieuNhapController;
use App\Http\Controllers\BaoCaoController;
use App\Http\Controllers\Shipper\ShipperController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

// ─── Trang chủ ────────────────────────────────────────────────────────────────
Route::get('/', function () {
    if (auth()->check()) {
        $vai_tro = auth()->user()->vai_tro;
        return match($vai_tro) {
            'ADMIN', 'NHAN_VIEN' => redirect()->route('admin.dashboard'),
            'SHIPPER'            => redirect()->route('shipper.dashboard'),
            default              => redirect()->route('customer.dashboard'),
        };
    }
    return redirect()->route('login');
});

// ─── Breeze Auth routes ────────────────────────────────────────────────────────
require __DIR__.'/auth.php';

// ─── ADMIN + NHÂN VIÊN Dashboard ──────────────────────────────────────────────
Route::middleware(['auth', 'role:ADMIN,NHAN_VIEN'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
        ->name('admin.dashboard');
});

// ─── ADMIN ONLY ───────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:ADMIN'])->group(function () {

    // Quản lý Người dùng
    Route::prefix('nguoi-dung')->name('nguoi-dung.')->group(function () {
        Route::get('/',              [NguoiDungController::class, 'index'])->name('index');
        Route::post('/',             [NguoiDungController::class, 'store'])->name('store');
        Route::put('/{id}',          [NguoiDungController::class, 'update'])->name('update');
        Route::delete('/{id}',       [NguoiDungController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/edit-data',[NguoiDungController::class, 'editData'])->name('edit-data');
    });

    // Quản lý Nhân viên (bao gồm trả tiền - admin only)
    Route::prefix('nhan-vien')->name('nhan-vien.')->controller(NhanVienController::class)->group(function () {
        Route::get('/',                   'index')->name('index');
        Route::put('/{ma_nhan_vien}',     'update')->name('update');
        Route::delete('/{ma_nhan_vien}',  'destroy')->name('destroy');
        Route::post('/{ma}/tra-tien',     'payback')->name('payback');
    });

    // Phiếu nhập
    Route::prefix('phieu-nhap')->name('phieu-nhap.')->controller(PhieuNhapController::class)->group(function () {
        Route::get('/',              'index')->name('index');
        Route::post('/',             'store')->name('store');
        Route::get('/{id}/edit-data','editData')->name('edit-data');
        Route::put('/{id}',          'update')->name('update');
        Route::delete('/{id}',       'destroy')->name('destroy');
        Route::post('/{id}/confirm', 'confirm')->name('confirm');
    });

    // Báo cáo
    Route::prefix('bao-cao')->name('bao-cao.')->group(function () {
        Route::get('/',             [BaoCaoController::class, 'index'])->name('index');
        Route::get('/doanh-thu',    [BaoCaoController::class, 'doanhThu'])->name('doanh-thu');
        Route::get('/loi-nhuan',    [BaoCaoController::class, 'loiNhuan'])->name('loi-nhuan');
        Route::get('/san-pham',     [BaoCaoController::class, 'sanPhamBanChay'])->name('san-pham');
        Route::get('/ton-kho',      [BaoCaoController::class, 'tonKho'])->name('ton-kho');
        Route::get('/khach-hang',   [BaoCaoController::class, 'khachHang'])->name('khach-hang');
        Route::post('/hang-hong',   [BaoCaoController::class, 'baoHangHong'])->name('bao-hang-hong');
    });
});

// ─── ADMIN + NHÂN VIÊN ────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:ADMIN,NHAN_VIEN'])->group(function () {

    // Quản lý Khách hàng
    Route::prefix('khach-hang')->name('khach-hang.')->group(function () {
        Route::get('/',        [KhachHangController::class, 'index'])->name('index');
        Route::put('/{id}',    [KhachHangController::class, 'update'])->name('update');
        Route::delete('/{id}', [KhachHangController::class, 'destroy'])->name('destroy');
    });

    // Sản phẩm
    Route::prefix('san-pham')->name('san-pham.')->group(function () {
        Route::get('/',              [SanPhamController::class, 'index'])->name('index');
        Route::post('/',             [SanPhamController::class, 'store'])->name('store');
        Route::get('/{id}/edit-data',[SanPhamController::class, 'editData'])->name('editData');
        Route::put('/{id}',          [SanPhamController::class, 'update'])->name('update');
        Route::patch('/{id}/toggle', [SanPhamController::class, 'toggleTrangThai'])->name('toggle');
    });

    // Hóa đơn
    Route::prefix('hoa-don')->name('hoa-don.')->controller(HoaDonController::class)->group(function () {
        Route::get('/',      'index')->name('index');
        Route::get('/{id}',  'show')->name('show');
        Route::delete('/{id}','destroy')->name('destroy');
    });

    // Đơn hàng
    Route::prefix('don-hang')->name('don-hang.')->controller(DonHangController::class)->group(function () {
        Route::get('/',              'index')->name('index');
        Route::post('/{id}/confirm', 'confirm')->name('confirm');
        Route::post('/{id}/cancel',  'cancel')->name('cancel');
    });
});

// ─── SHIPPER ──────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:SHIPPER'])->group(function () {
    Route::get('/shipper/dashboard',
        [ShipperController::class, 'dashboard'])->name('shipper.dashboard');
    Route::patch('/shipper/don-hang/{id}/cap-nhat',
        [ShipperController::class, 'updateStatus'])->name('shipper.update-status');
});

// ─── KHÁCH HÀNG ───────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:KHACH_HANG'])->group(function () {
    Route::get('/customer/dashboard',
        [CustomerController::class, 'dashboard'])->name('customer.dashboard');
});
Route::middleware('guest')->group(function () {
    Route::get('/register',  [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});