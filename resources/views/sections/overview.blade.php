<div class="grid-4">
    <div class="stat-card">
        <div class="stat-label">👁 Lượt xem</div>
        <div class="stat-value">24.5K</div>
        <div class="stat-change">+12% so với tuần trước</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">🛒 Đơn hàng</div>
        <div class="stat-value">3,849</div>
        <div class="stat-change">+8% so với tuần trước</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">💰 Doanh thu</div>
        <div class="stat-value">68.5M</div>
        <div class="stat-change">+15% so với tuần trước</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">👥 Khách hàng</div>
        <div class="stat-value">4,250</div>
        <div class="stat-change">+5% so với tuần trước</div>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        <div class="card-title">📊 Biểu đồ doanh thu 7 ngày</div>
        <div class="chart-container">
            <canvas id="chartRevenue7Days"></canvas>
        </div>
    </div>

    <div class="card">
        <div class="card-title">📈 Top sản phẩm bán chạy</div>
        <div class="chart-container">
            <canvas id="chartTopProducts"></canvas>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-title">📋 Đơn hàng gần đây</div>
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
    // Initialize charts for overview
    document.addEventListener('DOMContentLoaded', function() {
        // Revenue 7 Days Chart
        const ctxRevenue = document.getElementById('chartRevenue7Days').getContext('2d');
        new Chart(ctxRevenue, {
            type: 'line',
            data: {
                labels: ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'],
                datasets: [{
                    label: 'Doanh thu (triệu)',
                    data: [12, 15, 13, 18, 16, 20, 14],
                    borderColor: '#f472b6',
                    backgroundColor: 'rgba(244, 114, 182, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#ec4899',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#9ca3af',
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#9ca3af',
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Top Products Chart
        const ctxTop = document.getElementById('chartTopProducts').getContext('2d');
        new Chart(ctxTop, {
            type: 'bar',
            data: {
                labels: ['Hoa hồng đỏ', 'Hoa hồng pastel', 'Hoa lý trắng', 'Hoa hướng dương', 'Hoa baby'],
                datasets: [{
                    label: 'Số lượng bán',
                    data: [120, 95, 80, 65, 50],
                    backgroundColor: [
                        '#f472b6',
                        '#ec4899',
                        '#f87171',
                        '#fb7185',
                        '#fca5a5'
                    ],
                    borderRadius: 6,
                    borderSkipped: false
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            color: '#9ca3af',
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    y: {
                        ticks: {
                            color: '#9ca3af',
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    });
</script>
