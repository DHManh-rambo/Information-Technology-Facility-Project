<?php

use Illuminate\Support\Facades\DB;

Route::get('/khachhang', function () {
    $data = DB::table('KhachHang')->get();

    return response()->json($data);
});