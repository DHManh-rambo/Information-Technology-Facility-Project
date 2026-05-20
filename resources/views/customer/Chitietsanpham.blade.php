@extends('customer.layouts.customer')

@section('title', $sanPham->ten_san_pham . ' – Hoa Tươi Shop 🌸')

@section('head-styles')
<link rel="stylesheet" href="{{ asset('css/Customer/ChiTietSanPham.css') }}">
<style>
    
</style>
@endsection

@section('content')
@php
    $categoryLabels = [
        'HOA_TUOI'         => 'Hoa Tươi',
        'HOA_GIA'          => 'Hoa Giả',
        'SAN_PHAM_PREMIUM' => 'Sản Phẩm Premium',
        'CHAU_HOA_GIA'     => 'Chậu Hoa Giả',
        'CHAU_HOA_TUOI'    => 'Chậu Hoa Tươi',
        'CAY_CANH'         => 'Cây Cảnh',
        'HOA_SAP'          => 'Hoa Sáp',
        'HOA_GIAY_NHUN'    => 'Hoa Giấy Nhún',
        'TERRARIUM'        => 'Terrarium',
        'PHU_KIEN'         => 'Phụ Kiện',
        'QUA_TANG'         => 'Quà Tặng',
    ];
    $catEmoji = [
        'HOA_TUOI' => '🌹', 'HOA_GIA' => '💐', 'SAN_PHAM_PREMIUM' => '👑',
        'CHAU_HOA_GIA' => '🪴', 'CHAU_HOA_TUOI' => '🌷', 'CAY_CANH' => '🌿',
        'HOA_SAP' => '🕯️', 'HOA_GIAY_NHUN' => '🎀', 'TERRARIUM' => '🔮',
        'PHU_KIEN' => '🎁', 'QUA_TANG' => '🎀',
    ];
    $soLuong  = $sanPham->so_luong ?? 0;
    $catLabel = $categoryLabels[$sanPham->loai_san_pham] ?? $sanPham->loai_san_pham;
    $emoji    = $catEmoji[$sanPham->loai_san_pham] ?? '🌸';
@endphp

{{-- BREADCRUMB --}}
<div class="breadcrumb">
    <a onclick="navigateTo('{{ route('customer.dashboard') }}')">🏠 Trang chủ</a>
    <span class="sep">›</span>
    <a onclick="navigateTo('{{ route('customer.dashboard') }}?cat={{ $sanPham->loai_san_pham }}')">{{ $catLabel }}</a>
    <span class="sep">›</span>
    <span>{{ $sanPham->ten_san_pham }}</span>
</div>

{{-- DETAIL SECTION --}}
<div class="detail-wrap">

    {{-- LEFT: Image --}}
    <div>
        <div class="gallery-main">
            @if($sanPham->hinh_anh)
                <img src="{{ asset($sanPham->hinh_anh) }}" alt="{{ $sanPham->ten_san_pham }}"
                     onerror="this.style.display='none'; document.getElementById('imgFallback').style.display='flex';">
                <div class="gallery-placeholder" id="imgFallback" style="display:none;">{{ $emoji }}</div>
            @else
                <div class="gallery-placeholder">{{ $emoji }}</div>
            @endif
            <span class="badge-detail-status">Đang bán</span>
            <span class="badge-detail-type">{{ $catLabel }}</span>
        </div>
    </div>

    {{-- RIGHT: Info --}}
    <div class="info-panel">

        <div>
            <div class="info-category-tag">{{ $emoji }} {{ $catLabel }}</div>
        </div>

        <h1 class="info-title">{{ $sanPham->ten_san_pham }}</h1>

        {{-- Stock status --}}
        <div class="info-stock-row">
            @if($soLuong === 0)
                <span class="stock-badge no-stock">❌ Hết hàng</span>
            @elseif($soLuong <= 5)
                <span class="stock-badge low-stock">⚠️ Còn {{ $soLuong }} sản phẩm – Sắp hết!</span>
            @else
                <span class="stock-badge in-stock">✅ Còn hàng ({{ $soLuong }} sp)</span>
            @endif
        </div>

        {{-- Meta chips (bỏ mã SP) --}}
        <div class="meta-strip">
            <div class="meta-chip">📦 Số lượng: <strong>{{ $soLuong }}</strong></div>
            <div class="meta-chip">📂 Loại: <strong>{{ $catLabel }}</strong></div>
        </div>

        {{-- Bảng giá bán theo lô nhập --}}
        @php
            $loGia = $sanPham->chiTietNhaps ?? collect();
        @endphp
        @if($loGia->count() > 0)
        <div class="price-table-wrap">
            <div class="price-table-label">💰 Giá bán</div>
            <table class="price-table">
                <thead>
                    <tr>
                        <th>Giá bán</th>
                        <th>Còn lại</th>
                        <th>Chọn</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($loGia as $loIndex => $lo)
                    <tr class="{{ $lo->so_luong_con_lai <= 5 ? 'row-low' : '' }}"
                        id="lo-row-{{ $loIndex }}">
                        <td class="price-val">{{ number_format($lo->gia_ban, 0, ',', '.') }}₫</td>
                        <td>
                            <span class="stock-pill {{ $lo->so_luong_con_lai <= 5 ? 'low' : '' }}">
                                {{ $lo->so_luong_con_lai }} sp
                            </span>
                        </td>
                        <td>
                            <div class="lo-qty-row">
                                <span class="lo-qty-count" id="lo-qty-{{ $loIndex }}">0</span>
                                <button class="lo-plus-btn" onclick="addLo({{ $loIndex }}, {{ $lo->so_luong_con_lai }}, {{ $lo->gia_ban }})">+</button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Cart summary --}}
            <div class="cart-summary" id="cartSummary" style="display:none;">
                <div class="cart-summary-inner">
                    <div class="cart-summary-label">🛒 Đã chọn</div>
                    <div id="cartLines"></div>
                    <div class="cart-total-row">
                        <span>Tổng cộng:</span>
                        <span class="cart-total-price" id="cartTotal">0₫</span>
                    </div>
                    <button class="cart-reset-btn" onclick="resetCart()">✕ Xóa chọn</button>
                </div>
            </div>
        </div>
        @else
        <div class="price-table-wrap">
            <div class="price-table-label">💰 Giá bán</div>
            <p class="price-no-data">Chưa có thông tin giá. Vui lòng liên hệ để được tư vấn.</p>
        </div>
        @endif

        {{-- Description --}}
        @if($sanPham->mo_ta)
        <div class="info-desc">
            <div class="info-desc-label">📝 Mô tả sản phẩm</div>
            {{ $sanPham->mo_ta }}
        </div>
        @endif

        {{-- Login notice if guest --}}
        @guest
        <div class="login-notice">
            💡 <span>Vui lòng <a href="{{ route('login') }}">đăng nhập</a> để đặt hoa và theo dõi đơn hàng nhé!</span>
        </div>
        @endguest

        {{-- Action Buttons --}}
        @if($soLuong > 0)
        <div class="action-row">
            @auth
                <button class="btn-cart" id="btnAddCart" onclick="addToCart()" disabled>
                    🛒 Thêm vào giỏ hàng
                </button>
            @else
                <button class="btn-cart" onclick="navigateTo('{{ route('login') }}')">
                    🛒 Đăng nhập để đặt hoa
                </button>
            @endauth

            <a href="https://zalo.me/0357634696" target="_blank" class="btn-contact">
                💬 Tư vấn Zalo
            </a>
        </div>
        @else
        <div class="action-row">
            <a href="https://zalo.me/0357634696" target="_blank" class="btn-contact" style="flex:1; justify-content:center;">
                💬 Liên hệ để đặt trước qua Zalo
            </a>
        </div>
        @endif

    </div>
</div>

{{-- RELATED PRODUCTS --}}
@if($sanPhamLienQuan->count() > 0)
<div class="related-section">
    <div class="section-head">
        <h2 class="section-title">Sản Phẩm Liên Quan</h2>
        <span class="result-count">{{ $sanPhamLienQuan->count() }} sản phẩm</span>
    </div>
    <div class="related-grid">
        @foreach($sanPhamLienQuan as $sp)
        @php
            $spStock = $sp->so_luong ?? 0;
            $spEmoji = $catEmoji[$sp->loai_san_pham] ?? '🌸';
        @endphp
        <div class="product-card"
             onclick="navigateTo('{{ route('customer.san-pham.chi-tiet', $sp->ma_san_pham) }}')"
             style="cursor:pointer;">
            <div class="card-img-wrap">
                @if($sp->hinh_anh)
                    <img src="{{ asset($sp->hinh_anh) }}" alt="{{ $sp->ten_san_pham }}"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="card-img-placeholder" style="display:none;">{{ $spEmoji }}</div>
                @else
                    <div class="card-img-placeholder">{{ $spEmoji }}</div>
                @endif
                <span class="badge-status on-sale">Đang bán</span>
            </div>
            <div class="card-body">
                <div class="card-name">{{ $sp->ten_san_pham }}</div>
                <div class="card-footer">
                    <span class="card-stock {{ $spStock === 0 ? 'out' : ($spStock <= 5 ? 'low' : '') }}">
                        {{ $spStock === 0 ? 'Hết hàng' : 'Còn '.$spStock.' sp' }}
                    </span>
                    <button class="add-btn"
                            onclick="event.stopPropagation(); navigateTo('{{ route('customer.san-pham.chi-tiet', $sp->ma_san_pham) }}')">
                        Xem chi tiết
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
    const fmt = v => new Intl.NumberFormat('vi-VN').format(v) + '₫';

    const cart = {};

    function addLo(idx, maxQty, giaBan) {
        if (!cart[idx]) {
            cart[idx] = { qty: 0, maxQty, giaBan };
        }
        if (cart[idx].qty >= maxQty) return;
        cart[idx].qty++;
        document.getElementById('lo-qty-' + idx).textContent = cart[idx].qty;
        renderSummary();
    }

    function resetCart() {
        Object.keys(cart).forEach(idx => {
            cart[idx].qty = 0;
            document.getElementById('lo-qty-' + idx).textContent = 0;
        });
        renderSummary();
    }

    function renderSummary() {
        const summary  = document.getElementById('cartSummary');
        const linesEl  = document.getElementById('cartLines');
        const totalEl  = document.getElementById('cartTotal');
        const btnCart  = document.getElementById('btnAddCart');

        let totalQty   = 0;
        let totalPrice = 0;
        let linesHtml  = '';

        Object.entries(cart).forEach(([idx, item]) => {
            if (item.qty > 0) {
                const sub = item.qty * item.giaBan;
                totalQty   += item.qty;
                totalPrice += sub;
                linesHtml  += `<div class="cart-line">
                    <span>${fmt(item.giaBan)} × ${item.qty} sp</span>
                    <span>${fmt(sub)}</span>
                </div>`;
            }
        });

        if (totalQty > 0) {
            linesEl.innerHTML = linesHtml;
            totalEl.textContent = fmt(totalPrice);
            summary.style.display = 'block';
            if (btnCart) { btnCart.disabled = false; btnCart.textContent = `🛒 Thêm vào giỏ (${totalQty} sp)`; }
        } else {
            summary.style.display = 'none';
            if (btnCart) { btnCart.disabled = true; btnCart.textContent = '🛒 Thêm vào giỏ hàng'; }
        }
    }

    function addToCart() {
        let totalQty = Object.values(cart).reduce((s, i) => s + i.qty, 0);
        showToast('🛒 Đã thêm ' + totalQty + ' sản phẩm vào giỏ hàng!');
    }
</script>
@endsection