<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - RoseShop</title>
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

        .login-card {
            background: #fff;
            border-radius: 20px;
            padding: 40px 36px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 8px 30px rgba(255,105,180,0.12);
        }

        .login-card h2 {
            color: var(--rose);
            font-weight: 800;
            font-size: 1.7rem;
            letter-spacing: 1px;
            text-align: center;
            margin-bottom: 32px;
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
            padding: 11px 14px;
            font-size: 0.95rem;
            transition: border-color .2s;
        }

        .form-control::placeholder { color: #bbb; }

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
            color: #dc3545;
            font-size: 0.8rem;
            margin-top: 4px;
        }

        .status-msg {
            background: #fff3f8;
            border: 1px solid var(--rose);
            color: var(--rose-dark);
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.88rem;
            margin-bottom: 16px;
            text-align: center;
        }

        .btn-login {
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

        .btn-login:hover { background: var(--rose-dark); color: #fff; }

        .register-link {
            text-align: center;
            margin-top: 16px;
            font-size: 0.88rem;
            color: #666;
        }

        .register-link a {
            color: var(--rose);
            font-weight: 600;
            text-decoration: none;
        }

        .register-link a:hover { text-decoration: underline; }

        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0px 1000px #fff inset !important;
            box-shadow: 0 0 0px 1000px #fff inset !important;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>ĐĂNG NHẬP</h2>

        @if (session('status'))
            <div class="status-msg">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Tên đăng nhập --}}
            <div class="mb-3">
                <label class="form-label">Email đăng nhập <span class="req">*</span></label>
                <input type="text" name="login"
                    class="form-control @error('login') is-invalid @enderror"
                    value="{{ old('login') }}"
                    placeholder="Nhập tên đăng nhập"
                    required autofocus>
                @error('login')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            {{-- Mật khẩu --}}
            <div class="mb-4">
                <label class="form-label">Mật khẩu <span class="req">*</span></label>
                <div class="input-group">
                    <input type="password" name="password" id="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Nhập mật khẩu (tối thiểu 6 chữ số)"
                        required>
                    <button type="button" class="btn-eye" onclick="togglePass()">
                        <i class="fa fa-eye" id="eye-icon"></i>
                    </button>
                </div>
                @error('password')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-login">Đăng nhập</button>
        </form>

        <div class="register-link">
            Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký ngay</a>
        </div>
    </div>

    <script>
        function togglePass() {
            const input = document.getElementById('password');
            const icon  = document.getElementById('eye-icon');
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
