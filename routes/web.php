<?php

use App\Http\Controllers\NguoiDungController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KhachHangController;
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
