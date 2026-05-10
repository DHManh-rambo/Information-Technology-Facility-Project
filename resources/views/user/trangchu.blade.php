<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RoseShop - Trang chủ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        :root { --rose: #FF69B4; --rose-light: #FFF5F7; --rose-dark: #C71585; }

        body { background-color: var(--rose-light); font-family: 'Segoe UI', sans-serif; }

        .navbar {
            background: #fff;
            box-shadow: 0 2px 12px rgba(255,105,180,0.1);
            padding: 14px 0;
        }

        .navbar-brand {
            color: var(--rose) !important;
            font-weight: 800;
            font-size: 1.4rem;
        }

        .btn-login {
            border: 2px solid var(--rose);
            color: var(--rose);
            border-radius: 10px;
            font-weight: 600;
            padding: 7px 20px;
            background: transparent;
            transition: .2s;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .btn-login:hover { background: var(--rose); color: #fff; }

        .btn-register {
            background: var(--rose);
            color: #fff;
            border-radius: 10px;
            font-weight: 600;
            padding: 7px 20px;
            border: 2px solid var(--rose);
            transition: .2s;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .btn-register:hover { background: var(--rose-dark); border-color: var(--rose-dark); color: #fff; }

        .hero {
            text-align: center;
            padding: 80px 20px 60px;
        }

        .hero h1 {
            color: var(--rose);
            font-weight: 800;
            font-size: 2.5rem;
            margin-bottom: 12px;
        }

        .hero p {
            color: #888;
            font-size: 1.1rem;
            margin-bottom: 32px;
        }

        .user-greeting {
            color: var(--rose-dark);
            font-weight: 600;
            margin-right: 12px;
            font-size: 0.9rem;
        }

        .btn-logout {
            border: 2px solid #dc3545;
            color: #dc3545;
            border-radius: 10px;
            font-weight: 600;
            padding: 7px 20px;
            background: transparent;
            font-size: 0.9rem;
            transition: .2s;
        }

        .btn-logout:hover { background: #dc3545; color: #fff; }
    </style>
</head>
<body>

{{-- Navbar --}}
<nav class="navbar">
    <div class="container d-flex align-items-center justify-content-between">
        <a class="navbar-brand" href="{{ route('user.home') }}">
            🌷 RoseShop 🌷
        </a>

        <div class="d-flex align-items-center gap-2">
            @auth
                <span class="user-greeting">
                    <i class="fa-solid fa-circle-user me-1"></i>{{ auth()->user()->name }}
                </span>

                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('dashboard') }}" class="btn-register">
                        <i class="fa-solid fa-chart-pie me-1"></i>Quản trị
                    </a>
                @endif

                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="fa-solid fa-right-from-bracket me-1"></i>Đăng xuất
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn-login">Đăng nhập</a>
                <a href="{{ route('register') }}" class="btn-register">Đăng ký</a>
            @endauth
        </div>
    </div>
</nav>

{{-- Hero --}}
<div class="hero">
    <h1>Chào mừng đến với RoseShop 🌸</h1>
    <p>Cửa hàng hoa tươi đẹp nhất dành cho bạn</p>

    @guest
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('login') }}" class="btn-login px-4 py-2" style="font-size:1rem">
                <i class="fa-solid fa-right-to-bracket me-2"></i>Đăng nhập
            </a>
            <a href="{{ route('register') }}" class="btn-register px-4 py-2" style="font-size:1rem">
                <i class="fa-solid fa-user-plus me-2"></i>Đăng ký ngay
            </a>
        </div>
    @endguest
</div>

</body>
</html>
