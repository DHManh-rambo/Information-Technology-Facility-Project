<?php

use Illuminate\Support\Facades\DB;

Route::get('/khachhang', function () {
    $data = DB::table('khach_hang')->get();

    return response()->json($data);
});