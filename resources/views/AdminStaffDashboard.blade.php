<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Quản Lý</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

{{-- ── Navbar ────────────────────────────────────────────────────────────── --}}
<nav class="bg-white shadow px-6 py-4 flex justify-between items-center">
    <span class="font-bold text-xl text-indigo-700">
        🏪 Quản Lý Cửa Hàng
    </span>
    <div class="flex items-center gap-4">
        <span class="text-sm text-gray-600">
            Xin chào, <strong>{{ Auth::user()->ten_dang_nhap }}</strong>
            <span class="ml-1 px-2 py-0.5 rounded text-xs font-semibold
                {{ Auth::user()->vai_tro === 'ADMIN' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700' }}">
                {{ strtoupper(Auth::user()->vai_tro) }}
            </span>
        </span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="text-sm text-gray-500 hover:text-red-600">Đăng xuất</button>
        </form>
    </div>
</nav>

{{-- ── Main ─────────────────────────────────────────────────────────────── --}}
<main class="max-w-7xl mx-auto px-6 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        {{ Auth::user()->vai_tro === 'ADMIN' ? '⚙️ Admin Dashboard' : '🗂️ Nhân Viên Dashboard' }}
    </h1>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">

        {{-- ══ Chức năng chung cho Admin + Nhân viên ══ --}}

        {{-- Sản phẩm --}}
        <a href="{{ route('san-pham.index') }}"
           class="bg-white rounded-xl shadow p-5 hover:shadow-md transition flex flex-col items-center gap-2 text-center">
            <span class="text-3xl">📦</span>
            <span class="font-semibold text-gray-700">Sản Phẩm</span>
        </a>

        {{-- Hóa đơn --}}
        <a href="{{ route('hoa-don.index') }}"
           class="bg-white rounded-xl shadow p-5 hover:shadow-md transition flex flex-col items-center gap-2 text-center">
            <span class="text-3xl">🧾</span>
            <span class="font-semibold text-gray-700">Hóa Đơn</span>
        </a>

        {{-- Đơn hàng --}}
        <a href="{{ route('don-hang.index') }}"
           class="bg-white rounded-xl shadow p-5 hover:shadow-md transition flex flex-col items-center gap-2 text-center">
            <span class="text-3xl">🛒</span>
            <span class="font-semibold text-gray-700">Đơn Hàng</span>
        </a>

        {{-- Phiếu nhập (dành cho cả Admin và Nhân viên) --}}
        <a href="{{ route('phieu-nhap.index') }}"
           class="bg-white rounded-xl shadow p-5 hover:shadow-md transition flex flex-col items-center gap-2 text-center">
            <span class="text-3xl">📋</span>
            <span class="font-semibold text-gray-700">Phiếu Nhập</span>
        </a>

        {{-- ══ Chỉ Admin mới thấy từ đây ══ --}}
        @if(Auth::user()->vai_tro === 'ADMIN')

            {{-- Khách hàng --}}
            <a href="{{ route('khach-hang.index') }}"
               class="bg-white rounded-xl shadow p-5 hover:shadow-md transition flex flex-col items-center gap-2 text-center">
                <span class="text-3xl">👥</span>
                <span class="font-semibold text-gray-700">Khách Hàng</span>
            </a>

            {{-- Nhân viên --}}
            <a href="{{ route('nhan-vien.index') }}"
               class="bg-white rounded-xl shadow p-5 hover:shadow-md transition flex flex-col items-center gap-2 text-center">
                <span class="text-3xl">🧑‍💼</span>
                <span class="font-semibold text-gray-700">Nhân Viên</span>
            </a>

            {{-- Người dùng --}}
            <a href="{{ route('nguoi-dung.index') }}"
               class="bg-white rounded-xl shadow p-5 hover:shadow-md transition flex flex-col items-center gap-2 text-center">
                <span class="text-3xl">👤</span>
                <span class="font-semibold text-gray-700">Người Dùng</span>
            </a>

            {{-- Báo cáo --}}
            <a href="{{ route('bao-cao.index') }}"
               class="bg-white rounded-xl shadow p-5 hover:shadow-md transition flex flex-col items-center gap-2 text-center">
                <span class="text-3xl">📊</span>
                <span class="font-semibold text-gray-700">Báo Cáo</span>
            </a>

        @endif
        {{-- ══ Hết phần Admin ══ --}}

    </div>
</main>

</body>
</html>