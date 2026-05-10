@extends('layouts.admin')

@section('admin_content')
<div id="ajax-content">
    <h4 class="mb-4" style="color: var(--rose-pink)">
        <i class="fa-solid fa-chart-pie me-2"></i>Báo cáo RoseShop hôm nay
        <small class="fs-6 text-muted ms-2">{{ \Carbon\Carbon::today()->format('d/m/Y') }}</small>
    </h4>

    {{-- Thẻ thống kê chính --}}
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="p-4 rounded-4 text-white" style="background: linear-gradient(135deg, #FF69B4, #FFB6C1)">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="small opacity-75 mb-1">Doanh thu hôm nay</div>
                        <div class="fs-4 fw-bold">{{ number_format($doanhThuHomNay, 0, ',', '.') }} đ</div>
                    </div>
                    <i class="fa-solid fa-sack-dollar fs-2 opacity-50"></i>
                </div>
                @if($phanTramDoanhThu >= 0)
                    <div class="small mt-2 opacity-90">
                        <i class="fa-solid fa-arrow-up"></i> +{{ $phanTramDoanhThu }}% so với hôm qua
                    </div>
                @else
                    <div class="small mt-2 opacity-90">
                        <i class="fa-solid fa-arrow-down"></i> {{ $phanTramDoanhThu }}% so với hôm qua
                    </div>
                @endif
            </div>
        </div>

        <div class="col-md-3">
            <div class="p-4 rounded-4 bg-white border">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="small text-muted mb-1">Đơn hàng hôm nay</div>
                        <div class="fs-4 fw-bold" style="color: var(--rose-pink)">{{ $donHangHomNay }} đơn</div>
                    </div>
                    <i class="fa-solid fa-clipboard-list fs-2" style="color: #FFB6C1"></i>
                </div>
                <div class="small mt-2 text-warning fw-semibold">
                    <i class="fa-solid fa-clock"></i> {{ $donHangChoXuLy }} đơn đang chờ xử lý
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="p-4 rounded-4 bg-white border">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="small text-muted mb-1">Sản phẩm sắp hết</div>
                        <div class="fs-4 fw-bold text-danger">{{ $sanPhamSapHetSo }} sản phẩm</div>
                    </div>
                    <i class="fa-solid fa-boxes-stacked fs-2 text-danger opacity-40"></i>
                </div>
                <div class="small mt-2 text-danger">
                    <i class="fa-solid fa-triangle-exclamation"></i> Tồn kho dưới 5 sản phẩm
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="p-4 rounded-4 bg-white border">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="small text-muted mb-1">Tổng khách hàng</div>
                        <div class="fs-4 fw-bold text-success">{{ number_format($tongKhachHang) }}</div>
                    </div>
                    <i class="fa-solid fa-users fs-2 text-success opacity-40"></i>
                </div>
                <div class="small mt-2 text-muted">
                    <i class="fa-solid fa-circle-info"></i> Tất cả thời gian
                </div>
            </div>
        </div>
    </div>

    {{-- Biểu đồ --}}
    <div class="row g-4 mb-4">
        <div class="col-md-8">
            <div class="p-4 rounded-4 bg-white border">
                <h6 class="mb-3 fw-bold" style="color: var(--rose-pink)">
                    <i class="fa-solid fa-chart-line me-2"></i>Doanh thu 7 ngày gần nhất
                </h6>
                <canvas id="chartDoanhThu" height="100"></canvas>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-4 rounded-4 bg-white border h-100">
                <h6 class="mb-3 fw-bold" style="color: var(--rose-pink)">
                    <i class="fa-solid fa-chart-pie me-2"></i>Trạng thái đơn hàng
                </h6>
                <canvas id="chartTrangThai"></canvas>
                <div class="mt-3 small">
                    @php
                        $labelMap = [
                            'PENDING'   => ['Chờ xử lý', 'text-warning'],
                            'CONFIRMED' => ['Đã xác nhận', 'text-info'],
                            'DELIVERED' => ['Đã giao', 'text-success'],
                            'CANCELLED' => ['Đã hủy', 'text-danger'],
                        ];
                    @endphp
                    @foreach($trangThaiDonHang as $status => $count)
                        @php $lbl = $labelMap[$status] ?? [$status, 'text-secondary']; @endphp
                        <div class="d-flex justify-content-between">
                            <span class="{{ $lbl[1] }} fw-semibold">{{ $lbl[0] }}</span>
                            <span>{{ $count }} đơn</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Top sản phẩm --}}
    @if($topSanPham->count() > 0)
    <div class="p-4 rounded-4 bg-white border">
        <h6 class="mb-3 fw-bold" style="color: var(--rose-pink)">
            <i class="fa-solid fa-trophy me-2"></i>Top 5 sản phẩm bán chạy
        </h6>
        <div class="table-responsive">
            <table class="table table-sm align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Sản phẩm</th>
                        <th class="text-center">Đã bán</th>
                        <th class="text-end">Doanh thu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topSanPham as $i => $sp)
                    <tr>
                        <td>
                            @if($i === 0) <span class="badge" style="background:gold; color:#333">🥇</span>
                            @elseif($i === 1) <span class="badge bg-secondary">🥈</span>
                            @elseif($i === 2) <span class="badge" style="background:#cd7f32">🥉</span>
                            @else <span class="text-muted">{{ $i + 1 }}</span>
                            @endif
                        </td>
                        <td>{{ $sp->ten_san_pham }}</td>
                        <td class="text-center fw-semibold" style="color: var(--rose-pink)">{{ number_format($sp->tong_ban) }}</td>
                        <td class="text-end text-success fw-semibold">{{ number_format($sp->tong_doanh_thu, 0, ',', '.') }} đ</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

@php
    $statusLabels = [];
    $statusData   = [];
    $statusColors = ['PENDING' => '#FFC107', 'CONFIRMED' => '#0DCAF0', 'DELIVERED' => '#198754', 'CANCELLED' => '#DC3545'];
    $statusColorArr = [];
    foreach ($trangThaiDonHang as $k => $v) {
        $statusLabels[] = ['PENDING'=>'Chờ xử lý','CONFIRMED'=>'Đã xác nhận','DELIVERED'=>'Đã giao','CANCELLED'=>'Đã hủy'][$k] ?? $k;
        $statusData[]   = $v;
        $statusColorArr[] = $statusColors[$k] ?? '#aaa';
    }
@endphp

<script>
(function () {
    new Chart(document.getElementById('chartDoanhThu'), {
        type: 'line',
        data: {
            labels: @json($doanhThu7Ngay['labels']),
            datasets: [{
                label: 'Doanh thu (đ)',
                data: @json($doanhThu7Ngay['data']),
                borderColor: '#FF69B4',
                backgroundColor: 'rgba(255,105,180,0.1)',
                borderWidth: 2,
                pointBackgroundColor: '#FF69B4',
                tension: 0.4,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: v => new Intl.NumberFormat('vi-VN').format(v) + ' đ'
                    }
                }
            }
        }
    });

    new Chart(document.getElementById('chartTrangThai'), {
        type: 'doughnut',
        data: {
            labels: @json($statusLabels),
            datasets: [{
                data: @json($statusData),
                backgroundColor: @json($statusColorArr),
                borderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 12 } } }
            }
        }
    });
})();
</script>
@endsection
