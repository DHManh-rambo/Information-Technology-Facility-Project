<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/NhanVien.css') }}">
    <title>Quản lý nhân viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="container mt-4">
    <h2> Quản lý nhân viên</h2>

    {{-- Hiển thị thông báo --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ================================================= --}}
    {{-- 1. FORM SỬA (ẨN, HIỆN KHI NHẤN NÚT SỬA TRÊN DÒNG) --}}
    {{-- ================================================= --}}
    <div id="editFormContainer" class="card mb-4 d-none">
        <div class="card-header bg-warning text-dark">
            <strong><i class="fas fa-edit"></i> Chỉnh sửa thông tin nhân viên</strong>
            <button type="button" class="btn-close float-end" id="closeEditForm"></button>
        </div>
        <div class="card-body">
            <form id="editForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Mã nhân viên</label>
                        <input type="text" class="form-control" id="edit_ma_nhan_vien" readonly>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tên nhân viên</label>
                        <input type="text" name="ten_nhan_vien" id="edit_ten_nhan_vien" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" id="edit_email" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" name="so_dien_thoai" id="edit_so_dien_thoai" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Chức vụ</label>
                        <select name="chuc_vu" id="edit_chuc_vu" class="form-select" required>
                            <option value="CSKH">CSKH</option>
                            <option value="VAN_HANH">Vận hành</option>
                            <option value="THIET_KE">Thiết kế</option>
                            <option value="ONLINE">Online</option>
                            <option value="SHIPPER">Shipper</option>
                            <option value="KHAC">Khác</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Lương (VNĐ)</label>
                        <input type="number" name="luong" id="edit_luong" class="form-control" step="1000">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Công việc</label>
                        <input type="text" name="cong_viec" id="edit_cong_viec" class="form-control">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Lưu thay đổi</button>
                <button type="button" class="btn btn-secondary" id="cancelEdit">Hủy</button>
            </form>
        </div>
    </div>

    {{-- ================================================= --}}
    {{-- 2. TOOLBAR: LỌC + SẮP XẾP --}}
    {{-- ================================================= --}}
    <div class="row mb-3 align-items-end">
        <div class="col-md-3">
            <form method="GET" action="{{ route('nhan-vien.index') }}" id="filterForm">
                <label class="form-label">Lọc theo chức vụ</label>
                <select name="chuc_vu" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Tất cả --</option>
                    @foreach($chucVus as $value => $label)
                        <option value="{{ $value }}" {{ request('chuc_vu') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="col-md-3">
            <label class="form-label">Sắp xếp theo lương</label>
            <div>
                <a href="{{ request()->fullUrlWithQuery(['sort' => 'luong_desc']) }}" class="btn btn-sm btn-outline-primary {{ request('sort') == 'luong_desc' ? 'active' : '' }}">
                    <i class="fas fa-arrow-down"></i> Cao nhất
                </a>
                <a href="{{ request()->fullUrlWithQuery(['sort' => 'luong_asc']) }}" class="btn btn-sm btn-outline-primary {{ request('sort') == 'luong_asc' ? 'active' : '' }}">
                    <i class="fas fa-arrow-up"></i> Thấp nhất
                </a>
                <a href="{{ route('nhan-vien.index') }}" class="btn btn-sm btn-secondary">Reset</a>
            </div>
        </div>
    </div>

    {{-- ================================================= --}}
    {{-- 3. DANH SÁCH NHÂN VIÊN (BẢNG) --}}
    {{-- ================================================= --}}
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
            <tr>
                <th>Mã</th>
                <th>Tên nhân viên</th>
                <th>Email</th>
                <th>SĐT</th>
                <th>Chức vụ</th>
                <th>Lương (VNĐ)</th>
                <th>Công việc</th>
                <th width="100">Thao tác</th>
            </tr>
            </thead>
            <tbody>
            @forelse($nhanViens as $nv)
                <tr>
                    <td>{{ $nv->ma_nhan_vien }}</td>
                    <td>{{ $nv->ten_nhan_vien }}</td>
                    <td>{{ $nv->email }}</td>
                    <td>{{ $nv->so_dien_thoai }}</td>
                    <td>{{ $chucVus[$nv->chuc_vu] ?? $nv->chuc_vu }}</td>
                    <td class="text-end">{{ number_format($nv->luong, 0, ',', '.') }}</td>
                    <td>{{ $nv->cong_viec }}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-warning btn-edit"
                                data-id="{{ $nv->ma_nhan_vien }}"
                                data-ten="{{ $nv->ten_nhan_vien }}"
                                data-email="{{ $nv->email }}"
                                data-sdt="{{ $nv->so_dien_thoai }}"
                                data-chucvu="{{ $nv->chuc_vu }}"
                                data-congviec="{{ $nv->cong_viec }}"
                                data-luong="{{ $nv->luong }}">
                            <i class="fas fa-edit"></i> Sửa
                        </button>

                        <form action="{{ route('nhan-vien.destroy', $nv->ma_nhan_vien) }}" method="POST" class="d-inline delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-sm btn-danger btn-delete">
                                <i class="fas fa-trash-alt"></i> Xóa
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Không có nhân viên nào.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- Phân trang --}}
    <div class="d-flex justify-content-center">
        {{ $nhanViens->links() }}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const editFormContainer = document.getElementById('editFormContainer');
    const editForm = document.getElementById('editForm');
    const closeEditBtn = document.getElementById('closeEditForm');
    const cancelEditBtn = document.getElementById('cancelEdit');

    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            const ten = this.dataset.ten;
            const email = this.dataset.email;
            const sdt = this.dataset.sdt;
            const chucvu = this.dataset.chucvu;
            const congviec = this.dataset.congviec;
            const luong = this.dataset.luong;

            document.getElementById('edit_ma_nhan_vien').value = id;
            document.getElementById('edit_ten_nhan_vien').value = ten;
            document.getElementById('edit_email').value = email || '';
            document.getElementById('edit_so_dien_thoai').value = sdt || '';
            document.getElementById('edit_chuc_vu').value = chucvu;
            document.getElementById('edit_cong_viec').value = congviec || '';
            document.getElementById('edit_luong').value = luong || '';

            editForm.action = `/nhan-vien/${id}`;
            editFormContainer.classList.remove('d-none');
            editFormContainer.scrollIntoView({ behavior: 'smooth' });
        });
    });

    function closeEditForm() {
        editFormContainer.classList.add('d-none');
        editForm.reset(); 
    }
    closeEditBtn.addEventListener('click', closeEditForm);
    cancelEditBtn.addEventListener('click', closeEditForm);

    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function (e) {
            if (confirm('Bạn có chắc chắn muốn xóa nhân viên này không? Hành động không thể hoàn tác.')) {
                this.closest('form.delete-form').submit();
            }
        });
    });
</script>
</body>
</html>