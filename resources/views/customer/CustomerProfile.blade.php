<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hồ sơ cá nhân · Cửa Hàng Hoa</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Be+Vietnam+Pro:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary:      #e8436a;
            --primary-dark: #c0294e;
            --primary-soft: #fdf2f5;
            --accent:       #f97316;
            --gold:         #f59e0b;
            --green:        #16a34a;
            --red:          #dc2626;
            --gray:         #6b7280;
            --gray-light:   #f3f4f6;
            --border:       #e5e7eb;
            --dark:         #1f2937;
            --white:        #ffffff;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Be Vietnam Pro', sans-serif;
            background: #faf5f7;
            color: var(--dark);
            min-height: 100vh;
        }

        /* ── TOPBAR ── */
        .topbar {
            background: var(--primary);
            color: #fff;
            padding: 10px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .topbar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem;
            letter-spacing: .02em;
        }
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
            font-size: 0.83rem;
        }
        .topbar-right a {
            color: rgba(255,255,255,.85);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: color .2s;
        }
        .topbar-right a:hover { color: #fff; }
        .logout-form button {
            background: none;
            border: none;
            color: rgba(255,255,255,.85);
            font-size: 0.83rem;
            font-family: inherit;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: color .2s;
        }
        .logout-form button:hover { color: #fff; }

        /* ── PAGE WRAP ── */
        .profile-page {
            max-width: 860px;
            margin: 0 auto;
            padding: 32px 20px 60px;
        }

        /* back link */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            color: var(--primary);
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            margin-bottom: 20px;
            transition: opacity .2s;
        }
        .back-link:hover { opacity: .75; }

        /* page title */
        .page-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.6rem;
            color: var(--primary-dark);
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 24px;
        }

        /* ── ALERTS ── */
        .alert {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 0.85rem;
            margin-bottom: 18px;
        }
        .alert-success {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        .alert-error {
            background: #fef2f2;
            color: var(--red);
            border: 1px solid #fecaca;
        }

        /* ── TABS ── */
        .tab-bar {
            display: flex;
            gap: 4px;
            background: var(--gray-light);
            border-radius: 14px;
            padding: 5px;
            margin-bottom: 24px;
            width: fit-content;
        }
        .tab-btn {
            background: none;
            border: none;
            font-family: inherit;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--gray);
            padding: 9px 22px;
            border-radius: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 7px;
            transition: background .2s, color .2s;
        }
        .tab-btn.active {
            background: var(--white);
            color: var(--primary);
            box-shadow: 0 1px 6px rgba(0,0,0,.10);
        }
        .tab-btn:not(.active):hover { color: var(--primary-dark); }

        /* ── TAB PANES ── */
        .tab-pane { display: none; }
        .tab-pane.active { display: block; }

        /* ── CARD ── */
        .pcard {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 2px 16px rgba(232,67,106,.08);
            overflow: hidden;
        }
        .pcard-header {
            background: linear-gradient(135deg, var(--primary) 0%, #f0607f 100%);
            color: #fff;
            padding: 16px 24px;
            font-size: 0.92rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
            letter-spacing: .03em;
        }
        .pcard-body { padding: 28px 28px 32px; }

        /* ── FORM GRID ── */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px 24px;
        }
        @media (max-width: 600px) {
            .form-grid { grid-template-columns: 1fr; }
            .tab-bar { width: 100%; }
            .tab-btn { flex: 1; justify-content: center; }
        }

        .fgroup { display: flex; flex-direction: column; gap: 5px; }
        .fgroup.full { grid-column: 1 / -1; }
        .fgroup.span2 { grid-column: 1 / -1; }

        .fgroup label {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--gray);
            text-transform: uppercase;
            letter-spacing: .05em;
        }
        .fgroup input,
        .fgroup textarea {
            border: 1.5px solid var(--border);
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.88rem;
            font-family: inherit;
            color: var(--dark);
            background: var(--white);
            transition: border-color .2s, box-shadow .2s;
            outline: none;
        }
        .fgroup input:focus,
        .fgroup textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(232,67,106,.12);
        }
        .fgroup input.is-invalid { border-color: var(--red); }
        .fgroup textarea { resize: vertical; min-height: 80px; }

        .readonly-field {
            background: var(--gray-light) !important;
            color: var(--gray) !important;
            cursor: default;
        }

        .invalid-msg {
            font-size: 0.75rem;
            color: var(--red);
            margin-top: 2px;
        }

        /* diem tich luy badge */
        .diem-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            border: 1px solid #fbbf24;
            border-radius: 10px;
            padding: 10px 16px;
            font-size: 0.9rem;
            font-weight: 700;
            color: #92400e;
            margin-top: 4px;
        }
        .diem-badge .diem-val {
            font-size: 1.2rem;
            color: #b45309;
        }

        /* password wrap */
        .pwd-wrap {
            position: relative;
        }
        .pwd-wrap input { width: 100%; padding-right: 44px; }
        .pwd-eye {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--gray);
            cursor: pointer;
            padding: 4px;
            font-size: 0.85rem;
            transition: color .2s;
        }
        .pwd-eye:hover { color: var(--primary); }

        /* submit button */
        .btn-submit {
            margin-top: 28px;
            background: linear-gradient(135deg, var(--primary), #f0607f);
            color: #fff;
            border: none;
            border-radius: 999px;
            padding: 12px 32px;
            font-size: 0.9rem;
            font-family: inherit;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 14px rgba(232,67,106,.35);
            transition: transform .2s, box-shadow .2s;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(232,67,106,.45);
        }

        /* strength meter */
        .strength-bar {
            height: 4px;
            border-radius: 4px;
            background: var(--border);
            margin-top: 6px;
            overflow: hidden;
        }
        .strength-fill {
            height: 100%;
            width: 0%;
            border-radius: 4px;
            transition: width .3s, background .3s;
        }
        .strength-label {
            font-size: 0.72rem;
            color: var(--gray);
            margin-top: 3px;
        }
    </style>
</head>
<body>

{{-- TOPBAR --}}
<div class="topbar">
    <span class="topbar-brand">🌸 Hoa Tươi Shop</span>
    <div class="topbar-right">
        <span><i class="fas fa-user"></i> {{ $user->ten_dang_nhap }}</span>
        <form class="logout-form" method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"><i class="fas fa-sign-out-alt"></i> Đăng xuất</button>
        </form>
    </div>
</div>

<div class="profile-page">

    <a href="{{ route('customer.dashboard') }}" class="back-link">
        <i class="fas fa-arrow-left"></i> Quay lại cửa hàng
    </a>

    <div class="page-title">
        <i class="fas fa-user-circle"></i> Hồ sơ cá nhân
    </div>

    {{-- Flash --}}
    @if(session('success_info'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success_info') }}
        </div>
    @endif
    @if(session('success_password'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success_password') }}
        </div>
    @endif

    {{-- TABS --}}
    <div class="tab-bar">
        <button class="tab-btn {{ ($errors->has('mat_khau_cu') || $errors->has('mat_khau_moi') || session('tab') === 'password') ? '' : 'active' }}"
                id="tab-info-btn" onclick="switchTab('info')">
            <i class="fas fa-id-card"></i> Thông tin cá nhân
        </button>
        <button class="tab-btn {{ ($errors->has('mat_khau_cu') || $errors->has('mat_khau_moi') || session('tab') === 'password') ? 'active' : '' }}"
                id="tab-password-btn" onclick="switchTab('password')">
            <i class="fas fa-lock"></i> Đổi mật khẩu
        </button>
    </div>

    {{-- ════════════════════════════════════
         TAB 1: Thông tin cá nhân
    ════════════════════════════════════ --}}
    <div class="tab-pane {{ ($errors->has('mat_khau_cu') || $errors->has('mat_khau_moi') || session('tab') === 'password') ? '' : 'active' }}"
         id="tab-info">
        <div class="pcard">
            <div class="pcard-header">
                <i class="fas fa-id-card"></i> Thông tin cá nhân
            </div>
            <div class="pcard-body">

                {{-- Lỗi validation thông tin --}}
                @if($errors->has('ten_khach_hang') || $errors->has('so_dien_thoai') || $errors->has('email') || $errors->has('dia_chi'))
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <ul style="margin:0;padding-left:1.2rem;">
                            @foreach(['ten_khach_hang','so_dien_thoai','email','dia_chi'] as $field)
                                @if($errors->has($field))
                                    <li>{{ $errors->first($field) }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('customer.profile.update') }}">
                    @csrf
                    @method('PATCH')

                    <div class="form-grid">
                        {{-- Tên đăng nhập (readonly) --}}
                        <div class="fgroup">
                            <label><i class="fas fa-at" style="font-size:.7rem;"></i> Tên đăng nhập</label>
                            <input type="text" value="{{ $user->ten_dang_nhap }}" class="readonly-field" readonly>
                        </div>

                        {{-- Họ tên --}}
                        <div class="fgroup">
                            <label><i class="fas fa-user" style="font-size:.7rem;"></i> Họ tên *</label>
                            <input type="text" name="ten_khach_hang"
                                   value="{{ old('ten_khach_hang', $khachHang->ten_khach_hang) }}"
                                   class="{{ $errors->has('ten_khach_hang') ? 'is-invalid' : '' }}"
                                   placeholder="Nhập họ và tên"
                                   required>
                            @error('ten_khach_hang')
                                <div class="invalid-msg">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Số điện thoại --}}
                        <div class="fgroup">
                            <label><i class="fas fa-phone" style="font-size:.7rem;"></i> Số điện thoại *</label>
                            <input type="text" name="so_dien_thoai"
                                   value="{{ old('so_dien_thoai', $khachHang->so_dien_thoai) }}"
                                   maxlength="10"
                                   oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                                   placeholder="0xxxxxxxxx"
                                   class="{{ $errors->has('so_dien_thoai') ? 'is-invalid' : '' }}"
                                   required>
                            @error('so_dien_thoai')
                                <div class="invalid-msg">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="fgroup">
                            <label><i class="fas fa-envelope" style="font-size:.7rem;"></i> Email *</label>
                            <input type="email" name="email"
                                   value="{{ old('email', $khachHang->email) }}"
                                   placeholder="example@email.com"
                                   class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                                   required>
                            @error('email')
                                <div class="invalid-msg">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Địa chỉ (full width) --}}
                        <div class="fgroup span2">
                            <label><i class="fas fa-map-marker-alt" style="font-size:.7rem;"></i> Địa chỉ</label>
                            <textarea name="dia_chi"
                                      placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành phố"
                                      class="{{ $errors->has('dia_chi') ? 'is-invalid' : '' }}">{{ old('dia_chi', $khachHang->dia_chi) }}</textarea>
                            @error('dia_chi')
                                <div class="invalid-msg">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Điểm tích lũy (readonly, badge) --}}
                        <div class="fgroup">
                            <label><i class="fas fa-star" style="font-size:.7rem;"></i> Điểm tích lũy</label>
                            <div class="diem-badge">
                                <i class="fas fa-coins"></i>
                                <span class="diem-val">{{ number_format($khachHang->diem_tich_luy) }}</span>
                                <span style="font-weight:400;font-size:.78rem;">điểm</span>
                            </div>
                        </div>

                        {{-- Vai trò (readonly) --}}
                        <div class="fgroup">
                            <label><i class="fas fa-shield-alt" style="font-size:.7rem;"></i> Loại tài khoản</label>
                            <input type="text" value="Khách hàng" class="readonly-field" readonly>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Lưu thông tin
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════
         TAB 2: Đổi mật khẩu
    ════════════════════════════════════ --}}
    <div class="tab-pane {{ ($errors->has('mat_khau_cu') || $errors->has('mat_khau_moi') || session('tab') === 'password') ? 'active' : '' }}"
         id="tab-password">
        <div class="pcard">
            <div class="pcard-header">
                <i class="fas fa-lock"></i> Đổi mật khẩu
            </div>
            <div class="pcard-body">

                @if($errors->has('mat_khau_cu') || $errors->has('mat_khau_moi'))
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <ul style="margin:0;padding-left:1.2rem;">
                            @foreach(['mat_khau_cu','mat_khau_moi'] as $field)
                                @if($errors->has($field))
                                    <li>{{ $errors->first($field) }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('customer.profile.password') }}">
                    @csrf
                    @method('PATCH')

                    <div class="form-grid" style="max-width:440px; grid-template-columns:1fr;">

                        {{-- Mật khẩu hiện tại --}}
                        <div class="fgroup">
                            <label>Mật khẩu hiện tại *</label>
                            <div class="pwd-wrap">
                                <input type="password" name="mat_khau_cu" id="mat_khau_cu"
                                       autocomplete="current-password"
                                       placeholder="Nhập mật khẩu hiện tại"
                                       class="{{ $errors->has('mat_khau_cu') ? 'is-invalid' : '' }}"
                                       required>
                                <button type="button" class="pwd-eye" onclick="togglePwd('mat_khau_cu',this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('mat_khau_cu')
                                <div class="invalid-msg">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Mật khẩu mới --}}
                        <div class="fgroup">
                            <label>Mật khẩu mới * <span style="color:var(--gray);font-weight:400;">(ít nhất 6 ký tự)</span></label>
                            <div class="pwd-wrap">
                                <input type="password" name="mat_khau_moi" id="mat_khau_moi"
                                       autocomplete="new-password"
                                       placeholder="Tối thiểu 6 ký tự"
                                       minlength="6"
                                       oninput="checkStrength(this.value)"
                                       class="{{ $errors->has('mat_khau_moi') ? 'is-invalid' : '' }}"
                                       required>
                                <button type="button" class="pwd-eye" onclick="togglePwd('mat_khau_moi',this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            {{-- Strength meter --}}
                            <div class="strength-bar"><div class="strength-fill" id="strengthFill"></div></div>
                            <div class="strength-label" id="strengthLabel"></div>
                            @error('mat_khau_moi')
                                <div class="invalid-msg">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Xác nhận mật khẩu mới --}}
                        <div class="fgroup">
                            <label>Xác nhận mật khẩu mới *</label>
                            <div class="pwd-wrap">
                                <input type="password" name="mat_khau_moi_confirmation" id="mat_khau_moi_confirmation"
                                       autocomplete="new-password"
                                       placeholder="Nhập lại mật khẩu mới"
                                       minlength="6"
                                       oninput="checkMatch()"
                                       required>
                                <button type="button" class="pwd-eye" onclick="togglePwd('mat_khau_moi_confirmation',this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="strength-label" id="matchLabel"></div>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fas fa-key"></i> Đổi mật khẩu
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
    function switchTab(tab) {
        document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.getElementById('tab-' + tab).classList.add('active');
        document.getElementById('tab-' + tab + '-btn').classList.add('active');
    }

    function togglePwd(fieldId, btn) {
        const input = document.getElementById(fieldId);
        const icon  = btn.querySelector('i');
        input.type  = input.type === 'password' ? 'text' : 'password';
        icon.className = input.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
    }

    function checkStrength(val) {
        const fill  = document.getElementById('strengthFill');
        const label = document.getElementById('strengthLabel');
        let score = 0;
        if (val.length >= 6)  score++;
        if (val.length >= 10) score++;
        if (/[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        const levels = [
            { w: '0%',   c: '',         t: '' },
            { w: '25%',  c: '#ef4444',  t: 'Rất yếu' },
            { w: '50%',  c: '#f97316',  t: 'Yếu' },
            { w: '75%',  c: '#f59e0b',  t: 'Trung bình' },
            { w: '90%',  c: '#22c55e',  t: 'Mạnh' },
            { w: '100%', c: '#16a34a',  t: 'Rất mạnh 🔒' },
        ];

        const lvl = val.length === 0 ? levels[0] : levels[Math.min(score, 5)];
        fill.style.width      = lvl.w;
        fill.style.background = lvl.c;
        label.textContent     = lvl.t;
        label.style.color     = lvl.c;

        checkMatch();
    }

    function checkMatch() {
        const moi   = document.getElementById('mat_khau_moi').value;
        const conf  = document.getElementById('mat_khau_moi_confirmation').value;
        const label = document.getElementById('matchLabel');
        if (!conf) { label.textContent = ''; return; }
        if (moi === conf) {
            label.textContent = '✓ Mật khẩu khớp';
            label.style.color = 'var(--green)';
        } else {
            label.textContent = '✗ Mật khẩu chưa khớp';
            label.style.color = 'var(--red)';
        }
    }
</script>
</body>
</html>