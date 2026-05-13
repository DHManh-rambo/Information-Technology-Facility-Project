<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang khách hàng</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    {{-- Navbar --}}
    <nav class="bg-white shadow px-6 py-4 flex items-center justify-between">
        <span class="text-xl font-bold text-indigo-600">🛍️ Cửa hàng</span>
        <div class="flex items-center gap-4">
            <span class="text-gray-600 text-sm">Xin chào, <strong>{{ $user->ten_dang_nhap }}</strong></span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="text-sm bg-red-500 hover:bg-red-600 text-white px-4 py-1.5 rounded-lg transition">
                    Đăng xuất
                </button>
            </form>
        </div>
    </nav>

    {{-- Body --}}
    <div class="max-w-3xl mx-auto mt-16 px-4">
        <div class="bg-white rounded-2xl shadow p-8 text-center">
            <div class="text-6xl mb-4">🎉</div>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Đăng nhập thành công!</h1>
            <p class="text-gray-500 mb-6">
                Bạn đang đăng nhập với tài khoản
                <span class="font-semibold text-indigo-600">{{ $user->ten_dang_nhap }}</span>
                (vai trò: <span class="font-semibold text-green-600">{{ $user->vai_tro }}</span>)
            </p>

            {{-- Thông tin khách hàng nếu có --}}
            @if($user->khachHang)
            <div class="bg-indigo-50 rounded-xl p-5 text-left mt-4 space-y-2 text-sm text-gray-700">
                <p><span class="font-medium w-36 inline-block">Họ tên:</span> {{ $user->khachHang->ten_khach_hang }}</p>
                <p><span class="font-medium w-36 inline-block">Email:</span> {{ $user->khachHang->email }}</p>
                <p><span class="font-medium w-36 inline-block">Số điện thoại:</span> {{ $user->khachHang->so_dien_thoai }}</p>
                <p><span class="font-medium w-36 inline-block">Địa chỉ:</span> {{ $user->khachHang->dia_chi }}</p>
                <p><span class="font-medium w-36 inline-block">Điểm tích lũy:</span>
                    <span class="text-yellow-600 font-semibold">{{ number_format($user->khachHang->diem_tich_luy) }} điểm</span>
                </p>
            </div>
            @endif

            <p class="text-xs text-gray-400 mt-8">Dashboard khách hàng — sẽ hiển thị đơn hàng và sản phẩm ở đây.</p>
        </div>
    </div>

</body>
</html>