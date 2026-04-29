{{-- resources/views/DonHang.blade.php --}}
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/DonHang.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Quản Lý Đơn Hàng – Flower Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=IBM+Plex+Mono:wght@400;500&family=IBM+Plex+Sans:wght@300;400;500&display=swap" rel="stylesheet">
   
</head>
<body>

<header class="site-header">
    <h1>Flower Store</h1>
    <div class="nav-links">
        <a href="{{ route('hoa-don.index') }}">Hóa Đơn</a>
        <a href="{{ route('don-hang.index') }}" class="active">Đơn Hàng</a>
    </div>
</header>

<div class="page-wrap">
<main class="main-col">

    {{-- ================== BỘ LỌC ================== --}}
    <p class="section-label">[ 01 ] Bộ lọc & Tìm kiếm</p>
    <div class="filter-box">
        <form method="GET" action="{{ route('don-hang.index') }}" id="filterForm">
            <div class="filter-grid">

                {{-- Trạng thái thanh toán --}}
                <div class="filter-group">
                    <label>Thanh toán</label>
                    <select name="trang_thai_thanh_toan">
                        <option value="">— Tất cả —</option>
                        <option value="CHUA_THANH_TOAN" {{ request('trang_thai_thanh_toan') == 'CHUA_THANH_TOAN' ? 'selected' : '' }}>Chưa thanh toán</option>
                        <option value="DA_THANH_TOAN"   {{ request('trang_thai_thanh_toan') == 'DA_THANH_TOAN'   ? 'selected' : '' }}>Đã thanh toán</option>
                    </select>
                </div>

                {{-- Tổng tiền từ --}}
                <div class="filter-group">
                    <label>Tổng tiền từ (đ)</label>
                    <input type="number" name="tu_tien" placeholder="VD: 100000" value="{{ request('tu_tien') }}" min="0">
                </div>

                {{-- Tổng tiền đến --}}
                <div class="filter-group">
                    <label>Tổng tiền đến (đ)</label>
                    <input type="number" name="den_tien" placeholder="VD: 500000" value="{{ request('den_tien') }}" min="0">
                </div>

                {{-- Từ ngày --}}
                <div class="filter-group">
                    <label>Từ ngày</label>
                    <input type="date" name="tu_ngay" value="{{ request('tu_ngay') }}">
                </div>

                {{-- Đến ngày --}}
                <div class="filter-group">
                    <label>Đến ngày</label>
                    <input type="date" name="den_ngay" value="{{ request('den_ngay') }}">
                </div>

            </div>

            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">&#9906; Lọc kết quả</button>
                <a href="{{ route('don-hang.index') }}" class="btn btn-ghost">↺ Đặt lại</a>
                @if(request()->hasAny(['trang_thai_thanh_toan','tu_tien','den_tien','tu_ngay','den_ngay']))
                    <span style="font-family:var(--font-mono);font-size:0.65rem;color:var(--ink-soft);margin-left:auto;">
                        Đang lọc — {{ $donHangs->total() }} kết quả
                    </span>
                @endif
            </div>
        </form>
    </div>

    {{-- ================== BẢNG ĐƠN HÀNG ================== --}}
    <p class="section-label">[ 02 ] Danh sách đơn hàng chờ xử lý</p>
    <div class="table-box">
        <div class="table-meta">
            <span>Tổng cộng: <strong>{{ $donHangs->total() }}</strong> đơn hàng đang chờ</span>
            <span>Trang {{ $donHangs->currentPage() }} / {{ $donHangs->lastPage() }}</span>
        </div>

        <div class="tbl-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Mã ĐH</th>
                        <th>Khách hàng</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Thanh toán</th>
                        <th>Địa chỉ giao</th>
                        <th>SĐT</th>
                        <th>Chọn Shipper</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($donHangs as $index => $dh)
                    <tr id="row-{{ $dh->ma_hoa_don }}">
                        <td class="td-mono">{{ $donHangs->firstItem() + $index }}</td>
                        <td class="td-mono">HD-{{ str_pad($dh->ma_hoa_don, 4, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $dh->khachHang?->ten_khach_hang ?? '—' }}</td>
                        <td class="td-mono">{{ $dh->ngay_dat?->format('d/m/Y H:i') ?? '—' }}</td>
                        <td class="td-mono">{{ number_format($dh->tong_tien, 0, ',', '.') }}đ</td>
                        <td>
                            <span class="badge {{ $dh->trang_thai_thanh_toan === 'DA_THANH_TOAN' ? 'badge-paid' : 'badge-unpaid' }}">
                                {{ $dh->trang_thai_thanh_toan === 'DA_THANH_TOAN' ? 'Đã TT' : 'Chưa TT' }}
                            </span>
                        </td>
                        <td title="{{ $dh->dia_chi_giao }}">{{ Str::limit($dh->dia_chi_giao, 28) }}</td>
                        <td class="td-mono">{{ $dh->so_dien_thoai ?? '—' }}</td>
                        <td>
                            <select class="shipper-select" id="shipper-{{ $dh->ma_hoa_don }}" data-id="{{ $dh->ma_hoa_don }}">
                                <option value="">— Chọn shipper —</option>
                                @foreach($shippers as $sp)
                                    <option value="{{ $sp->ma_nhan_vien }}">{{ $sp->ten_nhan_vien }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <div class="td-actions">
                                <button class="btn btn-success btn-sm"
                                        onclick="confirmOrder({{ $dh->ma_hoa_don }})"
                                        title="Xác nhận đơn hàng (phải chọn shipper)">
                                    ✔ Xác nhận
                                </button>
                                <button class="btn btn-danger btn-sm"
                                        onclick="openCancelDialog({{ $dh->ma_hoa_don }}, 'HD-{{ str_pad($dh->ma_hoa_don, 4, '0', STR_PAD_LEFT) }}')"
                                        title="Từ chối đơn hàng">
                                    ✕ Từ chối
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10">
                            <div class="empty-state">
                                <div class="empty-icon">◻</div>
                                <p>Không có đơn hàng nào đang chờ xử lý.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($donHangs->hasPages())
        <div class="pagination-wrap">
            @if($donHangs->onFirstPage())
                <span class="disabled">‹ Trước</span>
            @else
                <a href="{{ $donHangs->previousPageUrl() }}">‹ Trước</a>
            @endif

            @foreach($donHangs->getUrlRange(1, $donHangs->lastPage()) as $page => $url)
                @if($page == $donHangs->currentPage())
                    <span class="current">{{ $page }}</span>
                @else
                    <a href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach

            @if($donHangs->hasMorePages())
                <a href="{{ $donHangs->nextPageUrl() }}">Sau ›</a>
            @else
                <span class="disabled">Sau ›</span>
            @endif
        </div>
        @endif
    </div>

</main>
</div>

{{-- ============ CONFIRM CANCEL DIALOG ============ --}}
<div class="overlay" id="cancelOverlay">
    <div class="dialog">
        <h3>Xác nhận từ chối</h3>
        <p id="cancelMsg">Bạn có chắc muốn từ chối đơn hàng này không?<br>Trạng thái sẽ chuyển sang <strong>CANCELLED</strong>.</p>
        <div class="dialog-actions">
            <button class="btn btn-ghost" onclick="closeCancelDialog()">Hủy</button>
            <button class="btn btn-danger" id="cancelOkBtn">✕ Từ chối</button>
        </div>
    </div>
</div>

{{-- ============ TOAST ============ --}}
<div class="toast" id="toast"></div>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    let currentCancelId = null;

    function showToast(msg, type = 'success') {
        const t = document.getElementById('toast');
        t.textContent = msg;
        t.className = 'toast ' + type;
        t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), 3500);
    }

    function removeRow(id) {
        const row = document.getElementById('row-' + id);
        if (row) {
            row.style.transition = 'opacity 0.35s';
            row.style.opacity = '0';
            setTimeout(() => row.remove(), 360);
        }
    }

    function confirmOrder(id) {
        const select = document.getElementById('shipper-' + id);
        const shipperId = select ? select.value : '';

        if (!shipperId) {
            showToast('Vui lòng chọn shipper trước khi xác nhận!', 'error');
            if (select) select.focus();
            return;
        }

        const btn = document.querySelector(`#row-${id} .btn-success`);
        if (btn) { btn.disabled = true; btn.textContent = '...'; }

        fetch(`/don-hang/${id}/confirm`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({ ma_nhan_vien_giao: shipperId })
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                removeRow(id);
                showToast(res.message, 'success');
            } else {
                showToast(res.message || 'Có lỗi xảy ra.', 'error');
                if (btn) { btn.disabled = false; btn.textContent = '✔ Xác nhận'; }
            }
        })
        .catch(() => {
            showToast('Có lỗi xảy ra, vui lòng thử lại.', 'error');
            if (btn) { btn.disabled = false; btn.textContent = '✔ Xác nhận'; }
        });
    }

    function openCancelDialog(id, label) {
        currentCancelId = id;
        document.getElementById('cancelMsg').innerHTML =
            `Bạn có chắc muốn từ chối <strong>${label}</strong>?<br>Trạng thái sẽ chuyển sang <strong>CANCELLED</strong>.`;
        document.getElementById('cancelOverlay').classList.add('show');
    }

    function closeCancelDialog() {
        document.getElementById('cancelOverlay').classList.remove('show');
        currentCancelId = null;
    }

    document.getElementById('cancelOkBtn').addEventListener('click', function () {
        if (!currentCancelId) return;
        this.disabled = true;
        this.textContent = '...';

        fetch(`/don-hang/${currentCancelId}/cancel`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(r => r.json())
        .then(res => {
            closeCancelDialog();
            this.disabled = false;
            this.textContent = '✕ Từ chối';

            if (res.success) {
                removeRow(currentCancelId);
                showToast(res.message, 'success');
            } else {
                showToast(res.message || 'Có lỗi xảy ra.', 'error');
            }
        })
        .catch(() => {
            closeCancelDialog();
            this.disabled = false;
            this.textContent = '✕ Từ chối';
            showToast('Có lỗi xảy ra, vui lòng thử lại.', 'error');
        });
    });

    document.getElementById('cancelOverlay').addEventListener('click', function (e) {
        if (e.target === this) closeCancelDialog();
    });
</script>
</body>
</html>