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
use App\Http\Controllers\Shipper\NhanDonController;
use App\Http\Controllers\Shipper\ShipperProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Customer\CustomerProfileController;
use App\Http\Controllers\Customer\ChiTietSanPhamController;
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
    // Guest vào thẳng dashboard customer (không cần đăng nhập)
    return redirect()->route('customer.dashboard');
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
        Route::get('/loi-nhuan',    [BaoCaoController::class, 'index'])->name('loi-nhuan');
        Route::get('/san-pham',     [BaoCaoController::class, 'baoCaoSanPham'])->name('san-pham');
        Route::get('/ton-kho',      [BaoCaoController::class, 'index'])->name('ton-kho');
        Route::get('/khach-hang',   [BaoCaoController::class, 'index'])->name('khach-hang');
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


Route::prefix('don-hang')->name('don-hang.')->controller(\App\Http\Controllers\DonHangController::class)->group(function () {
    
    Route::get('/', 'index')->name('index');
  
    Route::post('/{id}/confirm', 'confirm')->name('confirm');
   
    Route::post('/{id}/cancel', 'cancel')->name('cancel');
});
Route::prefix('phieu-nhap')->name('phieu-nhap.')->controller(PhieuNhapController::class)->group(function () {
 
    Route::get('/', 'index')->name('index');
 
    Route::post('/', 'store')->name('store');
 
    Route::get('/{id}/edit-data', 'editData')->name('edit-data');
 
    Route::put('/{id}', 'update')->name('update');
 
    Route::delete('/{id}', 'destroy')->name('destroy');
 
    Route::post('/{id}/confirm', 'confirm')->name('confirm');
});
Route::get('/bao-cao',               [BaoCaoController::class, 'index'])->name('bao-cao.index');
Route::get('/bao-cao/doanh-thu',     [BaoCaoController::class, 'doanhThu'])->name('bao-cao.doanh-thu');
Route::get('/bao-cao/loi-nhuan',     [BaoCaoController::class, 'index'])->name('bao-cao.loi-nhuan');
Route::get('/bao-cao/san-pham',              [BaoCaoController::class, 'baoCaoSanPham'])->name('bao-cao.san-pham');
Route::get('/bao-cao/san-pham/export',         [BaoCaoController::class, 'exportBaoCaoSanPham'])->name('bao-cao.san-pham.export');
Route::get('/bao-cao/san-pham/{id}/hang-hong', [BaoCaoController::class, 'chiTietHangHong'])->name('bao-cao.san-pham.hang-hong');
Route::get('/bao-cao/san-pham/{id}/chi-tiet',  [BaoCaoController::class, 'chiTietSanPham'])->name('bao-cao.san-pham.chi-tiet');
Route::get('/bao-cao/ton-kho',       [BaoCaoController::class, 'index'])->name('bao-cao.ton-kho');
Route::get('/bao-cao/khach-hang',    [BaoCaoController::class, 'index'])->name('bao-cao.khach-hang');
Route::post('/bao-cao/hang-hong',    [BaoCaoController::class, 'baoHangHong'])->name('bao-cao.bao-hang-hong');



});
Route::middleware(['auth', 'role:SHIPPER'])->group(function () {
    Route::get('/shipper/dashboard', [ShipperController::class, 'dashboard'])->name('shipper.dashboard');
    Route::patch('/shipper/don-hang/{id}/cap-nhat', [ShipperController::class, 'updateStatus'])->name('shipper.update-status');
    Route::get('/shipper/don-hang/{id}/chi-tiet', [NhanDonController::class, 'show'])->name('shipper.don-hang.chi-tiet');
    Route::get('/shipper/profile',[ShipperProfileController::class, 'edit'])->name('shipper.profile.edit');
    Route::patch('/shipper/profile',[ShipperProfileController::class, 'update'])->name('shipper.profile.update');
    Route::patch('/shipper/profile/password', [ShipperProfileController::class, 'updatePassword']) ->name('shipper.profile.password');
});



//Khach hàng
Route::get('/customer/dashboard', [CustomerController::class, 'dashboard'])
    ->name('customer.dashboard');
Route::get('/customer/san-pham/{id}', [ChiTietSanPhamController::class, 'show'])
    ->name('customer.san-pham.chi-tiet');

// Các chức năng cần đăng nhập mới dùng được
Route::middleware(['auth', 'role:KHACH_HANG'])->group(function () {
    Route::get('/customer/profile',            [CustomerProfileController::class, 'edit'])
        ->name('customer.profile.edit');
    Route::patch('/customer/profile',          [CustomerProfileController::class, 'update'])
        ->name('customer.profile.update');
    Route::patch('/customer/profile/password', [CustomerProfileController::class, 'updatePassword'])
        ->name('customer.profile.password');
});