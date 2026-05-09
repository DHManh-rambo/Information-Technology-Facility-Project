<?php

namespace App\Http\Controllers;

use App\Models\HoaDon;
use App\Models\SanPham;
use App\Models\KhachHang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BaoCaoController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'dashboard');

        $tongDoanhThu = HoaDon::where(function ($q) {
            $q->where('trang_thai', 'DELIVERED')
              ->orWhere('trang_thai_thanh_toan', 'DA_THANH_TOAN');
        })->sum('tong_tien');

        $tongLoiNhuan = DB::table('chi_tiet_hoa_don as ct')
            ->join('hoa_don as hd', 'ct.ma_hoa_don', '=', 'hd.ma_hoa_don')
            ->where('hd.trang_thai', 'DELIVERED')
            ->selectRaw('SUM((ct.gia_ban_snapshot - ct.gia_nhap_snapshot) * ct.so_luong) as loi_nhuan')
            ->value('loi_nhuan') ?? 0;

        $tongHoaDon = HoaDon::count();

        $tongSanPhamBan = DB::table('chi_tiet_hoa_don as ct')
            ->join('hoa_don as hd', 'ct.ma_hoa_don', '=', 'hd.ma_hoa_don')
            ->where('hd.trang_thai', 'DELIVERED')
            ->sum('ct.so_luong') ?? 0;

        $donHangHomNay = HoaDon::whereDate('ngay_dat', Carbon::today())->count();

        $doanhThuHomNay = HoaDon::whereDate('ngay_dat', Carbon::today())
            ->where('trang_thai', 'DELIVERED')
            ->sum('tong_tien');

        $sanPhamSapHetSo = SanPham::where('so_luong', '<', 5)
            ->where('trang_thai', 'DANG_BAN')
            ->count();

        $doanhThu7Ngay = $this->layDoanhThuTheoNgay(
            Carbon::today()->subDays(6),
            Carbon::today()
        );

        $trangThaiDonHang = HoaDon::selectRaw('trang_thai, COUNT(*) as so_luong')
            ->groupBy('trang_thai')
            ->pluck('so_luong', 'trang_thai')
            ->toArray();

        $topSanPhamDashboard = $this->layTopSanPham(5);

        $data = compact(
            'tab',
            'tongDoanhThu', 'tongLoiNhuan', 'tongHoaDon', 'tongSanPhamBan',
            'donHangHomNay', 'doanhThuHomNay', 'sanPhamSapHetSo',
            'doanhThu7Ngay', 'trangThaiDonHang', 'topSanPhamDashboard'
        );

        if ($tab === 'doanh-thu') {
            $data = array_merge($data, $this->dataDoanhThu($request));
        } elseif ($tab === 'loi-nhuan') {
            $data = array_merge($data, $this->dataLoiNhuan($request));
        } elseif ($tab === 'san-pham') {
            $data = array_merge($data, $this->dataSanPham($request));
        } elseif ($tab === 'ton-kho') {
            $data = array_merge($data, $this->dataTonKho($request));
        } elseif ($tab === 'khach-hang') {
            $data = array_merge($data, $this->dataKhachHang($request));
        }

        return view('BaoCao', $data);
    }

    private function dataDoanhThu(Request $request): array
    {
        $tuNgay = $request->filled('tu_ngay')
            ? Carbon::parse($request->tu_ngay)->startOfDay()
            : Carbon::now()->startOfMonth();
        $denNgay = $request->filled('den_ngay')
            ? Carbon::parse($request->den_ngay)->endOfDay()
            : Carbon::now()->endOfDay();

        $query = HoaDon::whereBetween('ngay_dat', [$tuNgay, $denNgay])
            ->where(function ($q) {
                $q->where('trang_thai', 'DELIVERED')
                  ->orWhere('trang_thai_thanh_toan', 'DA_THANH_TOAN');
            });

        $dtTongDoanhThu = (clone $query)->sum('tong_tien');
        $dtTongDonHang = (clone $query)->count();
        $dtTrungBinh = $dtTongDonHang > 0 ? round($dtTongDoanhThu / $dtTongDonHang) : 0;

        $dtTheoNgay = $this->layDoanhThuTheoNgay($tuNgay, $denNgay);

        $dtBang = (clone $query)
            ->selectRaw('DATE(ngay_dat) as ngay, SUM(tong_tien) as doanh_thu, COUNT(*) as so_don')
            ->groupBy('ngay')
            ->orderBy('ngay', 'desc')
            ->get();

        return compact('tuNgay', 'denNgay', 'dtTongDoanhThu', 'dtTongDonHang', 'dtTrungBinh', 'dtTheoNgay', 'dtBang');
    }

    private function dataLoiNhuan(Request $request): array
    {
        $tuNgay = $request->filled('tu_ngay')
            ? Carbon::parse($request->tu_ngay)->startOfDay()
            : Carbon::now()->startOfMonth();
        $denNgay = $request->filled('den_ngay')
            ? Carbon::parse($request->den_ngay)->endOfDay()
            : Carbon::now()->endOfDay();

        $lnTongLoiNhuan = DB::table('chi_tiet_hoa_don as ct')
            ->join('hoa_don as hd', 'ct.ma_hoa_don', '=', 'hd.ma_hoa_don')
            ->where('hd.trang_thai', 'DELIVERED')
            ->whereBetween('hd.ngay_dat', [$tuNgay, $denNgay])
            ->selectRaw('SUM((ct.gia_ban_snapshot - ct.gia_nhap_snapshot) * ct.so_luong) as loi_nhuan')
            ->value('loi_nhuan') ?? 0;

        $lnTongDoanhThu = HoaDon::whereBetween('ngay_dat', [$tuNgay, $denNgay])
            ->where('trang_thai', 'DELIVERED')
            ->sum('tong_tien');

        $lnTheoNgay = DB::table('chi_tiet_hoa_don as ct')
            ->join('hoa_don as hd', 'ct.ma_hoa_don', '=', 'hd.ma_hoa_don')
            ->where('hd.trang_thai', 'DELIVERED')
            ->whereBetween('hd.ngay_dat', [$tuNgay, $denNgay])
            ->selectRaw('DATE(hd.ngay_dat) as ngay, SUM((ct.gia_ban_snapshot - ct.gia_nhap_snapshot) * ct.so_luong) as loi_nhuan')
            ->groupBy('ngay')
            ->orderBy('ngay')
            ->get();

        $lnLabels = $lnTheoNgay->pluck('ngay')->toArray();
        $lnData = $lnTheoNgay->pluck('loi_nhuan')->toArray();

        return compact('tuNgay', 'denNgay', 'lnTongLoiNhuan', 'lnTongDoanhThu', 'lnTheoNgay', 'lnLabels', 'lnData');
    }

    private function dataSanPham(Request $request): array
    {
        $spTop = (int) $request->get('top', 10);
        $spDanhSach = $this->layTopSanPham($spTop);
        $spTongBan = DB::table('chi_tiet_hoa_don as ct')
            ->join('hoa_don as hd', 'ct.ma_hoa_don', '=', 'hd.ma_hoa_don')
            ->where('hd.trang_thai', 'DELIVERED')
            ->sum('ct.so_luong') ?? 0;

        return compact('spTop', 'spDanhSach', 'spTongBan');
    }

    private function dataTonKho(Request $request): array
    {
        $tkNguong = (int) $request->get('nguong', 5);

        $tkSapHet = SanPham::where('so_luong', '<', $tkNguong)
            ->where('trang_thai', 'DANG_BAN')
            ->orderBy('so_luong')
            ->get();

        $tkLoHang = DB::table('chi_tiet_nhap as ctn')
            ->join('san_pham as sp', 'ctn.ma_san_pham', '=', 'sp.ma_san_pham')
            ->join('phieu_nhap as pn', 'ctn.ma_phieu_nhap', '=', 'pn.ma_phieu_nhap')
            ->where('pn.trang_thai', 'CONFIRMED')
            ->where('ctn.so_luong_con_lai', '>', 0)
            ->select('sp.ten_san_pham', 'ctn.gia_nhap', 'ctn.gia_ban', 'ctn.so_luong', 'ctn.so_luong_con_lai', 'pn.ngay_nhap')
            ->orderBy('pn.ngay_nhap', 'desc')
            ->get();

        $tkNhapXuat = DB::table('san_pham as sp')
            ->leftJoin('chi_tiet_nhap as ctn', 'sp.ma_san_pham', '=', 'ctn.ma_san_pham')
            ->leftJoin('phieu_nhap as pn', function ($join) {
                $join->on('ctn.ma_phieu_nhap', '=', 'pn.ma_phieu_nhap')
                     ->where('pn.trang_thai', 'CONFIRMED');
            })
            ->leftJoin('chi_tiet_hoa_don as cthd', 'sp.ma_san_pham', '=', 'cthd.ma_san_pham')
            ->leftJoin('hoa_don as hd', function ($join) {
                $join->on('cthd.ma_hoa_don', '=', 'hd.ma_hoa_don')
                     ->where('hd.trang_thai', 'DELIVERED');
            })
            ->select(
                'sp.ma_san_pham', 'sp.ten_san_pham', 'sp.so_luong as ton_hien_tai',
                DB::raw('SUM(DISTINCT ctn.so_luong) as tong_nhap'),
                DB::raw('SUM(cthd.so_luong) as tong_ban')
            )
            ->groupBy('sp.ma_san_pham', 'sp.ten_san_pham', 'sp.so_luong')
            ->orderBy('sp.ten_san_pham')
            ->get();

        return compact('tkNguong', 'tkSapHet', 'tkLoHang', 'tkNhapXuat');
    }

    private function dataKhachHang(Request $request): array
    {
        $khTop = (int) $request->get('top', 10);

        $khDanhSach = DB::table('khach_hang as kh')
            ->join('hoa_don as hd', 'kh.ma_khach_hang', '=', 'hd.ma_khach_hang')
            ->where('hd.trang_thai', 'DELIVERED')
            ->select(
                'kh.ma_khach_hang', 'kh.ten_khach_hang', 'kh.so_dien_thoai', 'kh.email',
                DB::raw('COUNT(hd.ma_hoa_don) as so_don_hang'),
                DB::raw('SUM(hd.tong_tien) as tong_tien_mua')
            )
            ->groupBy('kh.ma_khach_hang', 'kh.ten_khach_hang', 'kh.so_dien_thoai', 'kh.email')
            ->orderBy('tong_tien_mua', 'desc')
            ->limit($khTop)
            ->get();

        $khTongKhachHang = KhachHang::count();

        return compact('khTop', 'khDanhSach', 'khTongKhachHang');
    }

    private function layDoanhThuTheoNgay($tuNgay, $denNgay): array
    {
        $rawData = HoaDon::whereBetween('ngay_dat', [$tuNgay, $denNgay])
            ->where(function ($q) {
                $q->where('trang_thai', 'DELIVERED')
                  ->orWhere('trang_thai_thanh_toan', 'DA_THANH_TOAN');
            })
            ->selectRaw('DATE(ngay_dat) as ngay, SUM(tong_tien) as doanh_thu')
            ->groupBy('ngay')
            ->orderBy('ngay')
            ->get()
            ->keyBy('ngay');

        $labels = [];
        $data = [];
        $current = Carbon::parse($tuNgay)->copy();
        $end = Carbon::parse($denNgay)->copy();

        while ($current->lte($end)) {
            $key = $current->format('Y-m-d');
            $labels[] = $current->format('d/m');
            $data[] = isset($rawData[$key]) ? (float) $rawData[$key]->doanh_thu : 0;
            $current->addDay();
        }

        return compact('labels', 'data');
    }

    private function layTopSanPham(int $top = 10)
    {
        return DB::table('chi_tiet_hoa_don as ct')
            ->join('hoa_don as hd', 'ct.ma_hoa_don', '=', 'hd.ma_hoa_don')
            ->join('san_pham as sp', 'ct.ma_san_pham', '=', 'sp.ma_san_pham')
            ->where('hd.trang_thai', 'DELIVERED')
            ->select(
                'sp.ten_san_pham',
                DB::raw('SUM(ct.so_luong) as tong_ban'),
                DB::raw('SUM(ct.so_luong * ct.gia_ban_snapshot) as tong_doanh_thu')
            )
            ->groupBy('sp.ma_san_pham', 'sp.ten_san_pham')
            ->orderBy('tong_ban', 'desc')
            ->limit($top)
            ->get();
    }
}