<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/NguoiDung.css') }}">
    <title>Quản lý người dùng</title>
    
</head>
<body>
<div class="container">
    <h2> Quản lý người dùng</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-error">
            @foreach($errors->all() as $err) {{ $err }} <br> @endforeach
        </div>
    @endif

    
    <div class="card">
        <div class="card-header" id="formTitle">Thêm người dùng</div>
        <div class="card-body">
            <form id="userForm" action="{{ route('nguoi-dung.store') }}" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="user_id" id="userId" value="">

                <div class="form-row">
                    <div class="form-group">
                        <label>Tên đăng nhập *</label>
                        <input type="text" name="ten_dang_nhap" id="ten_dang_nhap" required>
                    </div>
                    <div class="form-group">
                        <label>Mật khẩu</label>
                        <div class="password-wrapper">
                            <input type="password" name="mat_khau" id="mat_khau">
                            <button type="button" class="toggle-pwd" onclick="togglePassword('mat_khau')">hiện/ẩn</button>
                        </div>
                        <small>Để trống nếu không muốn đổi khi sửa</small>
                    </div>
                    <div class="form-group">
                        <label>Vai trò *</label>
                        <select name="vai_tro" id="vai_tro" required>
                            <option value="">-- Chọn vai trò --</option>
                            <option value="KHACH_HANG">Khách hàng</option>
                            <option value="NHAN_VIEN">Nhân viên</option>
                            <option value="SHIPPER">Shipper</option>
                        </select>
                    </div>
                </div>

               
                <div id="khachHangFields" style="display: none;">
                    <hr>
                    <h4>Thông tin khách hàng</h4>
                    <div class="form-row">
                        <div class="form-group"><label>Họ tên *</label><input type="text" name="ten_khach_hang" id="ten_khach_hang"></div>
                        <div class="form-group"><label>Số điện thoại *</label><input type="text" name="so_dien_thoai" id="so_dien_thoai"></div>
                        <div class="form-group"><label>Email *</label><input type="email" name="email" id="email"></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group"><label>Địa chỉ</label><textarea name="dia_chi" id="dia_chi" rows="2"></textarea></div>
                        <div class="form-group"><label>Điểm tích lũy</label><input type="number" name="diem_tich_luy" id="diem_tich_luy" value="0"></div>
                    </div>
                </div>

                
                <div id="nhanVienFields" style="display: none;">
                    <hr>
                    <h4>Thông tin nhân viên</h4>
                    <div class="form-row">
                        <div class="form-group"><label>Họ tên *</label><input type="text" name="ten_nhan_vien" id="ten_nhan_vien"></div>
                        <div class="form-group"><label>Số điện thoại *</label><input type="text" name="so_dien_thoai_nv" id="so_dien_thoai_nv"></div>
                        <div class="form-group"><label>Email *</label><input type="email" name="email_nv" id="email_nv"></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group"><label>Chức vụ *</label>
                            <select name="chuc_vu" id="chuc_vu">
                                <option value="CSKH">CSKH</option><option value="VAN_HANH">Vận hành</option>
                                <option value="THIET_KE">Thiết kế</option><option value="ONLINE">Online</option>
                                <option value="SHIPPER">Shipper</option><option value="KHAC">Khác</option>
                            </select>
                        </div>
                        <div class="form-group"><label>Lương</label><input type="number" step="100000" name="luong" id="luong"></div>
                        <div class="form-group"><label>Công việc</label><input type="text" name="cong_viec" id="cong_viec"></div>
                    </div>
                </div>

                <div class="inline-group" style="margin-top: 20px;">
                    <button type="submit" id="submitBtn">Thêm mới</button>
                    <button type="button" id="cancelBtn" class="btn-secondary" style="display: none;" onclick="resetForm()">Hủy sửa</button>
                </div>
            </form>
        </div>
    </div>


    <div class="card">
        <div class="card-header"> Danh sách người dùng</div>
        <div class="card-body" style="overflow-x: auto;">
            <table>
                <thead>
                    <tr><th>ID</th><th>Tên đăng nhập</th><th>Mật khẩu</th><th>Vai trò</th><th>Họ tên</th><th>Email/SĐT</th><th>Thao tác</th></tr>
                </thead>
                <tbody id="userTableBody">
                    @foreach($nguoiDungs as $user)
                    <tr data-id="{{ $user->ma_nguoi_dung }}">
                        <td>{{ $user->ma_nguoi_dung }}</td>
                        <td>{{ $user->ten_dang_nhap }}</td>
                        <td class="pwd-cell">
                            <span class="pwd-mask">******</span>
                            <span class="pwd-plain" style="display:none;">{{ $user->mat_khau }}</span>
                            <button class="btn btn-sm toggle-view-pwd" onclick="toggleViewPassword(this)"></button>
                        </td>
                        <td>{{ $user->vai_tro }}</td>
                        <td>
                            @if($user->isKhachHang() && $user->khachHang) {{ $user->khachHang->ten_khach_hang }}
                            @elseif($user->isNhanVien() && $user->nhanVien) {{ $user->nhanVien->ten_nhan_vien }}
                            @endif
                        </td>
                        <td>
                            @if($user->isKhachHang() && $user->khachHang) {{ $user->khachHang->email }} / {{ $user->khachHang->so_dien_thoai }}
                            @elseif($user->isNhanVien() && $user->nhanVien) {{ $user->nhanVien->email }} / {{ $user->nhanVien->so_dien_thoai }}
                            @endif
                        </td>
                        <td class="action-buttons">
                            <button class="btn btn-sm btn-secondary" onclick="editUser({{ $user->ma_nguoi_dung }})">Sửa</button>
                            <button class="btn btn-sm btn-danger" onclick="deleteUser({{ $user->ma_nguoi_dung }})">Xóa</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    const roleSelect = document.getElementById('vai_tro');
    const khachHangDiv = document.getElementById('khachHangFields');
    const nhanVienDiv = document.getElementById('nhanVienFields');
    const form = document.getElementById('userForm');
    const formTitle = document.getElementById('formTitle');
    const submitBtn = document.getElementById('submitBtn');
    const cancelBtn = document.getElementById('cancelBtn');

    function toggleRoleFields() {
        let role = roleSelect.value;
        khachHangDiv.style.display = 'none';
        nhanVienDiv.style.display = 'none';
        if (role === 'KHACH_HANG') khachHangDiv.style.display = 'block';
        else if (role === 'NHAN_VIEN' || role === 'SHIPPER') nhanVienDiv.style.display = 'block';
    }
    roleSelect.addEventListener('change', toggleRoleFields);
    toggleRoleFields(); 

    function togglePassword(fieldId) {
        let field = document.getElementById(fieldId);
        if (field.type === 'password') field.type = 'text';
        else field.type = 'password';
    }

    window.toggleViewPassword = function(btn) {
        let td = btn.closest('td');
        let maskSpan = td.querySelector('.pwd-mask');
        let plainSpan = td.querySelector('.pwd-plain');
        if (maskSpan.style.display !== 'none') {
            maskSpan.style.display = 'none';
            plainSpan.style.display = 'inline';
            btn.textContent = '.';
        } else {
            maskSpan.style.display = 'inline';
            plainSpan.style.display = 'none';
            btn.textContent = '.';
        }
    }

    window.resetForm = function() {
        form.reset();
        document.getElementById('formMethod').value = 'POST';
        form.action = "{{ route('nguoi-dung.store') }}";
        formTitle.innerHTML = ' Thêm người dùng';
        submitBtn.innerText = 'Thêm mới';
        cancelBtn.style.display = 'none';
        roleSelect.disabled = false;
        toggleRoleFields();
        document.getElementById('userId').value = '';
    }

    window.editUser = function(id) {
        fetch(`/nguoi-dung/${id}/edit-data`)
            .then(res => res.json())
            .then(data => {
                resetForm(); 
                document.getElementById('userId').value = id;
                document.getElementById('ten_dang_nhap').value = data.ten_dang_nhap;
                document.getElementById('mat_khau').value = '';
                roleSelect.value = data.vai_tro;
                roleSelect.disabled = true;
                toggleRoleFields();
                
                if (data.vai_tro === 'KHACH_HANG') {
                    document.getElementById('ten_khach_hang').value = data.ten_khach_hang || '';
                    document.getElementById('so_dien_thoai').value = data.so_dien_thoai || '';
                    document.getElementById('email').value = data.email || '';
                    document.getElementById('dia_chi').value = data.dia_chi || '';
                    document.getElementById('diem_tich_luy').value = data.diem_tich_luy || 0;
                } else if (data.vai_tro === 'NHAN_VIEN' || data.vai_tro === 'SHIPPER') {
                    document.getElementById('ten_nhan_vien').value = data.ten_nhan_vien || '';
                    document.getElementById('so_dien_thoai_nv').value = data.so_dien_thoai || '';
                    document.getElementById('email_nv').value = data.email || '';
                    document.getElementById('chuc_vu').value = data.chuc_vu || '';
                    document.getElementById('luong').value = data.luong || '';
                    document.getElementById('cong_viec').value = data.cong_viec || '';
                }
                form.action = `/nguoi-dung/${id}`;
                document.getElementById('formMethod').value = 'PUT';
                formTitle.innerHTML = ' Sửa người dùng';
                submitBtn.innerText = 'Cập nhật';
                cancelBtn.style.display = 'inline-block';
            })
            .catch(err => alert('Lỗi lấy dữ liệu: ' + err));
    }

    window.deleteUser = function(id) {
        if (!confirm('Bạn có chắc muốn xóa người dùng này? Dữ liệu liên quan sẽ bị xóa.')) return;
        fetch(`/nguoi-dung/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Xóa thất bại: ' + data.message);
            }
        });
    }

    form.addEventListener('submit', function(e) {
        let role = roleSelect.value;
        if (!role) {
            alert('Vui lòng chọn vai trò trước khi thêm/sửa');
            e.preventDefault();
            return false;
        }
        if (role === 'KHACH_HANG') {
            let ten = document.getElementById('ten_khach_hang').value.trim();
            let sdt = document.getElementById('so_dien_thoai').value.trim();
            let mail = document.getElementById('email').value.trim();
            if (!ten || !sdt || !mail) {
                alert('Vui lòng nhập đầy đủ họ tên, số điện thoại, email cho khách hàng');
                e.preventDefault();
                return false;
            }
        } else if (role === 'NHAN_VIEN' || role === 'SHIPPER') {
            let ten = document.getElementById('ten_nhan_vien').value.trim();
            let sdt = document.getElementById('so_dien_thoai_nv').value.trim();
            let mail = document.getElementById('email_nv').value.trim();
            if (!ten || !sdt || !mail) {
                alert('Vui lòng nhập đầy đủ họ tên, số điện thoại, email cho nhân viên');
                e.preventDefault();
                return false;
            }
        }
    });
</script>
</body>
</html>