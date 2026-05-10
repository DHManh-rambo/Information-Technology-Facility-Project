<div class="grid-4">
    <div class="stat-card">
        <div class="stat-label">💰 Tổng doanh thu</div>
        <div class="stat-value">68.5M</div>
        <div class="stat-change">+15% so với tháng trước</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">🎯 Doanh thu trung bình</div>
        <div class="stat-value">2.3M</div>
        <div class="stat-change">Mỗi ngày</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">📈 Tăng trưởng</div>
        <div class="stat-value">+25%</div>
        <div class="stat-change">So với tháng trước</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">🏆 Ngày cao nhất</div>
        <div class="stat-value">5.2M</div>
        <div class="stat-change">10/05/2026</div>
    </div>
</div>

<div class="card">
    <div class="card-title">📊 Biểu đồ doanh thu theo ngày</div>
    <div class="chart-container">
        <canvas id="chartDailyRevenue"></canvas>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        <div class="card-title">📈 Doanh thu theo tuần</div>
        <div class="chart-container">
            <canvas id="chartWeeklyRevenue"></canvas>
        </div>
    </div>

    <div class="card">
        <div class="card-title">🥧 Tỷ lệ doanh thu theo phương thức thanh toán</div>
        <div class="chart-container-pie">
            <canvas id="chartPaymentMethod"></canvas>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Daily Revenue Chart
        const ctxDaily = document.getElementById('chartDailyRevenue').getContext('2d');
        new Chart(ctxDaily, {
            type: 'area',
            data: {
                labels: ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10'],
                datasets: [{
                    label: 'Doanh thu (triệu)',
                    data: [2.1, 2.5, 1.9, 3.2, 2.8, 3.5, 4.1, 3.8, 4.5, 5.2],
                    borderColor: '#f472b6',
                    backgroundColor: 'rgba(244, 114, 182, 0.2)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#ec4899',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4
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
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Weekly Revenue Chart
        const ctxWeekly = document.getElementById('chartWeeklyRevenue').getContext('2d');
        new Chart(ctxWeekly, {
            type: 'bar',
            data: {
                labels: ['Tuần 1', 'Tuần 2', 'Tuần 3', 'Tuần 4'],
                datasets: [{
                    label: 'Doanh thu (triệu)',
                    data: [15.5, 18.2, 16.8, 18.0],
                    backgroundColor: ['#f472b6', '#ec4899', '#f87171', '#fb7185'],
                    borderRadius: 6
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

        // Payment Method Pie
        const ctxPayment = document.getElementById('chartPaymentMethod').getContext('2d');
        new Chart(ctxPayment, {
            type: 'doughnut',
            data: {
                labels: ['Tiền mặt', 'Chuyển khoản', 'Thẻ tín dụng', 'E-wallet'],
                datasets: [{
                    data: [45, 30, 15, 10],
                    backgroundColor: [
                        '#f472b6',
                        '#60a5fa',
                        '#10b981',
                        '#f59e0b'
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
    });
</script>
