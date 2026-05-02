<?php

use App\Http\Controllers\NguoiDungController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KhachHangController;
use App\Http\Controllers\NhanVienController;
use App\Http\Controllers\SanPhamController;
use App\Http\Controllers\HoaDonController;
use App\Http\Controllers\DonHangController;
use App\Http\Controllers\PhieuNhapController;
Route::get('/', function () {
    return view('welcome');
});

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
Route::prefix('san-pham')->name('san-pham.')->controller(SanPhamController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/', 'store')->name('store');
    Route::get('/{id}/edit-data', 'editData')->name('edit-data');
    Route::put('/{id}', 'update')->name('update');
    Route::patch('/{id}/toggle', 'toggleTrangThai')->name('toggle');
});

Route::prefix('hoa-don')->name('hoa-don.')->controller(HoaDonController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/{id}', 'show')->name('show');
    Route::delete('/{id}', 'destroy')->name('destroy');
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
