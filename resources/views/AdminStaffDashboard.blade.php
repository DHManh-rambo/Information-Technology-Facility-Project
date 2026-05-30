<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RoseShop Admin</title>

    @vite([
        'resources/css/app.css',
        'resources/css/admin-dashboard.css',
        'resources/js/app.js'
    ])
</head>

<body>
<div class="admin-layout">

    <aside class="sidebar">
        <div>
            <div class="brand">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" class="brand-logo">
                <div>
                    <h2>ADMIN</h2>
                    <p>RoseShop</p>
                </div>
            </div>

            <nav class="menu">
                @if(Auth::user()->vai_tro === 'ADMIN')
                    <a href="#" data-url="{{ route('nguoi-dung.index') }}" class="menu-item">👥 Quản lý tài khoản</a>
                    <a href="#" data-url="{{ route('khach-hang.index') }}" class="menu-item">👤 Quản lý khách hàng</a>
                @endif

                <a href="#" data-url="{{ route('san-pham.index') }}" class="menu-item">🌷 Quản lý sản phẩm</a>
                <a href="#" data-url="{{ route('phieu-nhap.index') }}" class="menu-item">🧾 Quản lý nhập hàng</a>
                <a href="#" data-url="{{ route('don-hang.index') }}" class="menu-item">🛒 Quản lý đơn hàng</a>
                <a href="#" data-url="{{ route('hoa-don.index') }}" class="menu-item">📄 Quản lý hóa đơn</a>

                @if(Auth::user()->vai_tro === 'ADMIN')
                    <a href="#" data-url="{{ route('nhan-vien.index') }}" class="menu-item">🧑‍💼 Quản lý nhân viên</a>
                    <a href="#" data-url="{{ route('bao-cao.index') }}" class="menu-item">📊 Báo cáo thống kê</a>
                @endif
            </nav>
        </div>

        <div class="admin-footer">
            <div>
                <p class="admin-name">{{ Auth::user()->ten_dang_nhap }}</p>
                <p class="admin-role">{{ Auth::user()->vai_tro }}</p>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Đăng xuất</button>
            </form>
        </div>
    </aside>

    <main class="content">
        <div id="welcomeBox" class="welcome-box">
            <h1>Chào mừng đến RoseShop Admin</h1>
            <p>Em hãy chọn một chức năng ở menu bên trái để bắt đầu.</p>
        </div>

        <iframe
            id="contentFrame"
            name="contentFrame"
            src="about:blank"
            class="admin-iframe">
        </iframe>
    </main>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const menuItems = document.querySelectorAll('.menu-item');
    const iframe = document.getElementById('contentFrame');
    const welcomeBox = document.getElementById('welcomeBox');

    menuItems.forEach(item => {
        item.addEventListener('click', function (e) {
            e.preventDefault();

            const url = this.getAttribute('data-url');

            if (!url) {
                console.error('Menu thiếu data-url:', this);
                return;
            }

            iframe.src = url;
            iframe.style.display = 'block';

            if (welcomeBox) {
                welcomeBox.style.display = 'none';
            }

            menuItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');
        });
    });
});
</script>
</body>
</html>