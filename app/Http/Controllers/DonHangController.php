<?php

namespace App\Http\Controllers;

use App\Models\HoaDon;
use App\Models\NhanVien;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DonHangController extends Controller
{
    
    public function index(Request $request)
    {
        $query = HoaDon::with(['khachHang', 'chiTietHoaDon.sanPham'])
            ->where('trang_thai', 'PENDING');

        
        if ($request->filled('ma_khach_hang')) {
            $query->where('ma_khach_hang', $request->ma_khach_hang);
        }

       
        if ($request->filled('trang_thai_thanh_toan')) {
            $query->where('trang_thai_thanh_toan', $request->trang_thai_thanh_toan);
        }

        
        if ($request->filled('tu_tien')) {
            $query->where('tong_tien', '>=', $request->tu_tien);
        }
        if ($request->filled('den_tien')) {
            $query->where('tong_tien', '<=', $request->den_tien);
        }

        
        if ($request->filled('tu_ngay')) {
            $query->where('ngay_dat', '>=', Carbon::parse($request->tu_ngay)->startOfDay());
        }
        if ($request->filled('den_ngay')) {
            $query->where('ngay_dat', '<=', Carbon::parse($request->den_ngay)->endOfDay());
        }

        $donHangs = $query->orderByDesc('ngay_dat')->paginate(15)->withQueryString();

        
        $shippers = NhanVien::whereHas('nguoiDung', function ($q) {
            $q->where('vai_tro', 'SHIPPER');
        })->orWhere('chuc_vu', 'SHIPPER')->get();

        return view('DonHang', compact('donHangs', 'shippers'));
    }

   
    public function confirm(Request $request, $id)
    {
        $request->validate([
            'ma_nhan_vien_giao' => 'required|exists:nhan_vien,ma_nhan_vien',
        ], [
            'ma_nhan_vien_giao.required' => 'Vui lòng chọn shipper trước khi xác nhận.',
            'ma_nhan_vien_giao.exists'   => 'Shipper không hợp lệ.',
        ]);

        $hoaDon = HoaDon::findOrFail($id);

        if ($hoaDon->trang_thai !== 'PENDING') {
            return response()->json([
                'success' => false,
                'message' => 'Chỉ có thể xác nhận đơn hàng đang ở trạng thái PENDING.',
            ], 422);
        }

        $hoaDon->update([
            'trang_thai'         => 'CONFIRMED',
            'ma_nhan_vien_giao'  => $request->ma_nhan_vien_giao,
           
        ]);

        return response()->json([
            'success' => true,
            'message' => "Đã xác nhận đơn hàng #HD-" . str_pad($id, 4, '0', STR_PAD_LEFT) . " thành công.",
        ]);
    }

  
    public function cancel($id)
    {
        $hoaDon = HoaDon::findOrFail($id);

        if ($hoaDon->trang_thai !== 'PENDING') {
            return response()->json([
                'success' => false,
                'message' => 'Chỉ có thể từ chối đơn hàng đang ở trạng thái PENDING.',
            ], 422);
        }

        $hoaDon->update([
            'trang_thai' => 'CANCELLED',
        ]);

        return response()->json([
            'success' => true,
            'message' => "Đã từ chối đơn hàng #HD-" . str_pad($id, 4, '0', STR_PAD_LEFT) . ".",
        ]);
    }
}