<?php

namespace App\Http\Controllers;

use App\Models\HoaDon;
use App\Models\SanPham;
use App\Models\KhachHang;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        $doanhThuHomNay = HoaDon::whereDate('ngay_dat', $today)
            ->where(function ($q) {
                $q->where('trang_thai', 'DELIVERED')
                  ->orWhere('trang_thai_thanh_toan', 'DA_THANH_TOAN');
            })->sum('tong_tien');

        $doanhThuHomQua = HoaDon::whereDate('ngay_dat', $yesterday)
            ->where(function ($q) {
                $q->where('trang_thai', 'DELIVERED')
                  ->orWhere('trang_thai_thanh_toan', 'DA_THANH_TOAN');
            })->sum('tong_tien');

        if ($doanhThuHomQua > 0) {
            $phanTramDoanhThu = round(($doanhThuHomNay - $doanhThuHomQua) / $doanhThuHomQua * 100, 1);
        } else {
            $phanTramDoanhThu = $doanhThuHomNay > 0 ? 100 : 0;
        }

        $donHangHomNay = HoaDon::whereDate('ngay_dat', $today)->count();
        $donHangChoXuLy = HoaDon::where('trang_thai', 'PENDING')->count();

        $sanPhamSapHetSo = SanPham::where('so_luong', '<', 5)
            ->where('trang_thai', 'DANG_BAN')
            ->count();

        $tongKhachHang = KhachHang::count();

        $doanhThu7Ngay = $this->layDoanhThu7Ngay($today);

        $trangThaiDonHang = HoaDon::selectRaw('trang_thai, COUNT(*) as so_luong')
            ->groupBy('trang_thai')
            ->pluck('so_luong', 'trang_thai')
            ->toArray();

        $topSanPham = DB::table('chi_tiet_hoa_don as ct')
            ->join('hoa_don as hd', 'ct.ma_hoa_don', '=', 'hd.ma_hoa_don')
            ->join('san_pham as sp', 'ct.ma_san_pham', '=', 'sp.ma_san_pham')
            ->where('hd.trang_thai', 'DELIVERED')
            ->select('sp.ten_san_pham', DB::raw('SUM(ct.so_luong) as tong_ban'), DB::raw('SUM(ct.so_luong * ct.gia_ban_snapshot) as tong_doanh_thu'))
            ->groupBy('sp.ma_san_pham', 'sp.ten_san_pham')
            ->orderByDesc('tong_ban')
            ->limit(5)
            ->get();

        return view('dashboard-admin', compact(
            'doanhThuHomNay', 'doanhThuHomQua', 'phanTramDoanhThu',
            'donHangHomNay', 'donHangChoXuLy',
            'sanPhamSapHetSo', 'tongKhachHang',
            'doanhThu7Ngay', 'trangThaiDonHang', 'topSanPham'
        ));
    }

    private function layDoanhThu7Ngay(Carbon $today): array
    {
        $from = $today->copy()->subDays(6);

        $rawData = HoaDon::whereBetween('ngay_dat', [$from->startOfDay(), $today->copy()->endOfDay()])
            ->where(function ($q) {
                $q->where('trang_thai', 'DELIVERED')
                  ->orWhere('trang_thai_thanh_toan', 'DA_THANH_TOAN');
            })
            ->selectRaw('DATE(ngay_dat) as ngay, SUM(tong_tien) as doanh_thu')
            ->groupBy('ngay')
            ->pluck('doanh_thu', 'ngay');

        $labels = [];
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $day = $today->copy()->subDays($i);
            $key = $day->format('Y-m-d');
            $labels[] = $day->format('d/m');
            $data[] = (float) ($rawData[$key] ?? 0);
        }

        return compact('labels', 'data');
    }
}
