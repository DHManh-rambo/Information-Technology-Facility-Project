<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🌸 Cửa Hàng Hoa Tươi</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Be+Vietnam+Pro:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary:   #e8436a;
            --primary-dark: #c0294e;
            --accent:    #f97316;
            --gold:      #f59e0b;
            --soft-pink: #fdf2f5;
            --light:     #fff7f9;
            --gray:      #6b7280;
            --dark:      #1f2937;
            --green:     #16a34a;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Be Vietnam Pro', sans-serif;
            background: var(--light);
            color: var(--dark);
        }

        /* ── TOP BAR ── */
        .topbar {
            background: var(--primary);
            color: #fff;
            font-size: 0.78rem;
            padding: 6px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }
        .topbar a { color: #ffe0e8; text-decoration: none; }
        .topbar a:hover { color: #fff; text-decoration: underline; }
        .topbar-left, .topbar-right { display: flex; align-items: center; gap: 16px; }

        /* ── HEADER ── */
        header {
            background: #fff;
            box-shadow: 0 2px 12px rgba(232,67,106,.10);
            padding: 14px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        .logo-icon { font-size: 2rem; }
        .logo-text { line-height: 1.1; }
        .logo-text span:first-child {
            font-family: 'Playfair Display', serif;
            font-size: 1.45rem;
            color: var(--primary);
            display: block;
        }
        .logo-text span:last-child {
            font-size: 0.68rem;
            color: var(--gray);
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        /* Search bar */
        .search-wrap {
            flex: 1;
            max-width: 520px;
            display: flex;
            align-items: center;
            background: #f3f4f6;
            border: 2px solid transparent;
            border-radius: 999px;
            overflow: hidden;
            transition: border-color .2s;
        }
        .search-wrap:focus-within { border-color: var(--primary); background: #fff; }
        #searchInput {
            flex: 1;
            border: none;
            background: transparent;
            padding: 10px 18px;
            font-size: 0.9rem;
            font-family: inherit;
            outline: none;
        }
        .search-btn {
            background: var(--primary);
            border: none;
            color: #fff;
            padding: 10px 22px;
            font-size: 0.88rem;
            font-family: inherit;
            font-weight: 600;
            cursor: pointer;
            transition: background .2s;
        }
        .search-btn:hover { background: var(--primary-dark); }

        /* Header right */
        .header-right {
            display: flex;
            align-items: center;
            gap: 18px;
        }
        .header-icon {
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 0.72rem;
            color: var(--gray);
            cursor: pointer;
            text-decoration: none;
            gap: 2px;
        }
        .header-icon:hover { color: var(--primary); }
        .header-icon .icon { font-size: 1.4rem; }
        .user-greeting {
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 0.72rem;
            color: var(--gray);
        }
        .user-greeting strong { color: var(--primary); font-size: 0.82rem; }

        .logout-btn {
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 7px 16px;
            border-radius: 999px;
            font-size: 0.78rem;
            font-family: inherit;
            font-weight: 600;
            cursor: pointer;
            transition: background .2s;
        }
        .logout-btn:hover { background: var(--primary-dark); }

        /* ── CATEGORY NAV ── */
        .cat-nav {
            background: #fff;
            border-top: 1px solid #f0e0e5;
            border-bottom: 3px solid var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            gap: 0;
            padding: 0 12px;
        }
        .cat-btn {
            background: none;
            border: none;
            font-family: inherit;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--dark);
            cursor: pointer;
            padding: 12px 14px;
            position: relative;
            transition: color .2s;
            white-space: nowrap;
            letter-spacing: .02em;
            text-transform: uppercase;
        }
        .cat-btn::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0; right: 0;
            height: 3px;
            background: var(--primary);
            transform: scaleX(0);
            transition: transform .2s;
        }
        .cat-btn:hover, .cat-btn.active { color: var(--primary); }
        .cat-btn:hover::after, .cat-btn.active::after { transform: scaleX(1); }

        /* ── MAIN ── */
        .main-wrap {
            max-width: 1280px;
            margin: 0 auto;
            padding: 28px 20px;
        }

        /* Welcome card */
        .welcome-card {
            background: linear-gradient(135deg, #fff0f3 0%, #fff7ee 100%);
            border: 1px solid #fcd5de;
            border-radius: 20px;
            padding: 24px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 32px;
            flex-wrap: wrap;
        }
        .welcome-left { display: flex; align-items: center; gap: 18px; }
        .welcome-avatar {
            width: 62px; height: 62px;
            background: var(--primary);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.8rem;
            box-shadow: 0 4px 14px rgba(232,67,106,.35);
        }
        .welcome-info h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.25rem;
            color: var(--primary-dark);
        }
        .welcome-info p { font-size: 0.83rem; color: var(--gray); margin-top: 2px; }
        .welcome-stats {
            display: flex;
            gap: 24px;
            flex-wrap: wrap;
        }
        .stat-item {
            text-align: center;
            background: #fff;
            border-radius: 14px;
            padding: 12px 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,.07);
        }
        .stat-item .stat-val {
            font-family: 'Playfair Display', serif;
            font-size: 1.35rem;
            color: var(--gold);
            font-weight: 700;
        }
        .stat-item .stat-label { font-size: 0.72rem; color: var(--gray); margin-top: 2px; }

        /* Section header */
        .section-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 10px;
        }
        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.35rem;
            color: var(--primary-dark);
            position: relative;
            padding-left: 16px;
        }
        .section-title::before {
            content: '';
            position: absolute;
            left: 0; top: 4px; bottom: 4px;
            width: 4px;
            background: var(--primary);
            border-radius: 4px;
        }
        .result-count {
            font-size: 0.8rem;
            color: var(--gray);
            background: #f3f4f6;
            padding: 5px 14px;
            border-radius: 999px;
        }

        /* ── PRODUCT GRID ── */
        #productGrid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
            gap: 22px;
        }

        .product-card {
            background: #fff;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,.07);
            transition: transform .25s, box-shadow .25s;
            cursor: pointer;
            position: relative;
        }
        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 28px rgba(232,67,106,.18);
        }

        .card-img-wrap {
            position: relative;
            aspect-ratio: 1 / 1;
            background: #f9eef1;
            overflow: hidden;
        }
        .card-img-wrap img {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform .4s;
        }
        .product-card:hover .card-img-wrap img { transform: scale(1.07); }

        .card-img-placeholder {
            width: 100%; height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3.5rem;
        }

        .badge-status {
            position: absolute;
            top: 10px; left: 10px;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: .04em;
        }
        .badge-status.on-sale {
            background: var(--accent);
            color: #fff;
        }
        .badge-status.sold-out {
            background: #9ca3af;
            color: #fff;
        }

        .badge-type {
            position: absolute;
            top: 10px; right: 10px;
            background: rgba(255,255,255,.9);
            color: var(--primary-dark);
            padding: 3px 9px;
            border-radius: 999px;
            font-size: 0.65rem;
            font-weight: 600;
            backdrop-filter: blur(4px);
        }

        .card-body {
            padding: 14px 16px 16px;
        }
        .card-name {
            font-weight: 600;
            font-size: 0.92rem;
            color: var(--dark);
            margin-bottom: 6px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.4;
        }
        .card-desc {
            font-size: 0.75rem;
            color: var(--gray);
            margin-bottom: 10px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }
        .card-stock {
            font-size: 0.75rem;
            color: var(--green);
            font-weight: 600;
        }
        .card-stock.low { color: var(--accent); }
        .card-stock.out { color: #9ca3af; }

        .add-btn {
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 999px;
            padding: 6px 14px;
            font-size: 0.75rem;
            font-family: inherit;
            font-weight: 600;
            cursor: pointer;
            transition: background .2s, transform .15s;
        }
        .add-btn:hover { background: var(--primary-dark); transform: scale(1.05); }
        .add-btn:disabled {
            background: #d1d5db;
            cursor: not-allowed;
            transform: none;
        }

        /* Empty state */
        #emptyState {
            display: none;
            grid-column: 1/-1;
            text-align: center;
            padding: 60px 20px;
        }
        #emptyState .empty-icon { font-size: 4rem; margin-bottom: 14px; }
        #emptyState p { color: var(--gray); font-size: 0.95rem; }

        /* ── FOOTER ── */
        footer {
            background: var(--dark);
            color: #d1d5db;
            margin-top: 60px;
            padding: 36px 24px 24px;
        }
        .footer-inner {
            max-width: 1280px;
            margin: 0 auto;
            display: flex;
            gap: 40px;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .footer-brand .logo-text span:first-child { color: #f9a8b8; }
        .footer-brand p { font-size: 0.8rem; margin-top: 8px; line-height: 1.7; max-width: 260px; }

        .footer-col h4 {
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #fff;
            margin-bottom: 14px;
            font-weight: 700;
        }
        .footer-col ul { list-style: none; }
        .footer-col li { margin-bottom: 8px; font-size: 0.8rem; }
        .footer-col a { color: #d1d5db; text-decoration: none; transition: color .2s; }
        .footer-col a:hover { color: #f9a8b8; }

        .footer-divider { border: none; border-top: 1px solid #374151; margin: 28px 0 14px; }
        .footer-bottom {
            max-width: 1280px;
            margin: 0 auto;
            font-size: 0.75rem;
            color: #6b7280;
            text-align: center;
        }

        /* Responsive */
        @media (max-width: 640px) {
            .cat-btn { font-size: 0.72rem; padding: 10px 9px; }
            header { gap: 10px; }
            .search-wrap { max-width: 100%; }
        }

        /* Loading pulse */
        .pulse {
            animation: pulse 1.5s ease-in-out infinite;
        }
        @keyframes pulse {
            0%,100% { opacity: 1; }
            50% { opacity: .4; }
        }
    </style>
</head>
<body>

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
    <a href="#" class="logo">
        <span class="logo-icon">🌸</span>
        <div class="logo-text">
            <span>Hoa Tươi Shop</span>
            <span>Flower Delivery Expert</span>
        </div>
    </a>

    <div class="search-wrap">
        <input type="text" id="searchInput" placeholder="Tìm sản phẩm... (vd: hồng, lan, tươi)">
        <button class="search-btn" onclick="doSearch()">Tìm kiếm</button>
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
        <a href="{{ route('customer.profile.edit') }}" class="header-icon">
            <span class="icon">👤</span>
            <span>Hồ sơ</span>
        </a>
        <div class="user-greeting">
            <span>Xin chào,</span>
            <strong>{{ $user->ten_dang_nhap }}</strong>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">Đăng xuất</button>
        </form>
    </div>
</header>

{{-- CATEGORY NAV --}}
<nav class="cat-nav">
    <button class="cat-btn active" onclick="filterCat('', this)">Tất Cả</button>
    <button class="cat-btn" onclick="filterCat('HOA_TUOI', this)">🌹 Hoa Tươi</button>
    <button class="cat-btn" onclick="filterCat('HOA_GIA', this)">💐 Hoa Giả</button>
    <button class="cat-btn" onclick="filterCat('SAN_PHAM_PREMIUM', this)">👑 Premium</button>
    <button class="cat-btn" onclick="filterCat('CHAU_HOA_GIA', this)">🪴 Chậu Giả</button>
    <button class="cat-btn" onclick="filterCat('CHAU_HOA_TUOI', this)">🌷 Chậu Tươi</button>
    <button class="cat-btn" onclick="filterCat('CAY_CANH', this)">🌿 Cây Cảnh</button>
    <button class="cat-btn" onclick="filterCat('HOA_SAP', this)">🕯️ Hoa Sáp</button>
    <button class="cat-btn" onclick="filterCat('HOA_GIAY_NHUN', this)">🎀 Hoa Giấy</button>
    <button class="cat-btn" onclick="filterCat('TERRARIUM', this)">🔮 Terrarium</button>
    <button class="cat-btn" onclick="filterCat('PHU_KIEN', this)">🎁 Phụ Kiện</button>
    <button class="cat-btn" onclick="filterCat('QUA_TANG', this)">🎀 Quà Tặng</button>
</nav>

{{-- MAIN --}}
<div class="main-wrap">

    {{-- Welcome Card --}}
    <div class="welcome-card">
        <div class="welcome-left">
            <div class="welcome-avatar">🌸</div>
            <div class="welcome-info">
                <h2>Xin chào, {{ $user->khachHang ? $user->khachHang->ten_khach_hang : $user->ten_dang_nhap }}!</h2>
                <p>Chào mừng bạn quay lại cửa hàng hoa tươi của chúng tôi 💐</p>
                @if($user->khachHang)
                <p style="margin-top:4px; font-size:.78rem;">
                    📞 {{ $user->khachHang->so_dien_thoai }} &nbsp;|&nbsp;
                    ✉️ {{ $user->khachHang->email }}
                </p>
                @endif
            </div>
        </div>
        @if($user->khachHang)
        <div class="welcome-stats">
            <div class="stat-item">
                <div class="stat-val" style="font-size:1rem;">📍</div>
                <div class="stat-label">{{ $user->khachHang->dia_chi ? Str::limit($user->khachHang->dia_chi, 28) : 'Chưa có địa chỉ' }}</div>
            </div>
        </div>
        @endif
    </div>

    {{-- Products Section --}}
    <div class="section-head">
        <h2 class="section-title">Sản Phẩm</h2>
        <span class="result-count" id="resultCount">Đang tải...</span>
    </div>

    <div id="productGrid">
        {{-- Loading placeholders --}}
        @for($i = 0; $i < 8; $i++)
        <div class="product-card pulse" style="height:300px;"></div>
        @endfor
        <div id="emptyState">
            <div class="empty-icon">🔍</div>
            <p>Không tìm thấy sản phẩm phù hợp.</p>
        </div>
    </div>
</div>

{{-- FOOTER --}}
<footer>
    <div class="footer-inner">
        <div class="footer-brand">
            <a href="#" class="logo" style="text-decoration:none;">
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
                <li><a href="#" onclick="filterCat('HOA_TUOI')">🌹 Hoa Tươi</a></li>
                <li><a href="#" onclick="filterCat('SAN_PHAM_PREMIUM')">👑 Premium</a></li>
                <li><a href="#" onclick="filterCat('CAY_CANH')">🌿 Cây Cảnh</a></li>
                <li><a href="#" onclick="filterCat('QUA_TANG')">🎀 Quà Tặng</a></li>
            </ul>
        </div>
    </div>
    <hr class="footer-divider">
    <div class="footer-bottom">
        © {{ date('Y') }} Hoa Tươi Shop. Phục vụ 24/7 với tình yêu 🌸
    </div>
</footer>

<script>

    const categoryLabels = {
        'HOA_TUOI':         'Hoa Tươi',
        'HOA_GIA':          'Hoa Giả',
        'SAN_PHAM_PREMIUM': 'Premium',
        'CHAU_HOA_GIA':     'Chậu Giả',
        'CHAU_HOA_TUOI':    'Chậu Tươi',
        'CAY_CANH':         'Cây Cảnh',
        'HOA_SAP':          'Hoa Sáp',
        'HOA_GIAY_NHUN':    'Hoa Giấy Nhún',
        'TERRARIUM':        'Terrarium',
        'PHU_KIEN':         'Phụ Kiện',
        'QUA_TANG':         'Quà Tặng',
    };

    const catEmoji = {
        'HOA_TUOI': '🌹', 'HOA_GIA': '💐', 'SAN_PHAM_PREMIUM': '👑',
        'CHAU_HOA_GIA': '🪴', 'CHAU_HOA_TUOI': '🌷', 'CAY_CANH': '🌿',
        'HOA_SAP': '🕯️', 'HOA_GIAY_NHUN': '🎀', 'TERRARIUM': '🔮',
        'PHU_KIEN': '🎁', 'QUA_TANG': '🎀',
    };

    let allProducts = @json($sanPhams ?? []);
    let activeCat   = '';
    let searchVal   = '';

    function renderProducts() {
        const grid    = document.getElementById('productGrid');
        const empty   = document.getElementById('emptyState');
        const counter = document.getElementById('resultCount');

        let filtered = allProducts.filter(p => {
            const matchCat  = activeCat ? p.loai_san_pham === activeCat : true;
            const matchText = searchVal
                ? p.ten_san_pham.toLowerCase().includes(searchVal.toLowerCase())
                : true;
            return matchCat && matchText;
        });

        Array.from(grid.children).forEach(c => {
            if (c.id !== 'emptyState') c.remove();
        });

        counter.textContent = `${filtered.length} sản phẩm`;

        if (filtered.length === 0) {
            empty.style.display = 'block';
            return;
        }
        empty.style.display = 'none';

        filtered.forEach(p => {
            const isOnSale = p.trang_thai === 'DANG_BAN';
            const stock    = p.so_luong ?? 0;
            const stockClass = stock === 0 ? 'out' : stock <= 5 ? 'low' : '';
            const stockText  = stock === 0 ? 'Hết hàng' : `Còn ${stock} sp`;

            const card = document.createElement('div');
            card.className = 'product-card';
            card.innerHTML = `
                <div class="card-img-wrap">
                    ${p.hinh_anh
                        ? `<img src="/${p.hinh_anh}" alt="${p.ten_san_pham}" onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                           <div class="card-img-placeholder" style="display:none;">${catEmoji[p.loai_san_pham] || '🌸'}</div>`
                        : `<div class="card-img-placeholder">${catEmoji[p.loai_san_pham] || '🌸'}</div>`
                    }
                    <span class="badge-status ${isOnSale ? 'on-sale' : 'sold-out'}">
                        ${isOnSale ? 'Đang bán' : 'Ngừng bán'}
                    </span>
                    <span class="badge-type">${categoryLabels[p.loai_san_pham] || p.loai_san_pham}</span>
                </div>
                <div class="card-body">
                    <div class="card-name">${p.ten_san_pham}</div>
                    ${p.mo_ta ? `<div class="card-desc">${p.mo_ta}</div>` : ''}
                    <div class="card-footer">
                        <span class="card-stock ${stockClass}">${stockText}</span>
                        <button class="add-btn" ${!isOnSale || stock === 0 ? 'disabled' : ''}>
                            ${stock === 0 ? 'Hết hàng' : '🛒 Đặt hoa'}
                        </button>
                    </div>
                </div>
            `;
            grid.insertBefore(card, empty);
        });
    }

    function filterCat(cat, btnEl) {
        activeCat = cat;
        document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
        if (btnEl) btnEl.classList.add('active');
        renderProducts();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function doSearch() {
        searchVal = document.getElementById('searchInput').value.trim();
        renderProducts();
    }

    document.getElementById('searchInput').addEventListener('input', function() {
        searchVal = this.value.trim();
        renderProducts();
    });

    document.getElementById('searchInput').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') doSearch();
    });

    renderProducts();
</script>
</body>
</html>