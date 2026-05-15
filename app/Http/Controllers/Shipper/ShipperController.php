<?php

namespace App\Http\Controllers\Shipper;

use App\Models\HoaDon;
use App\Models\NhanVien;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShipperController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $maNhanVien = $user->ma_nguoi_dung;

        $shipper = NhanVien::findOrFail($maNhanVien);

        $tienCanTra = HoaDon::where('ma_nhan_vien_giao', $maNhanVien)
            ->where('trang_thai', 'DELIVERED')
            ->where('trang_thai_thanh_toan', 'DA_THANH_TOAN')
            ->where('phuong_thuc_thanh_toan', 'COD')
            ->sum('tong_tien');

        $soDonConNo = HoaDon::where('ma_nhan_vien_giao', $maNhanVien)
            ->where('trang_thai', 'DELIVERED')
            ->where('trang_thai_thanh_toan', 'DA_THANH_TOAN')
            ->where('phuong_thuc_thanh_toan', 'COD')
            ->count();

        $donHangCanShip = HoaDon::with('khachHang')
            ->where('ma_nhan_vien_giao', $maNhanVien)
            ->whereIn('trang_thai', ['CONFIRMED', 'SHIPPING'])
            ->orderByDesc('ngay_dat')
            ->get();

        $lichSuDonHang = HoaDon::with('khachHang')
            ->where('ma_nhan_vien_giao', $maNhanVien)
            ->where('trang_thai', 'DELIVERED')
            ->orderByDesc('ngay_giao')
            ->paginate(10);

        $tongDoanhThu = HoaDon::where('ma_nhan_vien_giao', $maNhanVien)
            ->where('trang_thai', 'DELIVERED')
            ->sum('tong_tien');

        return view('Shipper.ShipperDashboard', compact(
            'shipper',
            'tienCanTra',
            'soDonConNo',
            'donHangCanShip',
            'lichSuDonHang',
            'tongDoanhThu'
        ));
    }

    public function updateStatus(Request $request, $id)
    {
        $user = Auth::user();
        $maNhanVien = $user->ma_nguoi_dung;

        $hoaDon = HoaDon::findOrFail($id);

        if ((int) $hoaDon->ma_nhan_vien_giao !== (int) $maNhanVien) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền cập nhật đơn hàng này.',
            ], 403);
        }

        $map = [
            'CONFIRMED' => 'SHIPPING',
            'SHIPPING'  => 'DELIVERED',
        ];

        if (!isset($map[$hoaDon->trang_thai])) {
            return response()->json([
                'success' => false,
                'message' => 'Trạng thái hiện tại không thể cập nhật.',
            ], 422);
        }

        $newStatus = $map[$hoaDon->trang_thai];
        $updateData = ['trang_thai' => $newStatus];

        if ($newStatus === 'DELIVERED') {
            $updateData['ngay_giao'] = now();
            $updateData['trang_thai_thanh_toan'] = 'DA_THANH_TOAN';
        }

        $hoaDon->update($updateData);

        return response()->json([
            'success'    => true,
            'new_status' => $newStatus,
            'message'    => $newStatus === 'SHIPPING'
                ? 'Đã bắt đầu giao đơn #HD-' . str_pad($id, 4, '0', STR_PAD_LEFT) . '.'
                : 'Đã hoàn thành đơn #HD-' . str_pad($id, 4, '0', STR_PAD_LEFT) . '!',
        ]);
    }
}