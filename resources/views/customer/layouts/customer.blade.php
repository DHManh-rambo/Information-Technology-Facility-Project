<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/Customer/Dashboard.css') }}">
    <title>@yield('title', '🌸 Cửa Hàng Hoa Tươi')</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Be+Vietnam+Pro:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        #page-transition {
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: linear-gradient(135deg, #fff0f3 0%, #fce4eb 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.8rem;
            pointer-events: none;
            opacity: 0;
            transition: opacity .25s ease;
        }
        #page-transition.active { opacity: 1; pointer-events: all; }

        .main-wrap, .detail-wrap, .breadcrumb, .related-section {
            animation: fadeUp .35s ease both;
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(14px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .cart-dropdown-wrapper {
            position: relative;
            margin: 0 8px;
        }
        .cart-icon-btn {
            background: none;
            border: none;
            font-size: 1.3rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 4px;
            color: #374151;
            padding: 6px 10px;
            border-radius: 30px;
            transition: background .2s;
        }
        .cart-icon-btn:hover {
            background: #fdf2f8;
        }
        .cart-badge {
            background: #be185d;
            color: white;
            font-size: 0.7rem;
            font-weight: bold;
            min-width: 18px;
            height: 18px;
            border-radius: 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 5px;
            margin-left: 2px;
        }
        .cart-dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(190,24,93,.15);
            min-width: 180px;
            padding: 8px 0;
            z-index: 1000;
            display: none;
            border: 1px solid #fce7f3;
        }
        .cart-dropdown-menu.show {
            display: block;
        }
        .cart-dropdown-menu a {
            display: block;
            padding: 10px 18px;
            color: #1f2937;
            text-decoration: none;
            font-size: 0.85rem;
            transition: background .15s;
        }
        .cart-dropdown-menu a:hover {
            background: #fdf2f8;
            color: #be185d;
        }
    </style>

    @yield('head-styles')
</head>
<body data-page="@yield('page-id', 'other')">

<div id="page-transition">🌸</div>

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
<header>
    <a href="{{ route('customer.dashboard') }}" class="logo" id="logoLink">
        <span class="logo-icon">🌸</span>
        <div class="logo-text">
            <span>Hoa Tươi Shop</span>
            <span>Flower Delivery Expert</span>
        </div>
    </a>

    <div class="search-wrap">
        <input type="text" id="searchInput"
               placeholder="Tìm sản phẩm... (vd: hồng, lan, tươi)">
        <button class="search-btn" onclick="handleSearch()">Tìm kiếm</button>
    </div>

    <div class="header-right">
        <a href="https://www.facebook.com/duong.manh.19423" target="_blank" class="header-icon">
            <span class="icon">📘</span><span>Facebook</span>
        </a>
        <a href="https://zalo.me/0357634696" target="_blank" class="header-icon">
            <span class="icon">💬</span><span>Zalo</span>
        </a>

        @auth
            {{-- NÚT GIỎ HÀNG VỚI DROPDOWN --}}
            <div class="cart-dropdown-wrapper">
                <button class="cart-icon-btn" id="cartDropdownBtn">
                    <span class="icon">🛒</span>
                    <span class="cart-badge" id="cartBadge">{{ session('gio_hang') ? count(session('gio_hang')) : 0 }}</span>
                </button>
                <div class="cart-dropdown-menu" id="cartDropdownMenu">
                    <a href="{{ route('customer.gio-hang') }}">🛒 Xem giỏ hàng</a>
                    <a href="{{ route('customer.thanh-toan') }}">💳 Thanh toán</a>
                </div>
            </div>

            <a href="{{ route('customer.profile.edit') }}" class="header-icon">
                <span class="icon">👤</span><span>Hồ sơ</span>
            </a>
            <div class="user-greeting">
                <span>Xin chào,</span>
                <strong>{{ $user->ten_dang_nhap ?? (auth()->user()->ten_dang_nhap ?? '') }}</strong>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">Đăng xuất</button>
            </form>
        @else
            <div class="auth-btns">
                <a href="{{ route('login') }}" class="btn-login">Đăng nhập</a>
                <a href="{{ route('register') }}" class="btn-register">Đăng ký</a>
            </div>
        @endauth
    </div>
</header>

{{-- CATEGORY NAV --}}
<nav class="cat-nav" id="catNav">
    <button class="cat-btn" data-cat="">Tất Cả</button>
    <button class="cat-btn" data-cat="HOA_TUOI">🌹 Hoa Tươi</button>
    <button class="cat-btn" data-cat="HOA_GIA">💐 Hoa Giả</button>
    <button class="cat-btn" data-cat="SAN_PHAM_PREMIUM">👑 Premium</button>
    <button class="cat-btn" data-cat="CHAU_HOA_GIA">🪴 Chậu Giả</button>
    <button class="cat-btn" data-cat="CHAU_HOA_TUOI">🌷 Chậu Tươi</button>
    <button class="cat-btn" data-cat="CAY_CANH">🌿 Cây Cảnh</button>
    <button class="cat-btn" data-cat="HOA_SAP">🕯️ Hoa Sáp</button>
    <button class="cat-btn" data-cat="HOA_GIAY_NHUN">🎀 Hoa Giấy</button>
    <button class="cat-btn" data-cat="TERRARIUM">🔮 Terrarium</button>
    <button class="cat-btn" data-cat="PHU_KIEN">🎁 Phụ Kiện</button>
    <button class="cat-btn" data-cat="QUA_TANG">🎀 Quà Tặng</button>
</nav>

@yield('content')

{{-- FOOTER --}}
<footer>
    <div class="footer-inner">
        <div class="footer-brand">
            <a href="{{ route('customer.dashboard') }}" id="footerLogo" class="logo" style="text-decoration:none;">
                <span class="logo-icon">🌸</span>
                <div class="logo-text">
                    <span>Hoa Tươi Shop</span>
                    <span style="color:#9ca3af;">Flower Delivery Expert</span>
                </div>
            </a>
            <p>Chuyên cung cấp hoa tươi, hoa giả, cây cảnh và quà tặng. Giao hàng nhanh trong 60 phút.</p>
        </div>
        <div class="footer-col">
            <h4>Liên hệ</h4>
            <ul>
                <li>📞 <a href="tel:0357634696">0357 634 696</a></li>
                <li>💬 <a href="https://zalo.me/0357634696" target="_blank">Zalo: 0357 634 696</a></li>
                <li>📘 <a href="https://www.facebook.com/duong.manh.19423" target="_blank">Facebook</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Danh mục</h4>
            <ul>
                <li><a href="#" class="footer-cat" data-cat="HOA_TUOI">🌹 Hoa Tươi</a></li>
                <li><a href="#" class="footer-cat" data-cat="SAN_PHAM_PREMIUM">👑 Premium</a></li>
                <li><a href="#" class="footer-cat" data-cat="CAY_CANH">🌿 Cây Cảnh</a></li>
                <li><a href="#" class="footer-cat" data-cat="QUA_TANG">🎀 Quà Tặng</a></li>
            </ul>
        </div>
    </div>
    <hr class="footer-divider">
    <div class="footer-bottom">
        © {{ date('Y') }} Hoa Tươi Shop. Phục vụ 24/7 với tình yêu 🌸
    </div>
</footer>

<div class="toast" id="toast">
    <span id="toastMsg">✅ Thông báo</span>
</div>

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

    
    document.querySelectorAll('#catNav .cat-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const cat = btn.dataset.cat;
            if (PAGE === 'dashboard') {
                document.querySelectorAll('#catNav .cat-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                if (typeof filterCat === 'function') filterCat(cat, null);
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else {
                const url = cat ? DASH_URL + '?cat=' + encodeURIComponent(cat) : DASH_URL;
                navigateTo(url);
            }
        });
    });

    document.querySelectorAll('.footer-cat').forEach(a => {
        a.addEventListener('click', e => {
            e.preventDefault();
            const cat = a.dataset.cat;
            if (PAGE === 'dashboard') {
                document.querySelectorAll('#catNav .cat-btn').forEach(b => b.classList.remove('active'));
                const matching = document.querySelector(`#catNav .cat-btn[data-cat="${cat}"]`);
                if (matching) matching.classList.add('active');
                if (typeof filterCat === 'function') filterCat(cat, null);
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
</script>

@yield('scripts')

</body>
</html>