<div class="grid-4">
    <div class="stat-card">
        <div class="stat-label">📦 Tổng sản phẩm</div>
        <div class="stat-value">585</div>
        <div class="stat-change">+10 hôm nay</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">🛒 Đã bán</div>
        <div class="stat-value">2,310</div>
        <div class="stat-change">+120 hôm nay</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">💰 Doanh thu</div>
        <div class="stat-value">68.4M</div>
        <div class="stat-change">+2.5M hôm nay</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">📊 Tỷ lệ</div>
        <div class="stat-value">89%</div>
        <div class="stat-change">Tỷ lệ bán hàng</div>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        <div class="card-title">📊 Top 10 sản phẩm bán chạy</div>
        <div class="chart-container">
            <canvas id="chartTopSalesProducts"></canvas>
        </div>
    </div>

    <div class="card">
        <div class="card-title">🥧 Tỷ lệ doanh thu theo sản phẩm</div>
        <div class="chart-container-pie">
            <canvas id="chartRevenueDistribution"></canvas>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-title">📋 Chi tiết sản phẩm bán chạy</div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Sản phẩm</th>
                    <th>Loại</th>
                    <th>Số lượng bán</th>
                    <th>Doanh thu</th>
                    <th>Tỷ lệ %</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>🌹 Hoa hồng đỏ</td>
                    <td><span class="badge badge-primary">Hoa hồng</span></td>
                    <td>120</td>
                    <td>18,000,000đ</td>
                    <td><strong>18.6%</strong></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>🌷 Hoa hồng pastel</td>
                    <td><span class="badge badge-primary">Hoa hồng</span></td>
                    <td>95</td>
                    <td>14,250,000đ</td>
                    <td><strong>14.7%</strong></td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>🤍 Hoa lý trắng</td>
                    <td><span class="badge badge-primary">Hoa lý</span></td>
                    <td>80</td>
                    <td>12,000,000đ</td>
                    <td><strong>12.4%</strong></td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>🌻 Hoa hướng dương</td>
                    <td><span class="badge badge-primary">Hoa hướng dương</span></td>
                    <td>65</td>
                    <td>9,750,000đ</td>
                    <td><strong>10.1%</strong></td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>👶 Hoa baby</td>
                    <td><span class="badge badge-primary">Hoa phụ</span></td>
                    <td>60</td>
                    <td>9,000,000đ</td>
                    <td><strong>9.3%</strong></td>
                </tr>
                <tr>
                    <td>6</td>
                    <td>🌺 Hoa bìm bịp</td>
                    <td><span class="badge badge-primary">Hoa phụ</span></td>
                    <td>45</td>
                    <td>6,750,000đ</td>
                    <td><strong>6.9%</strong></td>
                </tr>
                <tr>
                    <td>7</td>
                    <td>🎋 Hoa cẩm tú cầu</td>
                    <td><span class="badge badge-primary">Hoa cẩm tú cầu</span></td>
                    <td>40</td>
                    <td>6,000,000đ</td>
                    <td><strong>6.2%</strong></td>
                </tr>
                <tr>
                    <td>8</td>
                    <td>🌿 Hoa lan hồ điệp</td>
                    <td><span class="badge badge-primary">Hoa lan</span></td>
                    <td>35</td>
                    <td>5,250,000đ</td>
                    <td><strong>5.4%</strong></td>
                </tr>
                <tr>
                    <td>9</td>
                    <td>🌸 Hoa đông lăn</td>
                    <td><span class="badge badge-primary">Hoa phụ</span></td>
                    <td>30</td>
                    <td>4,500,000đ</td>
                    <td><strong>4.6%</strong></td>
                </tr>
                <tr>
                    <td>10</td>
                    <td>🎀 Hoa miễu đán</td>
                    <td><span class="badge badge-primary">Hoa phụ</span></td>
                    <td>25</td>
                    <td>3,750,000đ</td>
                    <td><strong>3.1%</strong></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Top Sales Products Chart
        const ctxTop = document.getElementById('chartTopSalesProducts').getContext('2d');
        new Chart(ctxTop, {
            type: 'bar',
            data: {
                labels: ['Hoa hồng đỏ', 'Hoa hồng\npastel', 'Hoa lý\ntrắng', 'Hoa hướng\ndương', 'Hoa baby', 'Hoa\nbìm bịp', 'Hoa cẩm\ntú cầu', 'Hoa lan\nhồ điệp', 'Hoa đông\nlăn', 'Hoa miễu\nđán'],
                datasets: [{
                    label: 'Số lượng bán (đơn vị)',
                    data: [120, 95, 80, 65, 60, 45, 40, 35, 30, 25],
                    backgroundColor: '#f472b6',
                    borderRadius: 6,
                    borderSkipped: false
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
                                size: 11
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Revenue Distribution Pie Chart
        const ctxPie = document.getElementById('chartRevenueDistribution').getContext('2d');
        new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: ['Hoa hồng đỏ', 'Hoa hồng pastel', 'Hoa lý trắng', 'Hoa hướng dương', 'Hoa baby', 'Hoa bìm bịp', 'Hoa cẩm tú cầu', 'Hoa lan hồ điệp', 'Hoa đông lăn', 'Hoa miễu đán'],
                datasets: [{
                    data: [18.6, 14.7, 12.4, 10.1, 9.3, 6.9, 6.2, 5.4, 4.6, 3.1],
                    backgroundColor: [
                        '#f472b6',
                        '#ec4899',
                        '#f87171',
                        '#fb7185',
                        '#fca5a5',
                        '#60a5fa',
                        '#06b6d4',
                        '#10b981',
                        '#f59e0b',
                        '#8b5cf6'
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
                            font: {
                                size: 12
                            },
                            color: '#6b7280'
                        }
                    }
                }
            }
        });
    });
</script>
