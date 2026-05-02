<?php

namespace App\Http\Controllers;

use App\Models\SanPham;
use Illuminate\Http\Request;

class SanPhamController extends Controller
{
    private $danhSachLoai = [
        'HOA_TUOI'         => 'Hoa Tươi',
        'HOA_GIA'          => 'Hoa Giả',
        'SAN_PHAM_PREMIUM' => 'Sản Phẩm Premium',
        'CHAU_HOA_GIA'     => 'Chậu Hoa Giả',
        'CHAU_HOA_TUOI'    => 'Chậu Hoa Tươi',
        'CAY_CANH'         => 'Cây Cảnh',
        'HOA_SAP'          => 'Hoa Sáp',
        'HOA_GIAY_NHUN'    => 'Hoa Giấy Nhún',
        'TERRARIUM'        => 'Terrarium',
        'PHU_KIEN'         => 'Phụ Kiện',
        'QUA_TANG'         => 'Quà Tặng',
    ];


    public function index(Request $request)
    {
        $query = SanPham::query();

        if ($request->filled('loai_san_pham')) {
            $query->where('loai_san_pham', $request->loai_san_pham);
        }

        $danhSachSanPham = $query->orderBy('ma_san_pham', 'desc')->get();
        $danhSachLoai    = $this->danhSachLoai;

        return view('SanPham', compact('danhSachSanPham', 'danhSachLoai'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'ten_san_pham'  => 'required|string|max:100',
            'gia'           => 'required|numeric|min:0',
           
            'loai_san_pham' => 'required|in:' . implode(',', array_keys($this->danhSachLoai)),
            'mo_ta'         => 'nullable|string',
            'hinh_anh'      => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ], [
            'ten_san_pham.required'  => 'Vui lòng nhập tên sản phẩm.',
            'gia.required'           => 'Vui lòng nhập giá.',
            'gia.numeric'            => 'Giá phải là số.',
            'loai_san_pham.required' => 'Vui lòng chọn loại sản phẩm.',
            'hinh_anh.image'         => 'File tải lên phải là hình ảnh.',
            'hinh_anh.max'           => 'Ảnh không được vượt quá 2MB.',
        ]);

        $duongDanAnh = null;
        if ($request->hasFile('hinh_anh')) {
            $file    = $request->file('hinh_anh');
            $tenFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img'), $tenFile);
            $duongDanAnh = 'img/' . $tenFile;
        }

        SanPham::create([
            'ten_san_pham'  => $request->ten_san_pham,
            'gia'           => $request->gia,
            'so_luong'      => 0,           
            'loai_san_pham' => $request->loai_san_pham,
            'mo_ta'         => $request->mo_ta,
            'hinh_anh'      => $duongDanAnh,
            'trang_thai'    => 'DANG_BAN',
        ]);

        return redirect()->route('san-pham.index')
                         ->with('success', 'Thêm sản phẩm thành công! Số lượng ban đầu là 0, hãy tạo phiếu nhập để thêm hàng vào kho.');
    }


    public function editData($id)
    {
        $sanPham = SanPham::findOrFail($id);
        return response()->json($sanPham);
    }


    public function update(Request $request, $id)
    {
        $sanPham = SanPham::findOrFail($id);

        $request->validate([
            'ten_san_pham'  => 'required|string|max:100',
            'gia'           => 'required|numeric|min:0',
            
            'loai_san_pham' => 'required|in:' . implode(',', array_keys($this->danhSachLoai)),
            'mo_ta'         => 'nullable|string',
            'hinh_anh'      => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ], [
            'ten_san_pham.required'  => 'Vui lòng nhập tên sản phẩm.',
            'gia.required'           => 'Vui lòng nhập giá.',
            'loai_san_pham.required' => 'Vui lòng chọn loại sản phẩm.',
            'hinh_anh.image'         => 'File tải lên phải là hình ảnh.',
            'hinh_anh.max'           => 'Ảnh không được vượt quá 2MB.',
        ]);

        $duongDanAnh = $sanPham->hinh_anh;
        if ($request->hasFile('hinh_anh')) {
            if ($sanPham->hinh_anh && file_exists(public_path($sanPham->hinh_anh))) {
                unlink(public_path($sanPham->hinh_anh));
            }
            $file    = $request->file('hinh_anh');
            $tenFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img'), $tenFile);
            $duongDanAnh = 'img/' . $tenFile;
        }

        $sanPham->update([
            'ten_san_pham'  => $request->ten_san_pham,
            'gia'           => $request->gia,
           
            'loai_san_pham' => $request->loai_san_pham,
            'mo_ta'         => $request->mo_ta,
            'hinh_anh'      => $duongDanAnh,
        ]);

        return redirect()->route('san-pham.index')
                         ->with('success', 'Cập nhật sản phẩm thành công!');
    }


    public function toggleTrangThai($id)
    {
        $sanPham = SanPham::findOrFail($id);

        if ($sanPham->trang_thai === 'DANG_BAN') {
            $sanPham->trang_thai = 'NGUNG_BAN';
            $thongBao = 'Đã ẩn sản phẩm "' . $sanPham->ten_san_pham . '"!';
        } else {
            $sanPham->trang_thai = 'DANG_BAN';
            $thongBao = 'Đã hiện lại sản phẩm "' . $sanPham->ten_san_pham . '"!';
        }

        $sanPham->save();

        return redirect()->route('san-pham.index')->with('success', $thongBao);
    }
}