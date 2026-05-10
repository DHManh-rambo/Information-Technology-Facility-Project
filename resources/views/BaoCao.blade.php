<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Báo Cáo & Thống Kê</title>
    <link rel="stylesheet" href="{{ asset('css/BaoCao.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="wrapper">

    <div class="page-header">
        <div>
            <h1> Báo Cáo & Thống Kê</h1>
            <p>Quản lý doanh thu, lợi nhuận, tồn kho và khách hàng</p>
        </div>
    </div>

    <div class="tabs">
        <button class="tab-btn {{ $tab === 'dashboard'  ? 'active' : '' }}" onclick="doiTab('dashboard', this)"> Tổng Quan</button>
        <button class="tab-btn {{ $tab === 'doanh-thu'  ? 'active' : '' }}" onclick="doiTab('doanh-thu', this)"> Doanh Thu</button>
        <button class="tab-btn {{ $tab === 'loi-nhuan'  ? 'active' : '' }}" onclick="doiTab('loi-nhuan', this)"> Lợi Nhuận</button>
        <button class="tab-btn {{ $tab === 'san-pham'   ? 'active' : '' }}" onclick="doiTab('san-pham', this)"> Sản Phẩm Bán Chạy</button>
        <button class="tab-btn {{ $tab === 'ton-kho'    ? 'active' : '' }}" onclick="doiTab('ton-kho', this)"> Tồn Kho</button>
        <button class="tab-btn {{ $tab === 'khach-hang' ? 'active' : '' }}" onclick="doiTab('khach-hang', this)"> Khách Hàng</button>
        <button class="tab-btn {{ $tab === 'hang-hong'  ? 'active' : '' }}" onclick="doiTab('hang-hong', this)" style="color:#dc3545;"> Hàng Hỏng</button>
    </div>

    {{-- ==================== TỔNG QUAN ==================== --}}
    <div id="tab-dashboard" class="tab-content {{ $tab === 'dashboard' ? 'active' : '' }}">

        <div class="stats-grid">
            <div class="stat-card highlight">
                <div class="label">Tổng Doanh Thu</div>
                <div class="value">{{ number_format($tongDoanhThu, 0, ',', '.') }}đ</div>
                <div class="sub">Toàn hệ thống</div>
            </div>
            <div class="stat-card">
                <div class="label">Tổng Lợi Nhuận</div>
                <div class="value">{{ number_format($tongLoiNhuan, 0, ',', '.') }}đ</div>
                <div class="sub">Hóa đơn đã hoàn thành</div>
            </div>
            <div class="stat-card">
                <div class="label">Tổng Hóa Đơn</div>
                <div class="value">{{ number_format($tongHoaDon) }}</div>
                <div class="sub">Tất cả đơn hàng</div>
            </div>
            <div class="stat-card">
                <div class="label">Sản Phẩm Đã Bán</div>
                <div class="value">{{ number_format($tongSanPhamBan) }}</div>
                <div class="sub">Tổng số lượng</div>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="label">Đơn Hàng Hôm Nay</div>
                <div class="value">{{ $donHangHomNay }}</div>
                <div class="sub">{{ \Carbon\Carbon::today()->format('d/m/Y') }}</div>
            </div>
            <div class="stat-card">
                <div class="label">Doanh Thu Hôm Nay</div>
                <div class="value">{{ number_format($doanhThuHomNay, 0, ',', '.') }}đ</div>
                <div class="sub">Đơn đã hoàn thành</div>
            </div>
            <div class="stat-card {{ $sanPhamSapHetSo > 0 ? 'warn' : '' }}">
                <div class="label">Sắp Hết Hàng</div>
                <div class="value">{{ $sanPhamSapHetSo }}</div>
                <div class="sub">Sản phẩm dưới 5 cái</div>
            </div>
            {{-- Mini card hàng hỏng --}}
            <div class="stat-card {{ $tongHangHongHomNay > 0 ? 'warn' : '' }}"
                 style="cursor:pointer" onclick="chuyenTabHangHong()">
                <div class="label"> Hàng Hỏng Hôm Nay</div>
                <div class="value" style="color:#dc3545">{{ $tongHangHongHomNay }}</div>
                <div class="sub">Click để xem chi tiết</div>
            </div>
        </div>

        <div class="chart-grid">
            <div class="chart-box" style="flex:2">
                <div class="chart-title"> Doanh Thu 7 Ngày Gần Nhất</div>
                <canvas id="chartDoanhThu7Ngay"></canvas>
            </div>
            <div class="chart-box">
                <div class="chart-title"> Trạng Thái Đơn Hàng</div>
                <canvas id="chartTrangThai"></canvas>
            </div>
        </div>

        <div class="card">
            <div class="card-title"> Top 5 Sản Phẩm Bán Chạy</div>
            <div class="chart-box" style="border:none;padding:0">
                <canvas id="chartTopSanPham"></canvas>
            </div>
        </div>
    </div>

    {{-- ==================== DOANH THU ==================== --}}
    <div id="tab-doanh-thu" class="tab-content {{ $tab === 'doanh-thu' ? 'active' : '' }}">

        <form method="GET" action="{{ route('bao-cao.index') }}">
            <input type="hidden" name="tab" value="doanh-thu">
            <div class="filter-bar">
                <div>
                    <label>Từ Ngày</label>
                    <input type="date" name="tu_ngay"
                        value="{{ isset($tuNgay) ? \Carbon\Carbon::parse($tuNgay)->format('Y-m-d') : \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d') }}">
                </div>
                <div>
                    <label>Đến Ngày</label>
                    <input type="date" name="den_ngay"
                        value="{{ isset($denNgay) ? \Carbon\Carbon::parse($denNgay)->format('Y-m-d') : \Carbon\Carbon::now()->format('Y-m-d') }}">
                </div>
                <button type="submit" class="btn btn-dark">Xem Báo Cáo</button>
                <a href="{{ route('bao-cao.index', ['tab' => 'doanh-thu']) }}" class="btn btn-light">Đặt Lại</a>
            </div>
        </form>

        @isset($dtBang)
            <div class="stats-grid">
                <div class="stat-card highlight">
                    <div class="label">Tổng Doanh Thu</div>
                    <div class="value">{{ number_format($dtTongDoanhThu, 0, ',', '.') }}đ</div>
                </div>
                <div class="stat-card">
                    <div class="label">Số Đơn Hàng</div>
                    <div class="value">{{ number_format($dtTongDonHang) }}</div>
                </div>
                <div class="stat-card">
                    <div class="label">Doanh Thu Trung Bình / Đơn</div>
                    <div class="value">{{ number_format($dtTrungBinh, 0, ',', '.') }}đ</div>
                </div>
            </div>

            @if(count($dtTheoNgay['labels']) > 0)
            <div class="card">
                <div class="card-title"> Biểu Đồ Doanh Thu Theo Ngày</div>
                <canvas id="chartDoanhThuRange" style="max-height:280px"></canvas>
            </div>
            @endif

            <div class="card">
                <div class="card-title"> Chi Tiết Doanh Thu Từng Ngày</div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Ngày</th>
                                <th class="text-right">Số Đơn</th>
                                <th class="text-right">Doanh Thu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dtBang as $row)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($row->ngay)->format('d/m/Y') }}</td>
                                    <td class="text-right">{{ $row->so_don }}</td>
                                    <td class="text-right bold">{{ number_format($row->doanh_thu, 0, ',', '.') }}đ</td>
                                </tr>
                            @empty
                                <tr class="empty-row"><td colspan="3">Không có dữ liệu trong khoảng thời gian này.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endisset
    </div>

    {{-- ==================== LỢI NHUẬN ==================== --}}
    <div id="tab-loi-nhuan" class="tab-content {{ $tab === 'loi-nhuan' ? 'active' : '' }}">

        <form method="GET" action="{{ route('bao-cao.index') }}">
            <input type="hidden" name="tab" value="loi-nhuan">
            <div class="filter-bar">
                <div>
                    <label>Từ Ngày</label>
                    <input type="date" name="tu_ngay"
                        value="{{ isset($tuNgay) ? \Carbon\Carbon::parse($tuNgay)->format('Y-m-d') : \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d') }}">
                </div>
                <div>
                    <label>Đến Ngày</label>
                    <input type="date" name="den_ngay"
                        value="{{ isset($denNgay) ? \Carbon\Carbon::parse($denNgay)->format('Y-m-d') : \Carbon\Carbon::now()->format('Y-m-d') }}">
                </div>
                <button type="submit" class="btn btn-dark">Xem Báo Cáo</button>
                <a href="{{ route('bao-cao.index', ['tab' => 'loi-nhuan']) }}" class="btn btn-light">Đặt Lại</a>
            </div>
        </form>

        @isset($lnTheoNgay)
            <div class="stats-grid">
                <div class="stat-card highlight">
                    <div class="label">Tổng Lợi Nhuận</div>
                    <div class="value">{{ number_format($lnTongLoiNhuan, 0, ',', '.') }}đ</div>
                    <div class="sub">= Giá bán - Giá nhập</div>
                </div>
                <div class="stat-card">
                    <div class="label">Tổng Doanh Thu</div>
                    <div class="value">{{ number_format($lnTongDoanhThu, 0, ',', '.') }}đ</div>
                </div>
                @if($lnTongDoanhThu > 0)
                <div class="stat-card">
                    <div class="label">Tỷ Suất Lợi Nhuận</div>
                    <div class="value">{{ round(($lnTongLoiNhuan / $lnTongDoanhThu) * 100, 1) }}%</div>
                    <div class="sub">Lợi nhuận / Doanh thu</div>
                </div>
                @endif
            </div>

            @if($lnTheoNgay->count() > 0)
            <div class="card">
                <div class="card-title"> Biểu Đồ Lợi Nhuận Theo Ngày</div>
                <canvas id="chartLoiNhuan" style="max-height:280px"></canvas>
            </div>
            @endif

            <div class="card">
                <div class="card-title"> Chi Tiết Lợi Nhuận Từng Ngày</div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Ngày</th>
                                <th class="text-right">Lợi Nhuận</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lnTheoNgay as $row)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($row->ngay)->format('d/m/Y') }}</td>
                                    <td class="text-right bold text-green">{{ number_format($row->loi_nhuan, 0, ',', '.') }}đ</td>
                                </tr>
                            @empty
                                <tr class="empty-row"><td colspan="2">Không có dữ liệu.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endisset
    </div>

    {{-- ==================== SẢN PHẨM BÁN CHẠY ==================== --}}
    <div id="tab-san-pham" class="tab-content {{ $tab === 'san-pham' ? 'active' : '' }}">

        <form method="GET" action="{{ route('bao-cao.index') }}">
            <input type="hidden" name="tab" value="san-pham">
            <div class="filter-bar">
                <div>
                    <label>Hiển Thị Top</label>
                    <select name="top">
                        <option value="5"  {{ isset($spTop) && $spTop == 5  ? 'selected' : '' }}>Top 5</option>
                        <option value="10" {{ !isset($spTop) || $spTop == 10 ? 'selected' : '' }}>Top 10</option>
                        <option value="20" {{ isset($spTop) && $spTop == 20 ? 'selected' : '' }}>Top 20</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-dark">Xem</button>
            </div>
        </form>

        @isset($spDanhSach)
            @if($spDanhSach->count() > 0)
            <div class="card">
                <div class="card-title"> Biểu Đồ Sản Phẩm Bán Chạy</div>
                <canvas id="chartSanPham" style="max-height:300px"></canvas>
            </div>
            @endif

            <div class="card">
                <div class="card-title flex-between">
                    <span> Danh Sách Sản Phẩm Bán Chạy</span>
                    <span style="font-size:12px;color:#888;font-weight:400">Tổng đã bán: <strong>{{ number_format($spTongBan) }}</strong> sản phẩm</span>
                </div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tên Sản Phẩm</th>
                                <th class="text-right">Số Lượng Đã Bán</th>
                                <th class="text-right">Tổng Doanh Thu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($spDanhSach as $i => $sp)
                                <tr>
                                    <td class="bold">{{ $i + 1 }}</td>
                                    <td>{{ $sp->ten_san_pham }}</td>
                                    <td class="text-right bold">{{ number_format($sp->tong_ban) }}</td>
                                    <td class="text-right">{{ number_format($sp->tong_doanh_thu, 0, ',', '.') }}đ</td>
                                </tr>
                            @empty
                                <tr class="empty-row"><td colspan="4">Chưa có dữ liệu bán hàng.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endisset
    </div>

    {{-- ==================== TỒN KHO ==================== --}}
    <div id="tab-ton-kho" class="tab-content {{ $tab === 'ton-kho' ? 'active' : '' }}">

        <form method="GET" action="{{ route('bao-cao.index') }}">
            <input type="hidden" name="tab" value="ton-kho">
            <div class="filter-bar">
                <div>
                    <label>Ngưỡng Cảnh Báo Hết Hàng</label>
                    <input type="number" name="nguong" min="1" max="100"
                        value="{{ $tkNguong ?? 5 }}" style="width:80px">
                    <span style="font-size:12px;color:#888;margin-left:4px">sản phẩm</span>
                </div>
                <button type="submit" class="btn btn-dark">Cập Nhật</button>
            </div>
        </form>

        @isset($tkSapHet)
            @if($tkSapHet->count() > 0)
            <div class="card" style="border-left:4px solid #e00">
                <div class="card-title" style="color:#e00"> Sản Phẩm Sắp Hết Hàng (dưới {{ $tkNguong }} cái)</div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Sản Phẩm</th>
                                <th class="text-center">Tồn Kho</th>
                                <th>Trạng Thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tkSapHet as $sp)
                                <tr>
                                    <td class="bold">{{ $sp->ten_san_pham }}</td>
                                    <td class="text-center text-red bold">{{ $sp->so_luong }}</td>
                                    <td><span class="badge badge-warn">Sắp Hết</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <div class="card">
                <div class="card-title"> Kiểm Tra Nhập Xuất Kho</div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Sản Phẩm</th>
                                <th class="text-right">Đã Nhập</th>
                                <th class="text-right">Đã Bán</th>
                                <th class="text-right">Tồn Hiện Tại</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tkNhapXuat as $row)
                                <tr>
                                    <td>{{ $row->ten_san_pham }}</td>
                                    <td class="text-right">{{ number_format($row->tong_nhap ?? 0) }}</td>
                                    <td class="text-right">{{ number_format($row->tong_ban ?? 0) }}</td>
                                    <td class="text-right bold {{ $row->ton_hien_tai < $tkNguong ? 'text-red' : 'text-green' }}">
                                        {{ number_format($row->ton_hien_tai) }}
                                    </td>
                                </tr>
                            @empty
                                <tr class="empty-row"><td colspan="4">Không có dữ liệu.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($tkLoHang->count() > 0)
            <div class="card">
                <div class="card-title"> Theo Dõi Từng Lô Hàng (Còn Tồn)</div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Sản Phẩm</th>
                                <th class="text-right">Giá Nhập</th>
                                <th class="text-right">Giá Bán</th>
                                <th class="text-right">SL Nhập</th>
                                <th class="text-right">SL Còn Lại</th>
                                <th>Ngày Nhập</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tkLoHang as $lo)
                                <tr>
                                    <td>{{ $lo->ten_san_pham }}</td>
                                    <td class="text-right">{{ number_format($lo->gia_nhap, 0, ',', '.') }}đ</td>
                                    <td class="text-right">{{ number_format($lo->gia_ban, 0, ',', '.') }}đ</td>
                                    <td class="text-right">{{ number_format($lo->so_luong) }}</td>
                                    <td class="text-right bold">{{ number_format($lo->so_luong_con_lai) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($lo->ngay_nhap)->format('d/m/Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        @endisset
    </div>

    {{-- ==================== KHÁCH HÀNG ==================== --}}
    <div id="tab-khach-hang" class="tab-content {{ $tab === 'khach-hang' ? 'active' : '' }}">

        <form method="GET" action="{{ route('bao-cao.index') }}">
            <input type="hidden" name="tab" value="khach-hang">
            <div class="filter-bar">
                <div>
                    <label>Hiển Thị Top</label>
                    <select name="top">
                        <option value="5"  {{ isset($khTop) && $khTop == 5  ? 'selected' : '' }}>Top 5</option>
                        <option value="10" {{ !isset($khTop) || $khTop == 10 ? 'selected' : '' }}>Top 10</option>
                        <option value="20" {{ isset($khTop) && $khTop == 20 ? 'selected' : '' }}>Top 20</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-dark">Xem</button>
            </div>
        </form>

        @isset($khTongKhachHang)
        <div class="stats-grid">
            <div class="stat-card">
                <div class="label">Tổng Khách Hàng</div>
                <div class="value">{{ number_format($khTongKhachHang) }}</div>
            </div>
        </div>
        @endisset

        @isset($khDanhSach)
            @if($khDanhSach->count() > 0)
            <div class="card">
                <div class="card-title"> Biểu Đồ Khách Hàng Mua Nhiều Nhất</div>
                <canvas id="chartKhachHang" style="max-height:280px"></canvas>
            </div>

            <div class="card">
                <div class="card-title"> Top Khách Hàng Tiềm Năng</div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tên Khách Hàng</th>
                                <th>Số Điện Thoại</th>
                                <th class="text-right">Số Đơn</th>
                                <th class="text-right">Tổng Chi Tiêu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($khDanhSach as $i => $kh)
                                <tr>
                                    <td class="bold">{{ $i + 1 }}</td>
                                    <td class="bold">{{ $kh->ten_khach_hang }}</td>
                                    <td>{{ $kh->so_dien_thoai }}</td>
                                    <td class="text-right">{{ $kh->so_don_hang }}</td>
                                    <td class="text-right bold">{{ number_format($kh->tong_tien_mua, 0, ',', '.') }}đ</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        @endisset
    </div>

    {{-- ==================== HÀNG HỎNG ==================== --}}
    <div id="tab-hang-hong" class="tab-content {{ $tab === 'hang-hong' ? 'active' : '' }}">

        {{-- Nút load data nếu chưa có --}}
        @if($tab !== 'hang-hong')
        <div class="card" style="text-align:center;padding:32px">
            <a href="{{ route('bao-cao.index', ['tab' => 'hang-hong']) }}" class="btn btn-dark">
                 Tải Báo Cáo Hàng Hỏng
            </a>
        </div>
        @else

        {{-- ---- THỐNG KÊ TỔNG ---- --}}
        <div class="stats-grid">
            <div class="stat-card" style="border-left:4px solid #dc3545">
                <div class="label">Tổng Hàng Hỏng</div>
                <div class="value" style="color:#dc3545">{{ number_format($hhTongSoLuong) }}</div>
                <div class="sub">Tổng số lượng đã hỏng</div>
            </div>
            <div class="stat-card">
                <div class="label">Số Lần Báo Cáo</div>
                <div class="value">{{ number_format($hhTongLanBaoCao) }}</div>
                <div class="sub">Tổng lần ghi nhận</div>
            </div>
            <div class="stat-card">
                <div class="label"> Hoa Tươi Hỏng</div>
                <div class="value" style="color:#e67e22">{{ number_format($hhTongTuoi) }}</div>
                <div class="sub">HOA_TUOI, CHAU_HOA_TUOI</div>
            </div>
            <div class="stat-card">
                <div class="label"> Hàng Khác Hỏng</div>
                <div class="value" style="color:#8e44ad">{{ number_format($hhTongKhacTuoi) }}</div>
                <div class="sub">Hoa giả, cây cảnh, quà tặng...</div>
            </div>
        </div>

        {{-- ---- TOP SẢN PHẨM HỎNG NHIỀU NHẤT ---- --}}
        @if($hhTopHong->count() > 0)
        <div class="card">
            <div class="card-title"> Top Sản Phẩm Hỏng Nhiều Nhất</div>
            <div class="chart-box" style="border:none;padding:0;max-height:220px">
                <canvas id="chartTopHangHong"></canvas>
            </div>
        </div>
        @endif

        {{-- ================================================================ --}}
        {{-- NHÓM 1: HOA TƯƠI                                                 --}}
        {{-- ================================================================ --}}
        <div class="card" style="border-top:3px solid #e67e22">
            <div class="card-title" style="color:#e67e22">
                 Danh Sách Hàng Hỏng — Hoa Tươi
                <span style="font-size:12px;color:#888;font-weight:400;margin-left:8px">
                    (HOA_TUOI, CHAU_HOA_TUOI)
                </span>
                <span style="font-size:13px;background:#fde8d8;color:#e67e22;
                             padding:2px 10px;border-radius:20px;margin-left:8px;font-weight:600">
                    Tổng hỏng: {{ $hhTongTuoi }}
                </span>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Sản Phẩm</th>
                            <th class="text-right">SL Hỏng</th>
                            <th>Lý Do</th>
                            <th>Ghi Chú</th>
                            <th>Nhân Viên</th>
                            <th>Thời Gian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hhDanhSachTuoi as $i => $bc)
                            <tr>
                                <td class="bold" style="color:#e67e22">{{ $i + 1 }}</td>
                                <td class="bold">{{ $bc->ten_san_pham }}</td>
                                <td class="text-right bold" style="color:#dc3545">
                                    -{{ number_format($bc->so_luong_hong) }}
                                </td>
                                <td>{{ $bc->ly_do ?: '—' }}</td>
                                <td style="font-size:12px;color:#888">
                                    {{ $bc->ghi_chu ? \Illuminate\Support\Str::limit($bc->ghi_chu, 40) : '—' }}
                                </td>
                                <td>{{ $bc->ten_nhan_vien }}</td>
                                <td style="white-space:nowrap;font-size:12px">
                                    {{ \Carbon\Carbon::parse($bc->thoi_gian_bao_cao)->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr class="empty-row">
                                <td colspan="7">Chưa có báo cáo hỏng cho nhóm hoa tươi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ================================================================ --}}
        {{-- NHÓM 2: HÀNG KHÔNG TƯƠI (hoa giả, cây cảnh, quà tặng…)         --}}
        {{-- ================================================================ --}}
        <div class="card" style="border-top:3px solid #8e44ad">
            <div class="card-title" style="color:#8e44ad">
                 Danh Sách Hàng Hỏng — Hàng Không Tươi
                <span style="font-size:12px;color:#888;font-weight:400;margin-left:8px">
                    (Hoa giả, Terrarium, Cây cảnh, Quà tặng...)
                </span>
                <span style="font-size:13px;background:#f0e6f8;color:#8e44ad;
                             padding:2px 10px;border-radius:20px;margin-left:8px;font-weight:600">
                    Tổng hỏng: {{ $hhTongKhacTuoi }}
                </span>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Sản Phẩm</th>
                            <th class="text-right">SL Hỏng</th>
                            <th>Lý Do</th>
                            <th>Ghi Chú</th>
                            <th>Nhân Viên</th>
                            <th>Thời Gian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hhDanhSachKhacTuoi as $i => $bc)
                            <tr>
                                <td class="bold" style="color:#8e44ad">{{ $i + 1 }}</td>
                                <td class="bold">{{ $bc->ten_san_pham }}</td>
                                <td class="text-right bold" style="color:#dc3545">
                                    -{{ number_format($bc->so_luong_hong) }}
                                </td>
                                <td>{{ $bc->ly_do ?: '—' }}</td>
                                <td style="font-size:12px;color:#888">
                                    {{ $bc->ghi_chu ? \Illuminate\Support\Str::limit($bc->ghi_chu, 40) : '—' }}
                                </td>
                                <td>{{ $bc->ten_nhan_vien }}</td>
                                <td style="white-space:nowrap;font-size:12px">
                                    {{ \Carbon\Carbon::parse($bc->thoi_gian_bao_cao)->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr class="empty-row">
                                <td colspan="7">Chưa có báo cáo hỏng cho nhóm này.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @endif {{-- end if tab === hang-hong --}}
    </div>

</div>

<script>
function doiTab(tabId, btn) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
    document.getElementById('tab-' + tabId).classList.add('active');
    btn.classList.add('active');
}

// Hàm click từ dashboard card
function chuyenTabHangHong() {
    window.location.href = '{{ route('bao-cao.index', ['tab' => 'hang-hong']) }}';
}

const mauChinh = '#111111';
const mauXam   = ['#111','#333','#555','#777','#999','#bbb','#ccc','#ddd','#eee','#f5f5f5'];

// Chart: Doanh thu 7 ngày
const ctx1 = document.getElementById('chartDoanhThu7Ngay');
if (ctx1) {
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: @json($doanhThu7Ngay['labels']),
            datasets: [{
                label: 'Doanh Thu (đ)',
                data: @json($doanhThu7Ngay['data']),
                borderColor: mauChinh,
                backgroundColor: 'rgba(17,17,17,0.08)',
                borderWidth: 2,
                pointBackgroundColor: mauChinh,
                pointRadius: 4,
                fill: true,
                tension: 0.3,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    ticks: { callback: val => new Intl.NumberFormat('vi-VN').format(val) + 'đ' },
                    grid: { color: '#eee' }
                },
                x: { grid: { display: false } }
            }
        }
    });
}

// Chart: Trạng thái đơn hàng
const ctx2 = document.getElementById('chartTrangThai');
if (ctx2) {
    const nhanTrangThai = {
        'PENDING': 'Chờ Xử Lý', 'CONFIRMED': 'Đã Xác Nhận',
        'SHIPPING': 'Đang Giao', 'DELIVERED': 'Hoàn Thành', 'CANCELLED': 'Đã Hủy',
    };
    const rawTrangThai = @json($trangThaiDonHang);
    new Chart(ctx2, {
        type: 'pie',
        data: {
            labels: Object.keys(rawTrangThai).map(k => nhanTrangThai[k] || k),
            datasets: [{
                data: Object.values(rawTrangThai),
                backgroundColor: ['#111','#444','#777','#aaa','#ddd'],
                borderWidth: 2,
                borderColor: '#fff',
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom', labels: { font: { size: 11 } } } }
        }
    });
}

// Chart: Top 5 sản phẩm (dashboard)
const ctx3 = document.getElementById('chartTopSanPham');
if (ctx3) {
    const spLabels = @json($topSanPhamDashboard->pluck('ten_san_pham'));
    const spData   = @json($topSanPhamDashboard->pluck('tong_ban'));
    new Chart(ctx3, {
        type: 'bar',
        data: {
            labels: spLabels,
            datasets: [{
                label: 'Số lượng bán',
                data: spData,
                backgroundColor: mauXam.slice(0, spLabels.length),
                borderWidth: 0,
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { grid: { color: '#eee' }, beginAtZero: true },
                x: { grid: { display: false } }
            }
        }
    });
}

// Chart: Doanh thu theo khoảng ngày (tab doanh-thu)
@isset($dtTheoNgay)
@if(count($dtTheoNgay['labels']) > 0)
const ctx4 = document.getElementById('chartDoanhThuRange');
if (ctx4) {
    new Chart(ctx4, {
        type: 'line',
        data: {
            labels: @json($dtTheoNgay['labels']),
            datasets: [{
                label: 'Doanh Thu (đ)',
                data: @json($dtTheoNgay['data']),
                borderColor: mauChinh,
                backgroundColor: 'rgba(17,17,17,0.07)',
                borderWidth: 2,
                pointRadius: 3,
                fill: true,
                tension: 0.3,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    ticks: { callback: val => new Intl.NumberFormat('vi-VN').format(val) + 'đ' },
                    grid: { color: '#eee' }
                },
                x: { grid: { display: false } }
            }
        }
    });
}
@endif
@endisset

// Chart: Lợi nhuận theo ngày (tab loi-nhuan)
@isset($lnLabels)
@if(count($lnLabels) > 0)
const ctx5 = document.getElementById('chartLoiNhuan');
if (ctx5) {
    new Chart(ctx5, {
        type: 'line',
        data: {
            labels: @json($lnLabels),
            datasets: [{
                label: 'Lợi Nhuận (đ)',
                data: @json($lnData),
                borderColor: '#444',
                backgroundColor: 'rgba(0,0,0,0.06)',
                borderWidth: 2,
                pointRadius: 3,
                fill: true,
                tension: 0.3,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    ticks: { callback: val => new Intl.NumberFormat('vi-VN').format(val) + 'đ' },
                    grid: { color: '#eee' }
                },
                x: { grid: { display: false } }
            }
        }
    });
}
@endif
@endisset

// Chart: Sản phẩm bán chạy (tab san-pham)
@isset($spDanhSach)
@if($spDanhSach->count() > 0)
const ctx6 = document.getElementById('chartSanPham');
if (ctx6) {
    new Chart(ctx6, {
        type: 'bar',
        data: {
            labels: @json($spDanhSach->pluck('ten_san_pham')),
            datasets: [{
                label: 'Số lượng bán',
                data: @json($spDanhSach->pluck('tong_ban')),
                backgroundColor: mauXam.slice(0, {{ $spDanhSach->count() }}),
                borderWidth: 0,
                borderRadius: 4,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { color: '#eee' }, beginAtZero: true },
                y: { grid: { display: false } }
            }
        }
    });
}
@endif
@endisset

// Chart: Khách hàng (tab khach-hang)
@isset($khDanhSach)
@if($khDanhSach->count() > 0)
const ctx7 = document.getElementById('chartKhachHang');
if (ctx7) {
    new Chart(ctx7, {
        type: 'bar',
        data: {
            labels: @json($khDanhSach->pluck('ten_khach_hang')),
            datasets: [{
                label: 'Tổng Chi Tiêu (đ)',
                data: @json($khDanhSach->pluck('tong_tien_mua')),
                backgroundColor: mauXam.slice(0, {{ $khDanhSach->count() }}),
                borderWidth: 0,
                borderRadius: 4,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                x: {
                    ticks: { callback: val => new Intl.NumberFormat('vi-VN').format(val) + 'đ' },
                    grid: { color: '#eee' }
                },
                y: { grid: { display: false } }
            }
        }
    });
}
@endif
@endisset

// Chart: Top hàng hỏng (tab hang-hong)
@isset($hhTopHong)
@if($hhTopHong->count() > 0)
const ctx8 = document.getElementById('chartTopHangHong');
if (ctx8) {
    new Chart(ctx8, {
        type: 'bar',
        data: {
            labels: @json($hhTopHong->pluck('ten_san_pham')),
            datasets: [{
                label: 'Tổng hỏng',
                data: @json($hhTopHong->pluck('tong_hong')),
                backgroundColor: ['#dc3545','#e74c3c','#c0392b','#922b21','#7b241c'],
                borderWidth: 0,
                borderRadius: 4,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { color: '#eee' }, beginAtZero: true },
                y: { grid: { display: false } }
            }
        }
    });
}
@endif
@endisset
</script>
</body>
</html>