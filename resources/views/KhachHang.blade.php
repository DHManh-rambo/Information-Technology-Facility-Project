<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/KhachHang.css') }}">
    <title>Quản lý khách hàng</title>
</head>
<body>
<div class="container">
    <h1>DANH SÁCH KHÁCH HÀNG</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <div class="sort-buttons">
        <a href="{{ route('khach-hang.index', ['sort' => 'desc']) }}" class="btn {{ $sort == 'desc' ? 'btn-secondary' : '' }}">Điểm từ cao → thấp</a>
        <a href="{{ route('khach-hang.index', ['sort' => 'asc']) }}" class="btn {{ $sort == 'asc' ? 'btn-secondary' : '' }}">Điểm từ thấp → cao</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Tên khách hàng</th>
                <th>Số điện thoại</th>
                <th>Email</th>
                <th>Địa chỉ</th>
                <th>Điểm tích lũy</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($khachHangs as $index => $kh)
            <tr id="row-{{ $kh->ma_khach_hang }}">
                <td>{{ $index + 1 }}</td>
                <td>{{ $kh->ten_khach_hang }}</td>
                <td>{{ $kh->so_dien_thoai }}</td>
                <td>{{ $kh->email }}</td>
                <td>{{ $kh->dia_chi }}</td>
                <td>{{ $kh->diem_tich_luy }}</td>
                <td>
                    <form action="{{ route('khach-hang.destroy', $kh->ma_khach_hang) }}" method="POST" style="display:inline-block;"
                          onsubmit="return confirm('Bạn có chắc muốn xóa khách hàng này? Hành động sẽ xóa luôn tài khoản đăng nhập.')">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="sort" value="{{ $sort }}">
                        <button type="submit" class="btn-danger">Xóa</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7">Không có khách hàng nào.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
</body>
</html>