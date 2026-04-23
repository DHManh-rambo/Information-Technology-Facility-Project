<?php

use App\Http\Controllers\NguoiDungController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/nguoi-dung', [NguoiDungController::class, 'index'])->name('nguoi-dung.index');
Route::post('/nguoi-dung', [NguoiDungController::class, 'store'])->name('nguoi-dung.store');
Route::put('/nguoi-dung/{id}', [NguoiDungController::class, 'update'])->name('nguoi-dung.update');
Route::delete('/nguoi-dung/{id}', [NguoiDungController::class, 'destroy'])->name('nguoi-dung.destroy');
Route::get('/nguoi-dung/{id}/edit-data', [NguoiDungController::class, 'editData'])->name('nguoi-dung.edit-data');