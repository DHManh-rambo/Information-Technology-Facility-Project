<div class="grid-4">
    <div class="stat-card">
        <div class="stat-label">📦 Tổng đơn hàng</div>
        <div class="stat-value">3,849</div>
        <div class="stat-change">+12% so với tuần trước</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">✅ Hoàn thành</div>
        <div class="stat-value">3,425</div>
        <div class="stat-change">88.9%</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">⏳ Đang xử lý</div>
        <div class="stat-value">236</div>
        <div class="stat-change">6.1%</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">❌ Hủy</div>
        <div class="stat-value">188</div>
        <div class="stat-change">4.9%</div>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        <div class="card-title">📊 Trạng thái đơn hàng</div>
        <div class="chart-container">
            <canvas id="chartOrderStatus"></canvas>
        </div>
    </div>

    <div class="card">
        <div class="card-title">📈 Đơn hàng theo ngày trong tuần</div>
        <div class="chart-container">
            <canvas id="chartOrderByDay"></canvas>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-title">📋 Chi tiết đơn hàng</div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>Số lượng</th>
                    <th>Tổng tiền</th>
                    <th>Ngày</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>#DH-001</td>
                    <td>Nguyễn Văn A</td>
                    <td>5</td>
                    <td>1.250.000đ</td>
                    <td>10/05/2026</td>
                    <td><span class="badge badge-success">Hoàn thành</span></td>
                </tr>
                <tr>
                    <td>#DH-002</td>
                    <td>Trần Thị B</td>
                    <td>3</td>
                    <td>890.000đ</td>
                    <td>09/05/2026</td>
                    <td><span class="badge badge-primary">Đang xử lý</span></td>
                </tr>
                <tr>
                    <td>#DH-003</td>
                    <td>Lê Văn C</td>
                    <td>2</td>
                    <td>450.000đ</td>
                    <td>08/05/2026</td>
                    <td><span class="badge badge-success">Hoàn thành</span></td>
                </tr>
                <tr>
                    <td>#DH-004</td>
                    <td>Phạm Thị D</td>
                    <td>4</td>
                    <td>1.820.000đ</td>
                    <td>07/05/2026</td>
                    <td><span class="badge badge-success">Hoàn thành</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Order Status Chart
        const ctxStatus = document.getElementById('chartOrderStatus').getContext('2d');
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: ['Hoàn thành', 'Đang xử lý', 'Hủy'],
                datasets: [{
                    data: [88.9, 6.1, 4.9],
                    backgroundColor: [
                        '#10b981',
                        '#f59e0b',
                        '#ef4444'
                    ],
                    borderColor: '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            padding: 15,
                            font: { size: 12 },
                            color: '#6b7280'
                        }
                    }
                }
            }
        });

        // Order by Day Chart
        const ctxDay = document.getElementById('chartOrderByDay').getContext('2d');
        new Chart(ctxDay, {
            type: 'line',
            data: {
                labels: ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'],
                datasets: [{
                    label: 'Số đơn hàng',
                    data: [520, 580, 510, 620, 580, 740, 630],
                    borderColor: '#f472b6',
                    backgroundColor: 'rgba(244, 114, 182, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#ec4899',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#9ca3af',
                            font: { size: 12 }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#9ca3af',
                            font: { size: 12 }
                        },
                        grid: { display: false }
                    }
                }
            }
        });
    });
</script>
