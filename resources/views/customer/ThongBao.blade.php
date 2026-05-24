{{-- resources/views/Customer/ThongBao.blade.php --}}
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/Customer/ThongBao.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Thông báo đơn hàng · FlowerStore</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
</head>
<body>

<header class="topbar">
    <span class="topbar-brand">🌸 FlowerStore</span>
    <a href="{{ url()->previous() }}" class="back-link">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</header>

<div class="page-wrap">
    <div class="page-title">
        <i class="fas fa-bell" style="color:var(--blue); font-size:20px; margin-right:8px"></i>
        Thông báo đơn hàng
    </div>
    <div class="page-sub">Ấn × để xóa thông báo sau khi đã nhận hàng.</div>

    <div class="notif-list" id="notif-list">
        @forelse($thongBaos as $tb)
            <div class="notif-card" id="notif-{{ $tb['id'] }}">
                <div class="notif-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="notif-body">
                    <div class="notif-title">
                        <i class="fas fa-truck"></i> Shipper đã đến nơi
                    </div>
                    <div class="notif-content">{{ $tb['noi_dung'] }}</div>
                    <div class="notif-time">
                        <i class="fas fa-clock"></i> {{ $tb['thoi_gian'] }}
                    </div>
                </div>
                <button class="btn-close-notif"
                        onclick="xoaThongBao('{{ $tb['id'] }}')"
                        title="Đóng thông báo">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @empty
            <div class="empty-state" id="empty-state">
                <i class="fas fa-bell-slash"></i>
                <p>Không có thông báo nào</p>
                <small>Khi shipper đến nơi giao, bạn sẽ thấy thông báo ở đây.</small>
            </div>
        @endforelse
    </div>
</div>

<div id="toast"></div>

<script>
const _csrf = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

function showToast(msg, type = '') {
    const el = document.getElementById('toast');
    el.textContent = msg; el.className = 'show ' + type;
    setTimeout(() => { el.className = ''; }, 3000);
}

function xoaThongBao(id) {
    const card = document.getElementById('notif-' + id);
    if (!card) return;

    fetch(`/customer/thong-bao/${id}/xoa`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': _csrf },
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            card.classList.add('removing');
            setTimeout(() => {
                card.remove();
                const list = document.getElementById('notif-list');
                if (list && list.querySelectorAll('.notif-card').length === 0) {
                    list.innerHTML = `
                        <div class="empty-state">
                            <i class="fas fa-bell-slash"></i>
                            <p>Không có thông báo nào</p>
                            <small>Khi shipper đến nơi giao, bạn sẽ thấy thông báo ở đây.</small>
                        </div>`;
                }
            }, 300);
        } else {
            showToast('Không thể xóa thông báo.', 'error');
        }
    })
    .catch(() => showToast('Có lỗi xảy ra.', 'error'));
}
</script>
</body>
</html>