{{-- resources/views/hoa-don/index.blade.php --}}
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/HoaDon.css') }}">
    <title>Quản Lý Hóa Đơn – Flower Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=IBM+Plex+Mono:wght@400;500&family=IBM+Plex+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    
</head>
<body>

{{-- ==================== HEADER ==================== --}}
<header class="site-header">
    <h1>Flower Store</h1>
    
</header>

<div class="page-wrap">
<main class="main-col" id="mainCol">

    {{-- ================== BỘ LỌC & TÌM KIẾM ================== --}}
    <p class="section-label">[ 01 ] Bộ lọc & Tìm kiếm</p>
    <div class="filter-box">
        <form method="GET" action="{{ route('hoa-don.index') }}" id="filterForm">
            <div class="filter-grid">

                {{-- Khách hàng (select) --}}
                <div class="filter-group" style="grid-column: span 2;">
                    <label for="ma_khach_hang">Khách hàng</label>
                    <select name="ma_khach_hang" id="ma_khach_hang">
                        <option value="">— Tất cả khách hàng —</option>
                        @foreach($khachHangs as $kh)
                            <option value="{{ $kh->ma_khach_hang }}"
                                {{ request('ma_khach_hang') == $kh->ma_khach_hang ? 'selected' : '' }}>
                                {{ $kh->ten_khach_hang }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Trạng thái hóa đơn --}}
                <div class="filter-group">
                    <label for="trang_thai">Trạng thái đơn</label>
                    <select name="trang_thai" id="trang_thai">
                        <option value="">— Tất cả —</option>
                        @foreach(['PENDING','CONFIRMED','SHIPPING','DELIVERED','CANCELLED'] as $tt)
                            <option value="{{ $tt }}" {{ request('trang_thai') == $tt ? 'selected' : '' }}>
                                {{ $tt }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Trạng thái thanh toán --}}
                <div class="filter-group">
                    <label for="trang_thai_thanh_toan">Thanh toán</label>
                    <select name="trang_thai_thanh_toan" id="trang_thai_thanh_toan">
                        <option value="">— Tất cả —</option>
                        <option value="CHUA_THANH_TOAN" {{ request('trang_thai_thanh_toan') == 'CHUA_THANH_TOAN' ? 'selected' : '' }}>Chưa thanh toán</option>
                        <option value="DA_THANH_TOAN"   {{ request('trang_thai_thanh_toan') == 'DA_THANH_TOAN'   ? 'selected' : '' }}>Đã thanh toán</option>
                    </select>
                </div>

                {{-- Ngày cụ thể --}}
                <div class="filter-group">
                    <label for="ngay_dat">Ngày đặt (cụ thể)</label>
                    <input type="date" name="ngay_dat" id="ngay_dat" value="{{ request('ngay_dat') }}">
                </div>

                {{-- Từ ngày --}}
                <div class="filter-group">
                    <label for="tu_ngay">Từ ngày</label>
                    <input type="date" name="tu_ngay" id="tu_ngay" value="{{ request('tu_ngay') }}">
                </div>

                {{-- Đến ngày --}}
                <div class="filter-group">
                    <label for="den_ngay">Đến ngày</label>
                    <input type="date" name="den_ngay" id="den_ngay" value="{{ request('den_ngay') }}">
                </div>

            </div>

            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">
                    &#9906; Lọc kết quả
                </button>
                <a href="{{ route('hoa-don.index') }}" class="btn btn-ghost">
                    ↺ Đặt lại
                </a>
                @if(request()->hasAny(['ma_khach_hang','trang_thai','trang_thai_thanh_toan','ngay_dat','tu_ngay','den_ngay']))
                    <span style="font-family:var(--font-mono);font-size:0.65rem;color:var(--ink-soft);margin-left:auto;">
                        Đang lọc — {{ $hoaDons->total() }} kết quả
                    </span>
                @endif
            </div>
        </form>
    </div>

    {{-- ================== BẢNG HÓA ĐƠN ================== --}}
    <p class="section-label">[ 02 ] Danh sách hóa đơn</p>
    <div class="table-box">
        <div class="table-meta">
            <span>Tổng cộng: <strong>{{ $hoaDons->total() }}</strong> hóa đơn</span>
            <span>Trang {{ $hoaDons->currentPage() }} / {{ $hoaDons->lastPage() }}</span>
        </div>

        <div class="tbl-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Mã HD</th>
                        <th>Khách hàng</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Thanh toán</th>
                        <th>P/T TT</th>
                        <th>Địa chỉ giao</th>
                        <th>Người giao</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hoaDons as $index => $hd)
                    <tr id="row-{{ $hd->ma_hoa_don }}">
                        <td class="td-mono">{{ $hoaDons->firstItem() + $index }}</td>
                        <td class="td-mono">HD-{{ str_pad($hd->ma_hoa_don, 4, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $hd->khachHang?->ten_khach_hang ?? '—' }}</td>
                        <td class="td-mono">{{ $hd->ngay_dat?->format('d/m/Y H:i') ?? '—' }}</td>
                        <td class="td-mono">{{ number_format($hd->tong_tien, 0, ',', '.') }}đ</td>
                        <td>
                            @php
                                $ttClass = match($hd->trang_thai) {
                                    'PENDING'   => 'badge-pending',
                                    'CONFIRMED' => 'badge-confirmed',
                                    'SHIPPING'  => 'badge-shipping',
                                    'DELIVERED' => 'badge-delivered',
                                    'CANCELLED' => 'badge-cancelled',
                                    default     => '',
                                };
                            @endphp
                            <span class="badge {{ $ttClass }}">{{ $hd->trang_thai }}</span>
                        </td>
                        <td>
                            <span class="badge {{ $hd->trang_thai_thanh_toan === 'DA_THANH_TOAN' ? 'badge-paid' : 'badge-unpaid' }}">
                                {{ $hd->trang_thai_thanh_toan === 'DA_THANH_TOAN' ? 'Đã TT' : 'Chưa TT' }}
                            </span>
                        </td>
                        <td class="td-mono">{{ $hd->phuong_thuc_thanh_toan }}</td>
                        <td>{{ Str::limit($hd->dia_chi_giao, 24) }}</td>
                        <td>{{ $hd->nhanVienGiao?->ten_nhan_vien ?? '—' }}</td>
                        <td>
                            <div class="td-actions">
                                {{-- Nút Chi tiết --}}
                                <button class="btn btn-detail btn-sm"
                                        onclick="openPanel({{ $hd->ma_hoa_don }})"
                                        title="Xem chi tiết hóa đơn">
                                    ⊞ Chi tiết
                                </button>

                                {{-- Nút Xóa: chỉ hiện nếu chưa TT hoặc đã CANCELLED --}}
                                @if($hd->trang_thai_thanh_toan === 'CHUA_THANH_TOAN' || $hd->trang_thai === 'CANCELLED')
                                    <button class="btn btn-danger btn-sm"
                                            onclick="confirmDelete({{ $hd->ma_hoa_don }}, 'HD-{{ str_pad($hd->ma_hoa_don, 4, '0', STR_PAD_LEFT) }}')"
                                            title="Xóa hóa đơn">
                                        ✕ Xóa
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11">
                            <div class="empty-state">
                                <div class="empty-icon">◻</div>
                                <p>Không tìm thấy hóa đơn nào.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Phân trang --}}
        @if($hoaDons->hasPages())
        <div class="pagination-wrap">
            {{-- Trang trước --}}
            @if($hoaDons->onFirstPage())
                <span class="disabled">‹ Trước</span>
            @else
                <a href="{{ $hoaDons->previousPageUrl() }}">‹ Trước</a>
            @endif

            {{-- Số trang --}}
            @foreach($hoaDons->getUrlRange(1, $hoaDons->lastPage()) as $page => $url)
                @if($page == $hoaDons->currentPage())
                    <span class="current">{{ $page }}</span>
                @else
                    <a href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach

            {{-- Trang sau --}}
            @if($hoaDons->hasMorePages())
                <a href="{{ $hoaDons->nextPageUrl() }}">Sau ›</a>
            @else
                <span class="disabled">Sau ›</span>
            @endif
        </div>
        @endif
    </div>

</main>

{{-- ==================== PANEL CHI TIẾT ==================== --}}
<aside class="detail-panel" id="detailPanel">
    <div class="panel-header">
        <h2 id="panelTitle">Chi tiết hóa đơn</h2>
        <button class="close-btn" onclick="closePanel()">✕ Đóng</button>
    </div>
    <div class="panel-body" id="panelBody">
        <div class="empty-state">
            <div class="empty-icon">◻</div>
            <p>Chọn một hóa đơn để xem chi tiết.</p>
        </div>
    </div>
</aside>
</div>

{{-- ==================== CONFIRM DIALOG ==================== --}}
<div class="overlay" id="confirmOverlay">
    <div class="dialog">
        <h3>Xác nhận xóa</h3>
        <p id="confirmMsg">Bạn có chắc muốn xóa hóa đơn này không?<br>
           Toàn bộ chi tiết hóa đơn sẽ bị xóa theo.</p>
        <div class="dialog-actions">
            <button class="btn btn-ghost" onclick="closeDialog()">Hủy</button>
            <button class="btn btn-danger" id="confirmOkBtn">✕ Xóa</button>
        </div>
    </div>
</div>

{{-- ==================== TOAST ==================== --}}
<div class="toast" id="toast"></div>

{{-- ==================== JAVASCRIPT ==================== --}}
<script>
    const csrfToken  = document.querySelector('meta[name="csrf-token"]').content;
    const mainCol    = document.getElementById('mainCol');
    const panel      = document.getElementById('detailPanel');
    const panelTitle = document.getElementById('panelTitle');
    const panelBody  = document.getElementById('panelBody');
    let currentDeleteId = null;

    function fmtMoney(n) {
        return Number(n).toLocaleString('vi-VN') + 'đ';
    }
    function fmtDate(s) {
        if (!s) return '—';
        const d = new Date(s);
        return d.toLocaleDateString('vi-VN', {day:'2-digit',month:'2-digit',year:'numeric'})
              + ' ' + d.toLocaleTimeString('vi-VN', {hour:'2-digit',minute:'2-digit'});
    }

    function showToast(msg, type = 'success') {
        const t = document.getElementById('toast');
        t.textContent = msg;
        t.className = 'toast ' + type;
        t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), 3500);
    }

    function openPanel(id) {
        panelTitle.textContent = 'Hóa đơn HD-' + String(id).padStart(4, '0');
        panelBody.innerHTML = '<div class="panel-loading"><span class="spinner"></span> Đang tải...</div>';
        panel.classList.add('open');
        mainCol.classList.add('panel-open');

        document.querySelectorAll('tbody tr').forEach(r => r.classList.remove('selected-row'));
        const row = document.getElementById('row-' + id);
        if (row) row.classList.add('selected-row');

        fetch(`/hoa-don/${id}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(res => renderPanel(res.data))
        .catch(() => {
            panelBody.innerHTML = '<div class="empty-state"><p>Lỗi tải dữ liệu.</p></div>';
        });
    }

    function closePanel() {
        panel.classList.remove('open');
        mainCol.classList.remove('panel-open');
        document.querySelectorAll('tbody tr').forEach(r => r.classList.remove('selected-row'));
    }

    function renderPanel(hd) {
        const tt = hd.trang_thai;
        const ttColor = {
            PENDING:'badge-pending', CONFIRMED:'badge-confirmed',
            SHIPPING:'badge-shipping', DELIVERED:'badge-delivered',
            CANCELLED:'badge-cancelled'
        }[tt] || '';

        const items = (hd.chi_tiet_hoa_dons || []).map(ct => `
            <tr>
                <td>${ct.san_pham?.ten_san_pham ?? 'N/A'}</td>
                <td style="text-align:center">${ct.so_luong}</td>
                <td style="text-align:right;font-family:var(--font-mono)">${fmtMoney(ct.gia)}</td>
                <td style="text-align:right;font-family:var(--font-mono)">${fmtMoney(ct.so_luong * ct.gia)}</td>
            </tr>
        `).join('');

        panelBody.innerHTML = `
            <div class="panel-section">
                <p class="panel-section-title">Thông tin hóa đơn</p>
                <div class="info-row"><span class="lbl">Mã hóa đơn</span><span class="val">HD-${String(hd.ma_hoa_don).padStart(4,'0')}</span></div>
                <div class="info-row"><span class="lbl">Ngày đặt</span><span class="val">${fmtDate(hd.ngay_dat)}</span></div>
                <div class="info-row"><span class="lbl">Trạng thái</span><span class="val"><span class="badge ${ttColor}">${tt}</span></span></div>
                <div class="info-row"><span class="lbl">Thanh toán</span>
                    <span class="val"><span class="badge ${hd.trang_thai_thanh_toan === 'DA_THANH_TOAN' ? 'badge-paid' : 'badge-unpaid'}">
                        ${hd.trang_thai_thanh_toan === 'DA_THANH_TOAN' ? 'Đã thanh toán' : 'Chưa thanh toán'}
                    </span></span>
                </div>
                <div class="info-row"><span class="lbl">Phương thức</span><span class="val">${hd.phuong_thuc_thanh_toan}</span></div>
                <div class="info-row"><span class="lbl">Ngày giao</span><span class="val">${fmtDate(hd.ngay_giao)}</span></div>
            </div>

            <div class="panel-section">
                <p class="panel-section-title">Thông tin khách hàng & giao hàng</p>
                <div class="info-row"><span class="lbl">Khách hàng</span><span class="val">${hd.khach_hang?.ten_khach_hang ?? '—'}</span></div>
                <div class="info-row"><span class="lbl">SĐT</span><span class="val">${hd.so_dien_thoai ?? '—'}</span></div>
                <div class="info-row"><span class="lbl">Địa chỉ giao</span><span class="val" style="text-align:right;max-width:220px">${hd.dia_chi_giao ?? '—'}</span></div>
                <div class="info-row"><span class="lbl">Nhân viên giao</span><span class="val">${hd.nhan_vien_giao?.ten_nhan_vien ?? '—'}</span></div>
            </div>

            <div class="panel-section">
                <p class="panel-section-title">Chi tiết sản phẩm</p>
                ${items.length ? `
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th style="text-align:center">SL</th>
                            <th style="text-align:right">Đơn giá</th>
                            <th style="text-align:right">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>${items}</tbody>
                </table>
                <div class="items-total">
                    <span>TỔNG CỘNG</span>
                    <span>${fmtMoney(hd.tong_tien)}</span>
                </div>` : '<p style="font-size:0.78rem;color:var(--ink-soft)">Không có sản phẩm.</p>'}
            </div>
        `;
    }

    function confirmDelete(id, label) {
        currentDeleteId = id;
        document.getElementById('confirmMsg').innerHTML =
            `Bạn có chắc muốn xóa <strong>${label}</strong> không?<br>Toàn bộ chi tiết hóa đơn sẽ bị xóa theo.`;
        document.getElementById('confirmOverlay').classList.add('show');
    }
    function closeDialog() {
        document.getElementById('confirmOverlay').classList.remove('show');
        currentDeleteId = null;
    }

    document.getElementById('confirmOkBtn').addEventListener('click', function () {
        if (!currentDeleteId) return;
        this.textContent = '...';
        this.disabled = true;

        fetch(`/hoa-don/${currentDeleteId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(r => r.json())
        .then(res => {
            closeDialog();
            this.textContent = '✕ Xóa';
            this.disabled = false;

            if (res.success) {
                const row = document.getElementById('row-' + currentDeleteId);
                if (row) {
                    row.style.transition = 'opacity 0.3s';
                    row.style.opacity = '0';
                    setTimeout(() => row.remove(), 300);
                }
                if (panel.classList.contains('open')) closePanel();
                showToast(res.message, 'success');
            } else {
                showToast(res.message, 'error');
            }
        })
        .catch(() => {
            closeDialog();
            this.textContent = '✕ Xóa';
            this.disabled = false;
            showToast('Có lỗi xảy ra, vui lòng thử lại.', 'error');
        });
    });

    document.getElementById('confirmOverlay').addEventListener('click', function (e) {
        if (e.target === this) closeDialog();
    });

    document.getElementById('ngay_dat').addEventListener('change', function () {
        if (this.value) {
            document.getElementById('tu_ngay').value = '';
            document.getElementById('den_ngay').value = '';
        }
    });
    ['tu_ngay','den_ngay'].forEach(id => {
        document.getElementById(id).addEventListener('change', function () {
            if (this.value) document.getElementById('ngay_dat').value = '';
        });
    });
</script>
</body>
</html>