<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/Customer/Dashboard.css') }}">
    <title>@yield('title', '🌸 Cửa Hàng Hoa Tươi')</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Be+Vietnam+Pro:wght@300;400;500;600&display=swap" rel="stylesheet">

    

    @yield('head-styles')
</head>

<body data-page="@yield('page-id', 'other')">

<div id="page-transition">🌸</div>

{{-- TOP BAR --}}
{{-- TOP BAR --}}
<div class="topbar">
    <div class="topbar-left">
        <span>📞 Hotline: <strong>0357 634 696</strong></span>
        <span>💬 Zalo: <a href="https://zalo.me/0357634696" target="_blank">0357 634 696</a></span>
    </div>

    <div class="topbar-right">
        <a href="https://www.facebook.com/duong.manh.19423" target="_blank">📘 Facebook</a>
        <span>🕐 Phục vụ 24/7</span>
    </div>
</div>

{{-- HEADER --}}
<header class="customer-header">
    <a href="{{ route('customer.dashboard') }}" class="logo" id="logoLink">
        <img src="{{ asset('img/logo.png') }}" alt="Logo" class="logo-img">

        <div class="logo-text">
            <span>RoseShop</span>
            <span>Chuyên Các Loại Hoa Tươi</span>
        </div>
    </a>

    <div class="search-wrap">
        <input type="text" id="searchInput" placeholder="Tìm sản phẩm... (vd: hồng, lan, tươi)">
        <button class="search-btn" onclick="handleSearch()">Tìm kiếm</button>
    </div>

    <div class="header-right">
        <a href="https://www.facebook.com/duong.manh.19423" target="_blank" class="header-icon">
            <span class="icon">📘</span>
            <span>Facebook</span>
        </a>

        <a href="https://zalo.me/0357634696" target="_blank" class="header-icon">
            <span class="icon">💬</span>
            <span>Zalo</span>
        </a>

        @auth
            <a href="{{ route('customer.thong-bao') }}" class="header-icon">
                <span class="icon">🔔</span>
            </a>

            <div class="cart-dropdown-wrapper">
                <button class="cart-icon-btn" id="cartDropdownBtn">
                    <span class="icon">🛒</span>
                    <span class="cart-badge" id="cartBadge">
                        {{ session('gio_hang') ? count(session('gio_hang')) : 0 }}
                    </span>
                </button>

                <div class="cart-dropdown-menu" id="cartDropdownMenu">
                    <a href="{{ route('customer.gio-hang') }}">🛒 Xem giỏ hàng</a>
                    <a href="{{ route('customer.thanh-toan') }}">💳 Thanh toán</a>
                </div>
            </div>

            <a href="{{ route('customer.profile.edit') }}" class="header-icon">
                <span class="icon">👤</span>
                <span>Hồ sơ</span>
            </a>

            <div class="user-greeting">
                <span>Xin chào,</span>
                <strong>{{ $user->ten_dang_nhap ?? auth()->user()->ten_dang_nhap ?? '' }}</strong>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">Đăng xuất</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="logout-btn">Đăng nhập</a>
        @endauth
    </div>
</header>

{{-- MAIN NAV --}}
<nav class="cat-nav" id="catNav">
<a href="{{ route('customer.dashboard') }}"
   class="cat-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
   TRANG CHỦ
</a>
       <a href="{{ route('customer.hoa-tuoi') }}"
   class="cat-link {{ request()->routeIs('customer.hoa-tuoi') ? 'active' : '' }}">
   HOA TƯƠI
</a>

        <!-- <div class="dropdown-menu">
            <button class="dropdown-cat" data-cat="HOA_TUOI"> Hoa tươi</button>
            <button class="dropdown-cat" data-cat="CHAU_HOA_TUOI"> Chậu tươi</button>
        </div>
    </div>

    <div class="nav-dropdown">
        <button type="button" class="cat-link dropdown-toggle" data-cat="HOA_GIA">
            Hoa giả ▾
        </button>

        <div class="dropdown-menu">
            <button class="dropdown-cat" data-cat="HOA_GIA"> Hoa giả</button>
            <button class="dropdown-cat" data-cat="CHAU_HOA_GIA"> Chậu giả</button>
        </div>
    </div> -->

    <!-- <button class="cat-link" data-cat="HOA_SAP">Hoa sáp</button>
    <button class="cat-link" data-cat="HOA_GIAY_NHUN">Hoa giấy</button>
    <button class="cat-link" data-cat="TERRARIUM">Terrarium</button>
    <button class="cat-link" data-cat="SAN_PHAM_PREMIUM">Premium</button>
    <button class="cat-link" data-cat="CAY_CANH">Cây cảnh</button> -->
    <a href="{{ route('customer.phu-kien') }}"
   class="cat-link {{ request()->routeIs('customer.phu-kien') ? 'active' : '' }}">
    PHỤ KIỆN
</a>
    <a href="{{ route('customer.qua-tang') }}"
   class="cat-link {{ request()->routeIs('customer.qua-tang') ? 'active' : '' }}">
    Quà tặng
</a>
    <a href="{{ route('customer.gioi-thieu') }}"
       class="cat-link {{ request()->routeIs('customer.gioi-thieu') ? 'active' : '' }}">
        Giới thiệu
    </a>
    <a href="{{ route('customer.tin-tuc') }}"
       class="cat-link {{ request()->routeIs('customer.tin-tuc') || request()->routeIs('customer.tin-tuc.chi-tiet') ? 'active' : '' }}">
        TIN TỨC
    </a>
<a href="{{ route('customer.lien-he') }}"
   class="cat-link {{ request()->routeIs('customer.lien-he') ? 'active' : '' }}">
    LIÊN HỆ
</a>



</nav>

@yield('content')

{{-- FOOTER --}}
<footer class="rose-footer">
    <div class="rose-footer-main">
        <div class="rose-footer-col footer-brand">
            <div class="footer-logo" id="footerLogo">
                <img src="{{ asset('img/logo.png') }}" alt="RoseShop">
                <h3>RoseShop</h3>
            </div>
            <p>Chuyên cung cấp hoa tươi chất lượng cao, giao hàng nhanh chóng và tận tâm.</p>

            <div class="footer-social">
                <a href="#">f</a>
                <a href="#">▶</a>
                <a href="#">◎</a>
            </div>
        </div>

        <div class="rose-footer-col">
            <h4>THÔNG TIN LIÊN HỆ</h4>
            <p>📍 123 Nguyễn Trãi, Thanh Xuân, Hà Nội</p>
            <p>📞 0357 634 696</p>
            <p>✉️ roseshop@gmail.com</p>
            <p>🕘 7:00 - 22:00 (Tất cả các ngày)</p>
        </div>

        <div class="rose-footer-col">
            <h4>DANH MỤC</h4>
            <a href="{{ route('customer.dashboard') }}">Trang chủ</a>
            <a href="{{ route('customer.hoa-tuoi') }}">Hoa tươi</a>
            <a href="{{ route('customer.phu-kien') }}">Phụ kiện</a>
            <a href="{{ route('customer.tin-tuc') }}">Tin tức</a>
            <a href="#">Giới thiệu</a>
            <a href="#">Liên hệ</a>
        </div>

        <div class="rose-footer-col">
            <h4>CHÍNH SÁCH</h4>
            <a href="#">Chính sách giao hàng</a>
            <a href="#">Chính sách đổi trả</a>
            <a href="#">Chính sách bảo mật</a>
            <a href="#">Điều khoản sử dụng</a>
        </div>

        <div class="rose-footer-col footer-newsletter">
            <h4>ĐĂNG KÝ NHẬN TIN</h4>
            <p>Nhận ưu đãi và thông tin mới nhất từ RoseShop.</p>

            <form>
                <input type="email" placeholder="Nhập email của bạn">
                <button type="button">Đăng ký</button>
            </form>
        </div>
    </div>

    <div class="rose-footer-bottom">
        <p>© {{ date('Y') }} RoseShop. All Rights Reserved.</p>

        <div class="payment-icons">
            <span>VISA</span>
            <span>🔴🟠</span>
            <span>mastercard</span>
            <span>momo</span>
            <span>ZaloPay</span>
        </div>
    </div>
</footer>

<!-- <div class="toast" id="toast">
    <span id="toastMsg">✅ Thông báo</span>
</div> -->

<script>
    const PAGE       = document.body.dataset.page; 
    const DASH_URL   = "{{ route('customer.dashboard') }}";
    const overlay    = document.getElementById('page-transition');

    function navigateTo(url) {
        overlay.classList.add('active');
        setTimeout(() => { window.location.href = url; }, 220);
    }

    document.getElementById('logoLink').addEventListener('click', e => {
        if (PAGE === 'dashboard') {
            e.preventDefault();
            if (typeof filterCat === 'function') filterCat('', null);
            if (typeof setSearchVal === 'function') setSearchVal('');
            document.getElementById('searchInput').value = '';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            e.preventDefault();
            navigateTo(DASH_URL);
        }
    });
    document.getElementById('footerLogo').addEventListener('click', e => {
        if (PAGE === 'dashboard') {
            e.preventDefault();
            if (typeof filterCat === 'function') filterCat('', null);
            if (typeof setSearchVal === 'function') setSearchVal('');
            document.getElementById('searchInput').value = '';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            e.preventDefault();
            navigateTo(DASH_URL);
        }
    });

    
document.querySelectorAll('#catNav [data-cat]').forEach(btn => {
    btn.addEventListener('click', (e) => {
        e.preventDefault();

        const cat = btn.dataset.cat;

        if (PAGE === 'dashboard') {
            document.querySelectorAll('#catNav [data-cat]')
                .forEach(b => b.classList.remove('active'));

            btn.classList.add('active');

            if (typeof filterCat === 'function') {
                filterCat(cat, null);
            }

            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            navigateTo(DASH_URL + '?cat=' + encodeURIComponent(cat));
        }
    });
});

    function handleSearch() {
        const val = document.getElementById('searchInput').value.trim();
        if (PAGE === 'dashboard') {
            if (typeof setSearchVal === 'function') setSearchVal(val);
        } else {
            navigateTo(val ? DASH_URL + '?q=' + encodeURIComponent(val) : DASH_URL);
        }
    }
    document.getElementById('searchInput').addEventListener('keydown', e => {
        if (e.key === 'Enter') handleSearch();
    });
    document.getElementById('searchInput').addEventListener('input', function() {
        if (PAGE === 'dashboard' && typeof setSearchVal === 'function') {
            setSearchVal(this.value.trim());
        }
    });

    function showToast(msg) {
        const toast = document.getElementById('toast');
        document.getElementById('toastMsg').textContent = msg;
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 3000);
    }

    const cartBtn = document.getElementById('cartDropdownBtn');
    const cartMenu = document.getElementById('cartDropdownMenu');
    if (cartBtn && cartMenu) {
        cartBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            cartMenu.classList.toggle('show');
        });
        document.addEventListener('click', (e) => {
            if (!cartBtn.contains(e.target) && !cartMenu.contains(e.target)) {
                cartMenu.classList.remove('show');
            }
        });
    }

    window.updateCartBadge = function(count) {
        const badge = document.getElementById('cartBadge');
        if (badge) {
            badge.textContent = count;
            if (count === 0) badge.textContent = '0';
        }
    };

    function showSuccessToast(message) {
    const toast = document.getElementById('success-toast');

    if (!toast) return;

    toast.textContent = message;

    toast.classList.add('show');

    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}
    // Kiểm tra thông báo chưa đọc → hiện chấm đỏ trên chuông
    @auth
    (function checkNotifDot() {
        fetch('/customer/thong-bao/so-chua-doc', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            const dot = document.getElementById('notifDot');
            if (dot && data.count > 0) dot.classList.add('has-notif');
        })
        .catch(() => {});
    })();
    @endauth
</script>


@yield('scripts')
<div id="success-toast" class="success-toast">
    ✅ Đã thêm sản phẩm vào giỏ hàng thành công
</div>
</body>
</html>