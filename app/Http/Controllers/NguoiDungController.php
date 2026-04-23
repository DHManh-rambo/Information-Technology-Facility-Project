<?php

namespace App\Http\Controllers;

use App\Models\NguoiDung;
use App\Models\KhachHang;
use App\Models\NhanVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NguoiDungController extends Controller
{
    public function index()
    {
        $nguoiDungs = NguoiDung::with(['khachHang', 'nhanVien'])->get();
        return view('NguoiDung', compact('nguoiDungs'));
    }

    public function editData($id)
    {
        $user = NguoiDung::with(['khachHang', 'nhanVien'])->findOrFail($id);
        $data = $user->toArray();

        if ($user->isKhachHang() && $user->khachHang) {
            $data = array_merge($data, $user->khachHang->toArray());
        } elseif ($user->isNhanVien() && $user->nhanVien) {
            $data = array_merge($data, $user->nhanVien->toArray());
        }

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'ten_dang_nhap' => 'required|unique:nguoi_dung',
            'mat_khau' => 'required|min:4',
            'vai_tro' => 'required|in:KHACH_HANG,NHAN_VIEN,SHIPPER',
        ]);

        DB::beginTransaction();
        try {
            $user = NguoiDung::create([
                'ten_dang_nhap' => $request->ten_dang_nhap,
                'mat_khau' => $request->mat_khau, 
                'vai_tro' => $request->vai_tro,
            ]);

            
            if ($request->vai_tro == 'KHACH_HANG') {
                $this->validateKhachHang($request);
                KhachHang::create([
                    'ma_khach_hang' => $user->ma_nguoi_dung,
                    'ten_khach_hang' => $request->ten_khach_hang,
                    'so_dien_thoai' => $request->so_dien_thoai,
                    'email' => $request->email,
                    'dia_chi' => $request->dia_chi,
                    'diem_tich_luy' => $request->diem_tich_luy ?? 0,
                ]);
            } else { 
                $this->validateNhanVien($request);
                NhanVien::create([
                    'ma_nhan_vien' => $user->ma_nguoi_dung,
                    'ten_nhan_vien' => $request->ten_nhan_vien,
                    'email' => $request->email,
                    'so_dien_thoai' => $request->so_dien_thoai,
                    'chuc_vu' => $request->chuc_vu,
                    'cong_viec' => $request->cong_viec,
                    'luong' => $request->luong,
                ]);
            }

            DB::commit();
            return redirect()->route('nguoi-dung.index')->with('success', 'Thêm người dùng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Lỗi: ' . $e->getMessage())->withInput();
        }
    }

    
    public function update(Request $request, $id)
    {
        $user = NguoiDung::findOrFail($id);

        $request->validate([
            'ten_dang_nhap' => 'required|unique:nguoi_dung,ten_dang_nhap,' . $id . ',ma_nguoi_dung',
            'mat_khau' => 'nullable|min:4',
            
        ]);

        DB::beginTransaction();
        try {

            $user->ten_dang_nhap = $request->ten_dang_nhap;
            if ($request->filled('mat_khau')) {
                $user->mat_khau = $request->mat_khau;
            }
            $user->save();

            if ($user->isKhachHang()) {
                $this->validateKhachHang($request);
                KhachHang::updateOrCreate(
                    ['ma_khach_hang' => $user->ma_nguoi_dung],
                    [
                        'ten_khach_hang' => $request->ten_khach_hang,
                        'so_dien_thoai' => $request->so_dien_thoai,
                        'email' => $request->email,
                        'dia_chi' => $request->dia_chi,
                        'diem_tich_luy' => $request->diem_tich_luy ?? 0,
                    ]
                );
            } elseif ($user->isNhanVien()) {
                $this->validateNhanVien($request);
                NhanVien::updateOrCreate(
                    ['ma_nhan_vien' => $user->ma_nguoi_dung],
                    [
                        'ten_nhan_vien' => $request->ten_nhan_vien,
                        'email' => $request->email,
                        'so_dien_thoai' => $request->so_dien_thoai,
                        'chuc_vu' => $request->chuc_vu,
                        'cong_viec' => $request->cong_viec,
                        'luong' => $request->luong,
                    ]
                );
            }

            DB::commit();
            return redirect()->route('nguoi-dung.index')->with('success', 'Cập nhật thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Lỗi: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $user = NguoiDung::findOrFail($id);
        DB::beginTransaction();
        try {
            if ($user->isKhachHang()) {
                KhachHang::where('ma_khach_hang', $id)->delete();
            } elseif ($user->isNhanVien()) {
                NhanVien::where('ma_nhan_vien', $id)->delete();
            }
            $user->delete();
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    private function validateKhachHang(Request $request)
    {
        $request->validate([
            'ten_khach_hang' => 'required|string|max:100',
            'so_dien_thoai' => 'required|string|max:15',
            'email' => 'required|email|max:100',
            'dia_chi' => 'nullable|string',
            'diem_tich_luy' => 'nullable|integer',
        ]);
    }

    private function validateNhanVien(Request $request)
    {
        $request->validate([
            'ten_nhan_vien' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'so_dien_thoai' => 'required|string|max:15',
            'chuc_vu' => 'required|in:CSKH,VAN_HANH,THIET_KE,ONLINE,SHIPPER,KHAC',
            'cong_viec' => 'nullable|string',
            'luong' => 'nullable|numeric|min:0',
        ]);
    }
}