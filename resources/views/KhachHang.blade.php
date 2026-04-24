<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/KhachHang.css') }}">
    <title>Quản lý khách hàng</title>

</head>
<body>
<div class="container">
    <h1> DANH SÁCH KHÁCH HÀNG</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <div class="sort-buttons">
        <a href="{{ route('khach-hang.index', ['sort' => 'desc']) }}" class="btn {{ $sort == 'desc' ? 'btn-secondary' : '' }}"> Điểm từ cao → thấp</a>
        <a href="{{ route('khach-hang.index', ['sort' => 'asc']) }}" class="btn {{ $sort == 'asc' ? 'btn-secondary' : '' }}"> Điểm từ thấp → cao</a>
    </div>

    <div id="editFormContainer" class="edit-form-container">
        <h3> Chỉnh sửa thông tin khách hàng</h3>
        <form id="editForm" method="POST" action="">
            @csrf
            @method('PUT')
            <input type="hidden" name="sort" value="{{ $sort }}">

            <div class="form-group">
                <label>Tên khách hàng:</label>
                <input type="text" name="ten_khach_hang" id="edit_ten" required>
            </div>
            <div class="form-group">
                <label>Số điện thoại:</label>
                <input type="text" name="so_dien_thoai" id="edit_sdt" required pattern="\d{10}" title="10 chữ số">
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" id="edit_email" required>
            </div>
            <div class="form-group">
                <label>Địa chỉ:</label>
                <textarea name="dia_chi" id="edit_diachi"></textarea>
            </div>
            <div class="form-group">
                <label>Điểm tích lũy:</label>
                <input type="number" name="diem_tich_luy" id="edit_diem" min="0">
            </div>
            <div class="form-group">
                <button type="submit"> Lưu thay đổi</button>
                <button type="button" id="cancelEdit" class="btn-secondary"> Hủy</button>
            </div>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Tên khách hàng</th>
                <th>Số điện thoại</th>
                <th>Email</th>
                <th>Địa chỉ</th>
                <th>Điểm tích lũy</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($khachHangs as $index => $kh)
            <tr id="row-{{ $kh->ma_khach_hang }}">
                <td>{{ $index + 1 }}</td>
                <td>{{ $kh->ten_khach_hang }}</td>
                <td>{{ $kh->so_dien_thoai }}</td>
                <td>{{ $kh->email }}</td>
                <td>{{ $kh->dia_chi }}</td>
                <td>{{ $kh->diem_tich_luy }}</td>
                <td>
                    <button class="btn-edit" data-id="{{ $kh->ma_khach_hang }}"
                            data-ten="{{ $kh->ten_khach_hang }}"
                            data-sdt="{{ $kh->so_dien_thoai }}"
                            data-email="{{ $kh->email }}"
                            data-diachi="{{ $kh->dia_chi }}"
                            data-diem="{{ $kh->diem_tich_luy }}">
                         Sửa
                    </button>
                    <form action="{{ route('khach-hang.destroy', $kh->ma_khach_hang) }}" method="POST" style="display:inline-block;" 
                          onsubmit="return confirm('Bạn có chắc muốn xóa khách hàng này? Hành động sẽ xóa luôn tài khoản đăng nhập.')">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="sort" value="{{ $sort }}">
                        <button type="submit" class="btn-danger"> Xóa</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7">Không có khách hàng nào.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    const editContainer = document.getElementById('editFormContainer');
    const editForm = document.getElementById('editForm');
    const cancelBtn = document.getElementById('cancelEdit');

    const inputTen = document.getElementById('edit_ten');
    const inputSdt = document.getElementById('edit_sdt');
    const inputEmail = document.getElementById('edit_email');
    const inputDiachi = document.getElementById('edit_diachi');
    const inputDiem = document.getElementById('edit_diem');

    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const ten = this.dataset.ten;
            const sdt = this.dataset.sdt;
            const email = this.dataset.email;
            const diachi = this.dataset.diachi;
            const diem = this.dataset.diem;

            inputTen.value = ten;
            inputSdt.value = sdt;
            inputEmail.value = email;
            inputDiachi.value = diachi || '';
            inputDiem.value = diem;

            
            editForm.action = "{{ url('khach-hang') }}/" + id;

            
            editContainer.style.display = 'block';
            
            editContainer.scrollIntoView({ behavior: 'smooth' });
        });
    });

   
    cancelBtn.addEventListener('click', function() {
        editContainer.style.display = 'none';
        editForm.reset();
    });

    
</script>
</body>
</html>