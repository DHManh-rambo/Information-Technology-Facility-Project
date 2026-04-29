<?php

namespace App\Http\Controllers;

use App\Models\HoaDon;
use App\Models\KhachHang;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HoaDonController extends Controller
{
    
    public function index(Request $request)
    {
        $query = HoaDon::with([
            'khachHang',
            'nhanVienGiao',
            'chiTietHoaDons.sanPham',
        ]);

        
        if ($request->filled('ma_khach_hang')) {
            $query->where('ma_khach_hang', $request->ma_khach_hang);
        }

       
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

       
        if ($request->filled('trang_thai_thanh_toan')) {
            $query->where('trang_thai_thanh_toan', $request->trang_thai_thanh_toan);
        }

       
        if ($request->filled('ngay_dat')) {
            $ngay = Carbon::parse($request->ngay_dat)->toDateString();
            $query->whereDate('ngay_dat', $ngay);
        }

      
        if ($request->filled('tu_ngay') && $request->filled('den_ngay')) {
            $tuNgay  = Carbon::parse($request->tu_ngay)->startOfDay();
            $denNgay = Carbon::parse($request->den_ngay)->endOfDay();
            $query->whereBetween('ngay_dat', [$tuNgay, $denNgay]);
        } elseif ($request->filled('tu_ngay')) {
            $query->where('ngay_dat', '>=', Carbon::parse($request->tu_ngay)->startOfDay());
        } elseif ($request->filled('den_ngay')) {
            $query->where('ngay_dat', '<=', Carbon::parse($request->den_ngay)->endOfDay());
        }

        $hoaDons   = $query->orderByDesc('ngay_dat')->paginate(15)->withQueryString();
        $khachHangs = KhachHang::orderBy('ten_khach_hang')->get();

        return view('HoaDon', compact('hoaDons', 'khachHangs'));
    }

   
    public function show($id)
    {
        $hoaDon = HoaDon::with([
            'khachHang',
            'nhanVienGiao',
            'chiTietHoaDons.sanPham',
        ])->findOrFail($id);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'data'    => $hoaDon,
            ]);
        }

        return view('hoa-don.show', compact('hoaDon'));
    }

    public function destroy($id)
    {
        $hoaDon = HoaDon::findOrFail($id);

        $coTheXoa = $hoaDon->trang_thai_thanh_toan === 'CHUA_THANH_TOAN'
                 || $hoaDon->trang_thai === 'CANCELLED';

        if (! $coTheXoa) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa hóa đơn đã thanh toán hoặc đang xử lý.',
            ], 403);
        }

        $hoaDon->delete(); 

        return response()->json([
            'success' => true,
            'message' => "Đã xóa hóa đơn #{$id} thành công.",
        ]);
    }
}