<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>RoseShop Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    @stack('styles')
    <style>
        :root { --rose-pink: #FF69B4; --light-pink: #FFF5F7; --dark-pink: #C71585; }

        /* Force layout shell styles to always win over page-specific CSS */
        body {
            background-color: var(--light-pink) !important;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: #fff;
            border-right: 1px solid #eee;
            z-index: 100;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }
        .sidebar .logo {
            padding: 25px;
            font-size: 24px;
            font-weight: bold;
            color: var(--rose-pink);
            text-align: center;
            border-bottom: 1px solid var(--light-pink);
            flex-shrink: 0;
        }
        .sidebar .nav-link {
            padding: 15px 25px;
            color: #555;
            cursor: pointer;
            transition: 0.3s;
            display: flex;
            align-items: center;
            text-decoration: none;
            border-bottom: 1px solid #f9f9f9;
            border-left: 5px solid transparent;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: var(--light-pink);
            color: var(--rose-pink);
            border-left-color: var(--rose-pink);
        }
        .sidebar .nav-link i { width: 30px; font-size: 18px; text-align: center; margin-right: 10px; }

        .main-content { margin-left: 260px; padding: 30px; min-height: 100vh; }
        .content-card {
            background: #fff;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
            min-height: 85vh;
        }

        #loading-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(255,255,255,0.7);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        .menu-label {
            padding: 15px 25px 5px;
            font-size: 11px;
            text-transform: uppercase;
            color: #aaa;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div id="loading-overlay">
    <div class="spinner-border" style="color: var(--rose-pink)"></div>
</div>

<div class="sidebar">
    <div class="logo">🌷 Rose Shop 🌷</div>
    <div class="nav flex-column mt-2 flex-grow-1">

        <a class="nav-link" href="{{ route('dashboard') }}" data-url="{{ route('dashboard') }}">
            <i class="fa-solid fa-chart-pie"></i> Tổng quan
        </a>

        @if(auth()->user()->role === 'admin')
        <div class="menu-label">Nhân sự &amp; Khách hàng</div>
        <a class="nav-link" href="{{ route('nguoi-dung.index') }}" data-url="{{ route('nguoi-dung.index') }}">
            <i class="fa-solid fa-users-gear"></i> Người dùng
        </a>
        <a class="nav-link" href="{{ route('khach-hang.index') }}" data-url="{{ route('khach-hang.index') }}">
            <i class="fa-solid fa-users"></i> Khách hàng
        </a>
        <a class="nav-link" href="{{ route('nhan-vien.index') }}" data-url="{{ route('nhan-vien.index') }}">
            <i class="fa-solid fa-user-tie"></i> Nhân viên
        </a>
        @endif

        <div class="menu-label">Sản phẩm &amp; Kho</div>
        <a class="nav-link" href="{{ route('san-pham.index') }}" data-url="{{ route('san-pham.index') }}">
            <i class="fa-solid fa-boxes-stacked"></i> Sản phẩm
        </a>
        <a class="nav-link" href="{{ route('phieu-nhap.index') }}" data-url="{{ route('phieu-nhap.index') }}">
            <i class="fa-solid fa-file-import"></i> Phiếu nhập
        </a>

        <div class="menu-label">Kinh doanh</div>
        <a class="nav-link" href="{{ route('don-hang.index') }}" data-url="{{ route('don-hang.index') }}">
            <i class="fa-solid fa-clipboard-list"></i> Đơn hàng
        </a>
        <a class="nav-link" href="{{ route('hoa-don.index') }}" data-url="{{ route('hoa-don.index') }}">
            <i class="fa-solid fa-file-invoice-dollar"></i> Hóa đơn
        </a>

        @if(auth()->user()->role === 'admin')
        <a class="nav-link" href="{{ route('bao-cao.index') }}" data-url="{{ route('bao-cao.index') }}">
            <i class="fa-solid fa-chart-line"></i> Báo cáo
        </a>
        @endif
    </div>

    <div class="border-top p-2" style="flex-shrink:0">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link border-0 bg-transparent w-100 text-danger">
                <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
            </button>
        </form>
    </div>
</div>

<div class="main-content">
    <div class="content-card" id="main-display">
        @yield('admin_content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@stack('scripts')
<script>
    // Highlight active nav link based on current URL path
    (function () {
        const path = window.location.pathname;
        document.querySelectorAll('.sidebar .nav-link[data-url]').forEach(function (link) {
            try {
                const linkPath = new URL(link.dataset.url, window.location.origin).pathname;
                if (path === linkPath) link.classList.add('active');
            } catch (e) {}
        });
    })();

    $(document).ready(function () {
        $('.sidebar .nav-link[data-url]').on('click', function (e) {
            const url = $(this).data('url');
            if (!url || $(this).hasClass('text-danger')) return;

            e.preventDefault();
            $('.sidebar .nav-link').removeClass('active');
            $(this).addClass('active');
            $('#loading-overlay').css('display', 'flex');

            $.get(url, function (html) {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                // Inject any new page-specific stylesheets not yet in document
                doc.querySelectorAll('link[rel="stylesheet"]').forEach(function (link) {
                    const href = link.href;
                    if (href && !document.querySelector('link[href="' + href + '"]')) {
                        const el = document.createElement('link');
                        el.rel = 'stylesheet';
                        el.href = href;
                        document.head.appendChild(el);
                    }
                });

                const contentEl = doc.getElementById('ajax-content');
                $('#main-display').html(contentEl ? contentEl.innerHTML : html);
                $('#loading-overlay').hide();
                history.pushState(null, '', url);
            }).fail(function () {
                alert('Lỗi khi tải trang, vui lòng thử lại!');
                $('#loading-overlay').hide();
            });
        });
    });
</script>
</body>
</html>