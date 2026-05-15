<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/Shipper/ShipperDashboard.css') }}">
    <title>Shipper Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;600;700&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<div class="topbar">
    <span class="topbar-brand">🌸 FlowerStore · Shipper</span>
    <div style="display: flex; align-items: center; gap: 20px;">
        <span class="topbar-status">
            <span class="dot-green"></span>
            Đang hoạt động
        </span>
        {{-- Nút đăng xuất --}}
        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
            @csrf
            <button type="submit" style="background:none; border:none; color:#6c757d; cursor:pointer; font-size:14px; display:flex; align-items:center; gap:6px;">
                <i class="fas fa-sign-out-alt"></i> Đăng xuất
            </button>
        </form>
    </div>
</div>

<div class="layout">

    {{-- LEFT PANEL: Profile + Tiền + Lịch sử --}}
    <aside class="left-panel">
        {{-- ... giữ nguyên nội dung phần left panel ... --}}
        <div class="profile-header">
            <div class="profile-avatar">
                {{ mb_strtoupper(mb_substr($shipper->ten_nhan_vien, 0, 1)) }}
            </div>
            <div class="profile-name">{{ $shipper->ten_nhan_vien }}</div>
            <div class="profile-id">SHIPPER · #{{ str_pad($shipper->ma_nhan_vien, 4, '0', STR_PAD_LEFT) }}</div>
            <div class="profile-contacts">
                @if($shipper->so_dien_thoai)
                    <span><i class="fas fa-phone"></i> {{ $shipper->so_dien_thoai }}</span>
                @endif
                @if($shipper->email)
                    <span><i class="fas fa-envelope"></i> {{ $shipper->email }}</span>
                @endif
            </div>
        </div>

        <div class="info-section">
            <div class="section-label">Thông tin cá nhân</div>
            <div class="info-grid">
                <div class="info-cell">
                    <div class="info-lbl">Chức vụ</div>
                    <div class="info-val">{{ $shipper->chuc_vu }}</div>
                </div>
                <div class="info-cell">
                    <div class="info-lbl">Lương cơ bản</div>
                    <div class="info-val">{{ number_format($shipper->luong, 0, ',', '.') }} đ</div>
                </div>
                @if($shipper->cong_viec)
                <div class="info-cell" style="grid-column:span 2;border-right:none">
                    <div class="info-lbl">Công việc</div>
                    <div class="info-val">{{ $shipper->cong_viec }}</div>
                </div>
                @endif
            </div>
        </div>

        <div class="money-section">
            <div class="section-label">
                Tiền cần nộp công ty
                @if($soDonConNo > 0)
                    &nbsp;· {{ $soDonConNo }} đơn chưa nộp
                @endif
            </div>
            <div class="money-block">
                <div class="money-label">Tổng cần nộp</div>
                <div class="money-amount {{ $tienCanTra == 0 ? 'zero' : '' }}">
                    {{ number_format($tienCanTra, 0, ',', '.') }}&thinsp;đ
                </div>
                <div class="money-sub">
                    @if($soDonConNo > 0)
                        Từ {{ $soDonConNo }} đơn COD đã giao, chưa nộp tiền
                    @else
                        Tất cả tiền COD đã được nộp ✓
                    @endif
                </div>

                @if($tienCanTra > 0)
                    <div class="money-alert">
                        <i class="fas fa-exclamation-triangle" style="margin-top:2px;flex-shrink:0"></i>
                        <span>Bạn đang giữ <strong>{{ number_format($tienCanTra, 0, ',', '.') }} đ</strong>
                        tiền COD. Vui lòng nộp lại công ty sớm nhất.</span>
                    </div>
                @else
                    <div class="money-alert ok">
                        <i class="fas fa-check-circle" style="margin-top:2px;flex-shrink:0"></i>
                        <span>Không có khoản tiền nào cần nộp. Cảm ơn bạn!</span>
                    </div>
                @endif

                <div class="money-stats">
                    <div class="money-stat">
                        <div class="money-stat-lbl">Tổng đã giao</div>
                        <div class="money-stat-val">{{ number_format($tongDoanhThu, 0, ',', '.') }}&thinsp;đ</div>
                    </div>
                    <div class="money-stat" style="padding-left:18px">
                        <div class="money-stat-lbl">Đơn chờ giao</div>
                        <div class="money-stat-val">{{ $donHangCanShip->count() }} đơn</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="history-section">
            <div class="section-label">
                Lịch sử đơn hàng
                &nbsp;· {{ $lichSuDonHang->total() }} đơn
            </div>
            <div class="history-scroll">
                @forelse($lichSuDonHang as $dh)
                    <div class="history-item">
                        <div>
                            <div class="history-code">#HD-{{ str_pad($dh->ma_hoa_don, 4, '0', STR_PAD_LEFT) }}</div>
                            <div class="history-date">{{ optional($dh->ngay_giao)->format('d/m/Y H:i') ?? '—' }}</div>
                        </div>
                        <div>
                            <div class="history-customer">{{ $dh->khachHang->ten_khach_hang ?? 'Khách vãng lai' }}</div>
                            <div class="history-date">{{ $dh->phuong_thuc_thanh_toan }}</div>
                        </div>
                        <div class="history-right">
                            <div class="history-price {{ $dh->trang_thai_thanh_toan === 'DA_THANH_TOAN' && $dh->phuong_thuc_thanh_toan === 'COD' ? 'cod-pending' : 'paid' }}">
                                {{ number_format($dh->tong_tien, 0, ',', '.') }}&thinsp;đ
                            </div>
                            @if($dh->trang_thai_thanh_toan === 'DA_NOP')
                                     <div class="history-tag done">Đã nộp</div>
                        @elseif($dh->trang_thai_thanh_toan === 'DA_THANH_TOAN' && $dh->phuong_thuc_thanh_toan === 'COD')
                                 <div class="history-tag unpaid">Chưa nộp</div>
                                    @else
                             <div class="history-tag done">Đã nộp</div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-clipboard-list"></i>
                        Chưa có đơn hàng nào.
                    </div>
                @endforelse

                @if($lichSuDonHang->hasPages())
                    <div style="padding:8px 16px">
                        {{ $lichSuDonHang->links() }}
                    </div>
                @endif
            </div>
        </div>
    </aside>

    {{-- RIGHT PANEL: Đơn hàng cần giao --}}
    <main class="right-panel">
        <div class="right-header">
            <div class="right-title">
                <i class="fas fa-motorcycle"></i>
                Đơn hàng cần giao
                @if($donHangCanShip->count() > 0)
                    <span class="count-badge">{{ $donHangCanShip->count() }}</span>
                @endif
            </div>
        </div>

        <div class="orders-scroll">
            @forelse($donHangCanShip as $dh)
                <div class="order-card" id="order-row-{{ $dh->ma_hoa_don }}">
                    <div class="order-left">
                        <div class="order-num">#HD-{{ str_pad($dh->ma_hoa_don, 4, '0', STR_PAD_LEFT) }}</div>
                        <span class="badge {{ strtolower($dh->trang_thai) == 'confirmed' ? 'badge-wait' : 'badge-ship' }}"
                              id="badge-{{ $dh->ma_hoa_don }}">
                            {{ $dh->trang_thai === 'CONFIRMED' ? 'Chờ lấy' : 'Đang giao' }}
                        </span>
                        <span class="badge {{ $dh->phuong_thuc_thanh_toan === 'COD' ? 'badge-cod' : 'badge-bank' }}">
                            {{ $dh->phuong_thuc_thanh_toan }}
                        </span>
                    </div>

                    <div class="order-middle">
                        <div class="order-customer">
                            {{ $dh->khachHang->ten_khach_hang ?? 'Khách vãng lai' }}
                        </div>
                        <div class="order-detail">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ $dh->dia_chi_giao }}</span>
                        </div>
                        @if($dh->so_dien_thoai)
                        <div class="order-detail">
                            <i class="fas fa-phone"></i>
                            <span>{{ $dh->so_dien_thoai }}</span>
                        </div>
                        @endif
                    </div>

                    <div class="order-right">
    <div class="order-price">{{ number_format($dh->tong_tien, 0, ',', '.') }}&thinsp;đ</div>
    <a href="{{ route('shipper.don-hang.chi-tiet', $dh->ma_hoa_don) }}" 
       class="btn-action btn-detail"
       style="background:#6c757d; color:white; text-decoration:none; display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:40px; font-size:13px; font-weight:500;">
        <i class="fas fa-info-circle"></i> Chi tiết
    </a>
</div>
                </div>
            @empty
                <div class="empty-state" style="padding:80px 28px">
                    <i class="fas fa-box-open"></i>
                    Không có đơn nào cần giao.
                </div>
            @endforelse
        </div>
    </main>

</div>

<div id="toast"></div>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
function showToast(msg, type = '') {
    const el = document.getElementById('toast');
    el.textContent = msg;
    el.className = 'show ' + type;
    setTimeout(() => { el.className = ''; }, 3000);
}

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

function updateStatus(id, currentStatus) {
    const label = currentStatus === 'CONFIRMED' ? 'bắt đầu lấy hàng' : 'hoàn thành đơn';
    if (!confirm('Xác nhận ' + label + ' #HD-' + String(id).padStart(4,'0') + '?')) return;

    fetch(`/shipper/don-hang/${id}/cap-nhat`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
        },
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            if (data.new_status === 'DELIVERED') {
                const row = document.getElementById('order-row-' + id);
                if (row) {
                    row.style.transition = 'opacity .4s';
                    row.style.opacity = '0';
                    setTimeout(() => row.remove(), 400);
                }
                setTimeout(() => location.reload(), 1500);
            } else {
                const badge = document.getElementById('badge-' + id);
                if (badge) {
                    badge.className = 'badge badge-ship';
                    badge.textContent = 'Đang giao';
                }
                const row = document.getElementById('order-row-' + id);
                if (row) {
                    const btn = row.querySelector('.btn-action');
                    if (btn) {
                        btn.className = 'btn-action btn-done';
                        btn.innerHTML = '<i class="fas fa-check"></i> Đã giao';
                        btn.setAttribute('onclick', `updateStatus(${id}, 'SHIPPING')`);
                    }
                }
            }
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(() => showToast('Có lỗi xảy ra, vui lòng thử lại.', 'error'));
}
</script>
</body>
</html>