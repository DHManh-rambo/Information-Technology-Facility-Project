<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết đơn hàng</title>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { font-family: 'IBM Plex Sans', sans-serif; background: #f5f7fb; margin: 0; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; background: white; border-radius: 24px; padding: 32px; box-shadow: 0 8px 20px rgba(0,0,0,0.05); }
        h1 { font-size: 24px; margin-bottom: 24px; }
        .info-card { background: #f8f9fa; border-radius: 16px; padding: 20px; margin-bottom: 24px; }
        .info-row { display: flex; margin-bottom: 12px; }
        .info-label { width: 140px; font-weight: 600; }
        .btn-nhan-don { background: #0d6efd; color: white; border: none; padding: 12px 24px; border-radius: 40px; font-weight: 600; cursor: pointer; }
        .back-link { display: inline-block; margin-top: 20px; color: #6c757d; text-decoration: none; }
    </style>
</head>
<body>
<div class="container">
    <h1><i class="fas fa-truck"></i> Chi tiết đơn hàng #HD-{{ str_pad($hoaDon->ma_hoa_don, 4, '0', STR_PAD_LEFT) }}</h1>
    <div class="info-card">
        <div class="info-row"><div class="info-label">Khách hàng:</div><div>{{ $hoaDon->khachHang->ten_khach_hang ?? 'Khách vãng lai' }}</div></div>
        <div class="info-row"><div class="info-label">Địa chỉ giao:</div><div>{{ $hoaDon->dia_chi_giao }}</div></div>
        <div class="info-row"><div class="info-label">Số điện thoại:</div><div>{{ $hoaDon->so_dien_thoai }}</div></div>
        <div class="info-row"><div class="info-label">Tổng tiền:</div><div>{{ number_format($hoaDon->tong_tien, 0, ',', '.') }} đ</div></div>
        <div class="info-row"><div class="info-label">Thanh toán:</div><div>{{ $hoaDon->phuong_thuc_thanh_toan }}</div></div>
        <div class="info-row"><div class="info-label">Trạng thái:</div><div>{{ $hoaDon->trang_thai }}</div></div>
    </div>
    <button class="btn-nhan-don" onclick="alert('Chức năng đang phát triển')"><i class="fas fa-check-circle"></i> Nhận đơn</button>
    <div><a href="{{ route('shipper.dashboard') }}" class="back-link"><i class="fas fa-arrow-left"></i> Quay lại Dashboard</a></div>
</div>
</body>
</html>