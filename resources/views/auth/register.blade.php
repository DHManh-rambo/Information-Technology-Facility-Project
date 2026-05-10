<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký tài khoản - RoseShop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        :root { --rose: #FF69B4; --rose-light: #FFF5F7; --rose-dark: #C71585; }

        body {
            background-color: var(--rose-light);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .register-card {
            background: #fff;
            border-radius: 20px;
            padding: 40px 36px;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 8px 30px rgba(255,105,180,0.12);
        }

        .register-card h2 {
            color: var(--rose);
            font-weight: 800;
            font-size: 1.6rem;
            letter-spacing: 1px;
            text-align: center;
            margin-bottom: 28px;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.88rem;
            color: #333;
            margin-bottom: 5px;
        }

        .form-label .req { color: var(--rose); }

        .form-control {
            border: 1.5px solid #e0e0e0;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.95rem;
            transition: border-color .2s;
        }

        .form-control:focus {
            border-color: var(--rose);
            box-shadow: 0 0 0 3px rgba(255,105,180,0.12);
            outline: none;
        }

        .form-control.is-invalid { border-color: #dc3545; }

        .input-group .form-control { border-right: none; border-radius: 10px 0 0 10px; }
        .input-group .btn-eye {
            border: 1.5px solid #e0e0e0;
            border-left: none;
            border-radius: 0 10px 10px 0;
            background: #fff;
            color: #aaa;
            padding: 0 14px;
            transition: color .2s;
        }
        .input-group .btn-eye:hover { color: var(--rose); }
        .input-group:focus-within .btn-eye { border-color: var(--rose); }

        .error-msg {
            color: var(--rose);
            font-size: 0.8rem;
            margin-top: 4px;
        }

        .btn-register {
            background: var(--rose);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-size: 1rem;
            font-weight: 700;
            width: 100%;
            margin-top: 8px;
            letter-spacing: 0.5px;
            transition: background .2s;
        }

        .btn-register:hover { background: var(--rose-dark); color: #fff; }

        .login-link {
            text-align: center;
            margin-top: 16px;
            font-size: 0.88rem;
            color: #666;
        }

        .login-link a {
            color: var(--rose);
            font-weight: 600;
            text-decoration: none;
        }

        .login-link a:hover { text-decoration: underline; }

        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0px 1000px #fff inset !important;
            box-shadow: 0 0 0px 1000px #fff inset !important;
        }
    </style>
</head>
<body>
    <div class="register-card">
        <h2>ĐĂNG KÝ TÀI KHOẢN</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- Tên đăng nhập --}}
            <div class="mb-3">
                <label class="form-label">Tên đăng nhập <span class="req">*</span></label>
                <input type="text" name="ten_dang_nhap"
                    class="form-control @error('ten_dang_nhap') is-invalid @enderror"
                    value="{{ old('ten_dang_nhap') }}" required>
                @error('ten_dang_nhap')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tên người dùng --}}
            <div class="mb-3">
                <label class="form-label">Tên người dùng <span class="req">*</span></label>
                <input type="text" name="name"
                    class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name') }}" required>
                @error('name')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <label class="form-label">Email <span class="req">*</span></label>
                <input type="email" name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}" required>
                @error('email')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            {{-- Số điện thoại --}}
            <div class="mb-3">
                <label class="form-label">Số điện thoại <span class="req">*</span></label>
                <input type="text" name="so_dien_thoai"
                    class="form-control @error('so_dien_thoai') is-invalid @enderror"
                    value="{{ old('so_dien_thoai') }}" required maxlength="10">
                @error('so_dien_thoai')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            {{-- Mật khẩu --}}
            <div class="mb-3">
                <label class="form-label">Mật khẩu <span class="req">*</span></label>
                <div class="input-group">
                    <input type="password" name="password" id="password"
                        class="form-control @error('password') is-invalid @enderror" required>
                    <button type="button" class="btn-eye" onclick="togglePass('password', this)">
                        <i class="fa fa-eye"></i>
                    </button>
                </div>
                @error('password')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            {{-- Xác nhận mật khẩu --}}
            <div class="mb-4">
                <label class="form-label">Xác nhận mật khẩu <span class="req">*</span></label>
                <div class="input-group">
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="form-control" required>
                    <button type="button" class="btn-eye" onclick="togglePass('password_confirmation', this)">
                        <i class="fa fa-eye"></i>
                    </button>
                </div>
                @error('password_confirmation')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-register">Đăng ký</button>
        </form>

        <div class="login-link">
            Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập ngay</a>
        </div>
    </div>

    <script>
        function togglePass(id, btn) {
            const input = document.getElementById(id);
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>
</body>
</html>
