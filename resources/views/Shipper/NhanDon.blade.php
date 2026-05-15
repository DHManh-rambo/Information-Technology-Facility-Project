@extends('Shipper.layouts.shipper')

@section('title', 'Chi tiết đơn #HD-' . str_pad($hoaDon->ma_hoa_don, 4, '0', STR_PAD_LEFT))

@section('extra-styles')
 <link rel="stylesheet" href="{{ asset('css/Shipper/ShipperDashboard.css') }}">
<style>
    .detail-wrap {
        max-width: 760px;
        margin: 0 auto;
        padding: 32px 20px 60px;
    }

    .back-bar {
        display: flex; align-items: center; gap: 12px;
        margin-bottom: 24px;
    }
    .back-btn {
        display: inline-flex; align-items: center; gap: 7px;
        color: var(--text-muted); font-size: 13px; font-weight: 500;
        text-decoration: none; padding: 7px 14px;
        border-radius: var(--radius-sm);
        border: 1.5px solid var(--border);
        background: var(--card);
        transition: border-color .15s, color .15s, box-shadow .15s;
    }
    .back-btn:hover {
        color: var(--text-main);
        border-color: #c8cfd8;
        box-shadow: var(--shadow-sm);
    }
    .order-id-title {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 18px; font-weight: 700;
        color: var(--text-main);
        letter-spacing: -.5px;
    }

    .status-pill {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 4px 14px; border-radius: 40px;
        font-size: 12px; font-weight: 600; letter-spacing: .3px;
    }
    .status-pill.confirmed { background: #fff7ed; color: #c05621; border: 1.5px solid #fed7aa; }
    .status-pill.shipping  { background: #eff6ff; color: #1d4ed8; border: 1.5px solid #bfdbfe; }
    .status-pill.delivered { background: #f0fdf4; color: #15803d; border: 1.5px solid #bbf7d0; }

    .card-section {
        background: var(--card);
        border-radius: var(--radius-lg);
        border: 1.5px solid var(--border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        margin-bottom: 18px;
    }
    .card-header {
        display: flex; align-items: center; gap: 10px;
        padding: 16px 22px;
        border-bottom: 1.5px solid var(--border);
        font-size: 13px; font-weight: 600; color: var(--text-muted);
        letter-spacing: .4px; text-transform: uppercase;
    }
    .card-header i { color: var(--brand); font-size: 14px; }
    .card-body { padding: 20px 22px; }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px 24px;
    }
    .info-item {}
    .info-item.full { grid-column: span 2; }
    .info-lbl {
        font-size: 11px; font-weight: 600; letter-spacing: .5px;
        text-transform: uppercase; color: var(--text-muted);
        margin-bottom: 4px;
    }
    .info-val {
        font-size: 14px; font-weight: 500; color: var(--text-main);
        line-height: 1.5;
    }
    .info-val a {
        color: var(--brand); text-decoration: none;
    }
    .info-val a:hover { text-decoration: underline; }

    .product-table {
        width: 100%; border-collapse: collapse;
    }
    .product-table th {
        font-size: 11px; font-weight: 600; letter-spacing: .4px;
        text-transform: uppercase; color: var(--text-muted);
        padding: 0 10px 12px;
        text-align: left;
    }
    .product-table th:last-child { text-align: right; }
    .product-table td {
        padding: 11px 10px;
        border-top: 1px solid var(--border);
        font-size: 14px;
        vertical-align: middle;
    }
    .product-table td:last-child { text-align: right; font-weight: 600; }
    .product-name { font-weight: 500; color: var(--text-main); }
    .product-qty  { color: var(--text-muted); font-size: 13px; }
    .product-price { color: var(--text-muted); font-size: 13px; }

    .totals-block {
        border-top: 2px solid var(--border);
        margin-top: 8px;
        padding-top: 14px;
    }
    .totals-row {
        display: flex; justify-content: space-between;
        align-items: center;
        padding: 5px 10px;
        font-size: 14px;
    }
    .totals-row.main {
        font-size: 17px; font-weight: 700;
        color: var(--text-main);
        padding-top: 10px;
    }
    .totals-lbl { color: var(--text-muted); font-weight: 500; }
    .totals-val { font-weight: 600; }

    .payment-row {
        display: flex; align-items: center; justify-content: space-between;
        gap: 14px;
    }
    .payment-method {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 7px 16px; border-radius: 40px;
        font-size: 13px; font-weight: 600;
    }
    .payment-method.cod  { background: #fff7ed; color: #c05621; border: 1.5px solid #fed7aa; }
    .payment-method.bank { background: #eff6ff; color: #1d4ed8; border: 1.5px solid #bfdbfe; }

    .collect-block {
        text-align: right;
    }
    .collect-lbl {
        font-size: 11px; font-weight: 600; letter-spacing: .4px;
        text-transform: uppercase; color: var(--text-muted);
        margin-bottom: 4px;
    }
    .collect-amount {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 22px; font-weight: 700;
    }
    .collect-amount.need-collect { color: var(--brand); }
    .collect-amount.no-collect   { color: var(--green); }

    /* ── Action section ───────────────────────────────────────── */
    .action-section {
        background: var(--card);
        border-radius: var(--radius-lg);
        border: 1.5px solid var(--border);
        box-shadow: var(--shadow-sm);
        padding: 24px 22px;
        margin-bottom: 18px;
    }
    .action-label {
        font-size: 11px; font-weight: 600; letter-spacing: .5px;
        text-transform: uppercase; color: var(--text-muted);
        margin-bottom: 16px;
    }

    .progress-steps {
        display: flex; align-items: center;
        margin-bottom: 24px;
        position: relative;
    }
    .step {
        display: flex; flex-direction: column; align-items: center;
        flex: 1; position: relative; z-index: 1;
    }
    .step-dot {
        width: 36px; height: 36px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 13px; font-weight: 700;
        border: 2px solid var(--border);
        background: var(--surface); color: var(--text-muted);
        transition: all .3s;
        margin-bottom: 8px;
    }
    .step-dot.done  { background: var(--green);  border-color: var(--green);  color: #fff; }
    .step-dot.active{ background: var(--brand);  border-color: var(--brand);  color: #fff; }
    .step-lbl {
        font-size: 11px; font-weight: 600; text-align: center;
        color: var(--text-muted);
        line-height: 1.3;
    }
    .step-lbl.done   { color: var(--green); }
    .step-lbl.active { color: var(--brand); }
    .step-line {
        flex: 1; height: 2px;
        background: var(--border);
        position: relative; z-index: 0; margin-top: -28px;
    }
    .step-line.done { background: var(--green); }

    .btn-action-main {
        width: 100%; padding: 16px;
        border: none; border-radius: var(--radius-md);
        font-size: 15px; font-weight: 700;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 10px;
        transition: transform .15s, box-shadow .15s, opacity .15s;
        letter-spacing: .1px;
    }
    .btn-action-main:hover:not(:disabled) {
        transform: translateY(-1px);
        box-shadow: 0 8px 24px rgba(0,0,0,.12);
    }
    .btn-action-main:active:not(:disabled) { transform: translateY(0); }
    .btn-action-main:disabled { opacity: .5; cursor: not-allowed; }

    .btn-confirm  {
        background: linear-gradient(135deg, #f97316, #ea580c);
        color: #fff;
        box-shadow: 0 4px 16px rgba(249,115,22,.35);
    }
    .btn-shipping {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: #fff;
        box-shadow: 0 4px 16px rgba(59,130,246,.35);
    }
    .btn-done {
        background: linear-gradient(135deg, #22c55e, #15803d);
        color: #fff;
        box-shadow: 0 4px 16px rgba(34,197,94,.30);
        cursor: default;
    }

    .action-hint {
        font-size: 12px; color: var(--text-muted);
        text-align: center; margin-top: 10px;
        line-height: 1.5;
    }

    
    .spinner {
        width: 18px; height: 18px;
        border: 2px solid rgba(255,255,255,.4);
        border-top-color: #fff;
        border-radius: 50%;
        animation: spin .6s linear infinite;
        display: none;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

   
    @media (max-width: 600px) {
        .info-grid { grid-template-columns: 1fr; }
        .info-item.full { grid-column: span 1; }
        .payment-row { flex-direction: column; align-items: flex-start; }
        .collect-block { text-align: left; }
    }
</style>
@endsection

@section('content')
<div class="detail-wrap">

    {{-- ── Back bar ──────────────────────────────────────────── --}}
    <div class="back-bar">
        <a href="{{ route('shipper.dashboard') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i> Dashboard
        </a>
        <span class="order-id-title">
            #HD-{{ str_pad($hoaDon->ma_hoa_don, 4, '0', STR_PAD_LEFT) }}
        </span>
        @php
            $st = strtolower($hoaDon->trang_thai);
            $stLabel = match($hoaDon->trang_thai) {
                'CONFIRMED' => 'Chờ lấy hàng',
                'SHIPPING'  => 'Đang giao',
                'DELIVERED' => 'Đã giao',
                default     => $hoaDon->trang_thai,
            };
        @endphp
        <span class="status-pill {{ $st === 'confirmed' ? 'confirmed' : ($st === 'shipping' ? 'shipping' : 'delivered') }}"
              id="status-pill">
            <i class="fas {{ $st === 'confirmed' ? 'fa-clock' : ($st === 'shipping' ? 'fa-motorcycle' : 'fa-check-circle') }}"></i>
            {{ $stLabel }}
        </span>
    </div>

    {{-- ── Action / Progress ─────────────────────────────────── --}}
    <div class="action-section">
        <div class="action-label"><i class="fas fa-route" style="color:var(--brand)"></i>&nbsp; Tiến trình giao hàng</div>

        {{-- Progress bar --}}
        <div class="progress-steps">
            <div class="step">
                <div class="step-dot done"><i class="fas fa-check"></i></div>
                <div class="step-lbl done">Xác nhận<br>đơn hàng</div>
            </div>
            <div class="step-line {{ in_array($hoaDon->trang_thai, ['SHIPPING','DELIVERED']) ? 'done' : '' }}"></div>
            <div class="step">
                <div class="step-dot {{ $hoaDon->trang_thai === 'SHIPPING' ? 'active' : ($hoaDon->trang_thai === 'DELIVERED' ? 'done' : '') }}">
                    @if($hoaDon->trang_thai === 'DELIVERED')
                        <i class="fas fa-check"></i>
                    @else
                        <i class="fas fa-motorcycle"></i>
                    @endif
                </div>
                <div class="step-lbl {{ $hoaDon->trang_thai === 'SHIPPING' ? 'active' : ($hoaDon->trang_thai === 'DELIVERED' ? 'done' : '') }}">
                    Đang<br>vận chuyển
                </div>
            </div>
            <div class="step-line {{ $hoaDon->trang_thai === 'DELIVERED' ? 'done' : '' }}"></div>
            <div class="step">
                <div class="step-dot {{ $hoaDon->trang_thai === 'DELIVERED' ? 'done' : '' }}">
                    @if($hoaDon->trang_thai === 'DELIVERED')
                        <i class="fas fa-check"></i>
                    @else
                        <i class="fas fa-flag-checkered"></i>
                    @endif
                </div>
                <div class="step-lbl {{ $hoaDon->trang_thai === 'DELIVERED' ? 'done' : '' }}">
                    Hoàn thành<br>giao hàng
                </div>
            </div>
        </div>

        {{-- Action button --}}
        @if($hoaDon->trang_thai === 'CONFIRMED')
            <button class="btn-action-main btn-confirm" id="action-btn"
                    onclick="handleUpdateStatus({{ $hoaDon->ma_hoa_don }}, 'CONFIRMED')">
                <span class="spinner" id="btn-spinner"></span>
                <i class="fas fa-truck-pickup" id="btn-icon"></i>
                <span id="btn-text">Nhận hàng &amp; bắt đầu giao</span>
            </button>
            <p class="action-hint">
                <i class="fas fa-info-circle"></i>
                Nhấn để xác nhận bạn đã lấy hàng tại kho và bắt đầu giao đến khách.
            </p>

        @elseif($hoaDon->trang_thai === 'SHIPPING')
            <button class="btn-action-main btn-shipping" id="action-btn"
                    onclick="handleUpdateStatus({{ $hoaDon->ma_hoa_don }}, 'SHIPPING')">
                <span class="spinner" id="btn-spinner"></span>
                <i class="fas fa-map-marker-alt" id="btn-icon"></i>
                <span id="btn-text">Đã đến điểm giao — Hoàn thành đơn</span>
            </button>
            <p class="action-hint">
                <i class="fas fa-info-circle"></i>
                Nhấn khi đã giao hàng thành công cho khách hàng.
                @if($hoaDon->phuong_thuc_thanh_toan === 'COD')
                    Nhớ thu <strong>{{ number_format($hoaDon->tong_tien, 0, ',', '.') }} đ</strong> tiền mặt.
                @endif
            </p>

        @else
            <button class="btn-action-main btn-done" disabled>
                <i class="fas fa-check-circle"></i>
                <span>Đơn hàng đã giao thành công</span>
            </button>
            <p class="action-hint">
                Giao lúc {{ optional($hoaDon->ngay_giao)->format('H:i, d/m/Y') ?? '—' }}
            </p>
        @endif
    </div>

    {{-- ── Thông tin khách hàng ──────────────────────────────── --}}
    <div class="card-section">
        <div class="card-header">
            <i class="fas fa-user-circle"></i> Thông tin khách hàng
        </div>
        <div class="card-body">
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-lbl">Họ tên</div>
                    <div class="info-val">{{ $hoaDon->khachHang->ten_khach_hang ?? 'Khách vãng lai' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-lbl">Số điện thoại</div>
                    <div class="info-val">
                        <a href="tel:{{ $hoaDon->so_dien_thoai }}">
                            <i class="fas fa-phone" style="font-size:12px"></i>
                            {{ $hoaDon->so_dien_thoai ?: '—' }}
                        </a>
                    </div>
                </div>
                <div class="info-item full">
                    <div class="info-lbl">Địa chỉ giao hàng</div>
                    <div class="info-val">
                        <i class="fas fa-map-marker-alt" style="color:var(--brand); font-size:12px; margin-right:5px"></i>
                        {{ $hoaDon->dia_chi_giao ?: '—' }}
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-lbl">Ngày đặt</div>
                    <div class="info-val">{{ optional($hoaDon->ngay_dat)->format('d/m/Y H:i') ?? '—' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-lbl">Mã hóa đơn</div>
                    <div class="info-val" style="font-family:'IBM Plex Mono',monospace">
                        #HD-{{ str_pad($hoaDon->ma_hoa_don, 4, '0', STR_PAD_LEFT) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Chi tiết sản phẩm ─────────────────────────────────── --}}
    <div class="card-section">
        <div class="card-header">
            <i class="fas fa-box-open"></i> Chi tiết sản phẩm
        </div>
        <div class="card-body" style="padding-bottom:8px">
            <table class="product-table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th style="text-align:center">SL</th>
                        <th style="text-align:right">Đơn giá</th>
                        <th style="text-align:right">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hoaDon->chiTietHoaDon as $item)
                        <tr>
                            <td>
                                <div class="product-name">
                                    {{ $item->sanPham->ten_san_pham ?? 'Sản phẩm #' . $item->ma_san_pham }}
                                </div>
                            </td>
                            <td style="text-align:center">
                                <span class="product-qty">× {{ $item->so_luong }}</span>
                            </td>
                            <td style="text-align:right">
                                <span class="product-price">
                                    {{ number_format($item->gia_ban_snapshot, 0, ',', '.') }} đ
                                </span>
                            </td>
                            <td>
                                {{ number_format($item->gia_ban_snapshot * $item->so_luong, 0, ',', '.') }} đ
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="color:var(--text-muted); text-align:center; padding:20px">
                                Không có thông tin chi tiết sản phẩm.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="totals-block">
                <div class="totals-row">
                    <span class="totals-lbl">Tạm tính</span>
                    <span class="totals-val">{{ number_format($hoaDon->tong_tien, 0, ',', '.') }} đ</span>
                </div>
                <div class="totals-row">
                    <span class="totals-lbl">Phí vận chuyển</span>
                    <span class="totals-val" style="color:var(--green)">Miễn phí</span>
                </div>
                <div class="totals-row main">
                    <span>Tổng cộng</span>
                    <span style="color:var(--brand)">{{ number_format($hoaDon->tong_tien, 0, ',', '.') }} đ</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Thanh toán ────────────────────────────────────────── --}}
    <div class="card-section">
        <div class="card-header">
            <i class="fas fa-wallet"></i> Thanh toán
        </div>
        <div class="card-body">
            <div class="payment-row">
                <div>
                    <div class="info-lbl" style="margin-bottom:8px">Phương thức</div>
                    @if($hoaDon->phuong_thuc_thanh_toan === 'COD')
                        <span class="payment-method cod">
                            <i class="fas fa-money-bill-wave"></i> Tiền mặt (COD)
                        </span>
                    @else
                        <span class="payment-method bank">
                            <i class="fas fa-university"></i> Chuyển khoản
                        </span>
                    @endif
                </div>

                <div class="collect-block">
                    <div class="collect-lbl">Số tiền cần thu</div>
                    @php
                        $canThu = ($hoaDon->trang_thai_thanh_toan === 'CHUA_THANH_TOAN'
                                   && $hoaDon->phuong_thuc_thanh_toan === 'COD')
                                  ? $hoaDon->tong_tien
                                  : 0;
                    @endphp
                    <div class="collect-amount {{ $canThu > 0 ? 'need-collect' : 'no-collect' }}">
                        {{ $canThu > 0 ? number_format($canThu, 0, ',', '.') . ' đ' : '0 đ' }}
                    </div>
                    <div style="font-size:11px; color:var(--text-muted); margin-top:4px">
                        @if($canThu > 0)
                            <i class="fas fa-exclamation-circle" style="color:var(--brand)"></i>
                            Thu tiền mặt khi giao hàng
                        @else
                            <i class="fas fa-check-circle" style="color:var(--green)"></i>
                            Đã thanh toán — không cần thu
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
function handleUpdateStatus(id, currentStatus) {
    const actionMap = {
        'CONFIRMED': { label: 'nhận hàng và bắt đầu giao', next: 'SHIPPING' },
        'SHIPPING':  { label: 'hoàn thành đơn hàng này', next: 'DELIVERED'  },
    };
    const action = actionMap[currentStatus];
    if (!action) return;

    const code = '#HD-' + String(id).padStart(4, '0');
    if (!confirm(`Xác nhận ${action.label} cho đơn ${code}?`)) return;

    const btn = document.getElementById('action-btn');
    const spinner = document.getElementById('btn-spinner');
    const icon    = document.getElementById('btn-icon');
    const text    = document.getElementById('btn-text');

    if (btn) btn.disabled = true;
    if (spinner) spinner.style.display = 'inline-block';
    if (icon) icon.style.display = 'none';
    if (text) text.textContent = 'Đang xử lý…';

    fetch(`/shipper/don-hang/${id}/cap-nhat`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': _csrfToken,
        },
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');

            if (data.new_status === 'DELIVERED') {
                setTimeout(() => {
                    window.location.href = '{{ route("shipper.dashboard") }}';
                }, 1500);
            } else {
                setTimeout(() => location.reload(), 800);
            }
        } else {
            showToast(data.message, 'error');
            if (btn) btn.disabled = false;
            if (spinner) spinner.style.display = 'none';
            if (icon) icon.style.display = '';
            if (text) {
                text.textContent = currentStatus === 'CONFIRMED'
                    ? 'Nhận hàng & bắt đầu giao'
                    : 'Đã đến điểm giao — Hoàn thành đơn';
            }
        }
    })
    .catch(() => {
        showToast('Có lỗi xảy ra, vui lòng thử lại.', 'error');
        if (btn) btn.disabled = false;
        if (spinner) spinner.style.display = 'none';
        if (icon) icon.style.display = '';
    });
}
</script>
@endsection