<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/SanPham.css') }}">
    <title>Quản Lý Sản Phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #f5f6fa; }
        .card { border: none; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); }
        .card-header { border-radius: 12px 12px 0 0 !important; font-weight: 600; }
        .badge-dang-ban   { background: #d1f5d3; color: #1a7f37; }
        .badge-ngung-ban  { background: #fde8e8; color: #c0392b; }
        table img { border-radius: 8px; object-fit: cover; }
        .btn-sm { border-radius: 6px; }
        .preview-img { max-height: 120px; border-radius: 8px; display: none; margin-top: 8px; }
    </style>
</head>
<body>
<div class="container py-4">

    {{-- ===== THÔNG BÁO ===== --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif


    {{-- ===== Ô TRÊN: THÊM / SỬA SẢN PHẨM ===== --}}
    {{--
        Ô này có 2 chế độ:
        - Chế độ THÊM (mặc định): tiêu đề xanh lá, action POST /san-pham, fields trống
        - Chế độ SỬA: tiêu đề vàng, action PUT /san-pham/{id}, fields được điền sẵn bằng JS
        Chuyển đổi hoàn toàn bằng JS, không reload trang, không dùng modal
    --}}
    <div class="card mb-4" id="cardForm">

        {{-- Tiêu đề — JS sẽ đổi class bg và text --}}
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center" id="cardFormHeader">
            <span id="tieuDeForm">
                <i class="bi bi-plus-circle me-2"></i>Thêm Sản Phẩm Mới
            </span>
            {{-- Nút Hủy chỉ hiện khi đang ở chế độ Sửa --}}
            <button type="button" id="btnHuy" class="btn btn-light btn-sm"
                    onclick="chuyenVeCheDoDaThem()" style="display:none;">
                <i class="bi bi-x-lg me-1"></i>Hủy sửa
            </button>
        </div>

        <div class="card-body">
            {{-- Form dùng chung cho cả THÊM lẫn SỬA --}}
            {{-- JS sẽ đổi action và thêm/xóa input _method=PUT --}}
            <form id="formChung" action="{{ route('san-pham.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                {{-- Placeholder để JS chèn @method('PUT') khi cần --}}
                <div id="methodField"></div>

                <div class="row g-3">

                    {{-- Tên sản phẩm --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Tên sản phẩm <span class="text-danger">*</span></label>
                        <input type="text" name="ten_san_pham" id="inp_ten" class="form-control"
                               value="{{ old('ten_san_pham') }}" placeholder="Ví dụ: Hoa hồng đỏ" required>
                    </div>

                    {{-- Giá --}}
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Giá (VNĐ) <span class="text-danger">*</span></label>
                        <input type="number" name="gia" id="inp_gia" class="form-control"
                               value="{{ old('gia') }}" placeholder="300000" min="0" required>
                    </div>

                    {{-- Số lượng --}}
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Số lượng <span class="text-danger">*</span></label>
                        <input type="number" name="so_luong" id="inp_so_luong" class="form-control"
                               value="{{ old('so_luong') }}" placeholder="50" min="0" required>
                    </div>

                    {{-- Loại sản phẩm --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Loại sản phẩm <span class="text-danger">*</span></label>
                        <select name="loai_san_pham" id="inp_loai" class="form-select" required>
                            <option value="">-- Chọn loại sản phẩm --</option>
                            @foreach($danhSachLoai as $value => $label)
                                <option value="{{ $value }}" {{ old('loai_san_pham') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Mô tả --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Mô tả</label>
                        <textarea name="mo_ta" id="inp_mo_ta" class="form-control" rows="2"
                                  placeholder="Nhập mô tả sản phẩm...">{{ old('mo_ta') }}</textarea>
                    </div>

                    {{-- Hình ảnh --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" id="labelAnh">Hình ảnh</label>
                        <input type="file" name="hinh_anh" class="form-control"
                               accept="image/*" id="inp_anh"
                               onchange="xemTruocAnh(this, 'xemTruocAnh')">
                        <small class="text-muted" id="smallAnh">
                            JPG, PNG, GIF, WEBP — tối đa 2MB. Ảnh sẽ lưu vào <code>public/img</code>
                        </small>
                        <br>
                        {{-- Ảnh hiện tại (chỉ hiện khi đang sửa) --}}
                        <img id="anhHienTai" src="" alt="Ảnh hiện tại"
                             style="max-height:80px; border-radius:8px; margin-top:6px; display:none;">
                        {{-- Ảnh xem trước --}}
                        <img id="xemTruocAnh" class="preview-img" alt="Xem trước ảnh">
                    </div>

                </div>

                <div class="mt-3 d-flex gap-2">
                    {{-- Nút submit — JS đổi text và màu tùy chế độ --}}
                    <button type="submit" id="btnSubmit" class="btn btn-success">
                        <i class="bi bi-plus-lg me-1" id="iconSubmit"></i>
                        <span id="textSubmit">Thêm sản phẩm</span>
                    </button>
                </div>
            </form>
        </div>
    </div>


    {{-- ===== Ô DƯỚI: DANH SÁCH SẢN PHẨM ===== --}}
    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <span><i class="bi bi-list-ul me-2"></i>Danh Sách Sản Phẩm</span>
            <span class="badge bg-white text-primary">{{ $danhSachSanPham->count() }} sản phẩm</span>
        </div>
        <div class="card-body">

            {{-- Bộ lọc theo loại sản phẩm --}}
            <form method="GET" action="{{ route('san-pham.index') }}" class="mb-3 d-flex gap-2 flex-wrap align-items-center">
                <label class="fw-semibold me-1 mb-0">Lọc theo loại:</label>
                <select name="loai_san_pham" class="form-select w-auto" onchange="this.form.submit()">
                    <option value="">-- Tất cả --</option>
                    @foreach($danhSachLoai as $value => $label)
                        <option value="{{ $value }}" {{ request('loai_san_pham') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @if(request('loai_san_pham'))
                    <a href="{{ route('san-pham.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-x-circle me-1"></i>Xóa lọc
                    </a>
                @endif
            </form>

            {{-- Bảng danh sách --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Loại</th>
                            <th>Giá</th>
                            <th>Số lượng</th>
                            <th>Mô tả</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($danhSachSanPham as $sp)
                        <tr class="{{ $sp->trang_thai === 'NGUNG_BAN' ? 'table-secondary' : '' }}" id="row-{{ $sp->ma_san_pham }}">

                            {{-- Ảnh --}}
                            <td>
                                <img src="{{ asset($sp->hinh_anh ?? 'img/default.jpg') }}"
                                     alt="{{ $sp->ten_san_pham }}"
                                     width="60" height="60">
                            </td>

                            {{-- Tên --}}
                            <td>
                                <strong>{{ $sp->ten_san_pham }}</strong>
                                <br><small class="text-muted">#{{ $sp->ma_san_pham }}</small>
                            </td>

                            {{-- Loại --}}
                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ $danhSachLoai[$sp->loai_san_pham] ?? $sp->loai_san_pham }}
                                </span>
                            </td>

                            {{-- Giá --}}
                            <td>{{ number_format($sp->gia, 0, ',', '.') }} đ</td>

                            {{-- Số lượng --}}
                            <td>{{ $sp->so_luong }}</td>

                            {{-- Mô tả --}}
                            <td>
                                <span title="{{ $sp->mo_ta }}">
                                    {{ Str::limit($sp->mo_ta, 40) }}
                                </span>
                            </td>

                            {{-- Trạng thái --}}
                            <td>
                                @if($sp->trang_thai === 'DANG_BAN')
                                    <span class="badge badge-dang-ban px-2 py-1">
                                        <i class="bi bi-check-circle me-1"></i>Đang bán
                                    </span>
                                @else
                                    <span class="badge badge-ngung-ban px-2 py-1">
                                        <i class="bi bi-eye-slash me-1"></i>Ngừng bán
                                    </span>
                                @endif
                            </td>

                            {{-- Hành động --}}
                            <td>
                                <div class="d-flex gap-1">

                                    {{-- Nút SỬA: điền dữ liệu lên ô trên, cuộn lên đầu trang --}}
                                    <button class="btn btn-warning btn-sm"
                                            onclick="chuyenCheDeSua({{ $sp->ma_san_pham }})"
                                            title="Sửa sản phẩm">
                                        <i class="bi bi-pencil-fill"></i> Sửa
                                    </button>

                                    {{-- Nút ẨN / HIỆN --}}
                                    <form action="{{ route('san-pham.toggle', $sp->ma_san_pham) }}" method="POST"
                                          onsubmit="return confirm('{{ $sp->trang_thai === 'DANG_BAN' ? 'Ẩn sản phẩm này?' : 'Hiện lại sản phẩm này?' }}')">
                                        @csrf
                                        @method('PATCH')
                                        @if($sp->trang_thai === 'DANG_BAN')
                                            <button type="submit" class="btn btn-secondary btn-sm" title="Ẩn sản phẩm">
                                                <i class="bi bi-eye-slash-fill"></i> Ẩn
                                            </button>
                                        @else
                                            <button type="submit" class="btn btn-success btn-sm" title="Hiện lại">
                                                <i class="bi bi-eye-fill"></i> Hiện
                                            </button>
                                        @endif
                                    </form>

                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                Không có sản phẩm nào.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>


    function xemTruocAnh(input, idImg) {
        const imgEl = document.getElementById(idImg);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = (e) => {
                imgEl.src = e.target.result;
                imgEl.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function chuyenCheDeSua(id) {
        fetch(`/san-pham/${id}/edit-data`)
            .then(res => res.json())
            .then(sp => {

                document.getElementById('inp_ten').value      = sp.ten_san_pham;
                document.getElementById('inp_gia').value      = sp.gia;
                document.getElementById('inp_so_luong').value = sp.so_luong;
                document.getElementById('inp_loai').value     = sp.loai_san_pham;
                document.getElementById('inp_mo_ta').value    = sp.mo_ta ?? '';

                const anhEl = document.getElementById('anhHienTai');
                if (sp.hinh_anh) {
                    anhEl.src = '/' + sp.hinh_anh;
                    anhEl.style.display = 'inline-block';
                } else {
                    anhEl.style.display = 'none';
                }

                document.getElementById('xemTruocAnh').style.display = 'none';
                document.getElementById('inp_anh').value = '';

                document.getElementById('formChung').action = `/san-pham/${id}`;

                document.getElementById('methodField').innerHTML =
                    '<input type="hidden" name="_method" value="PUT">';

                const header = document.getElementById('cardFormHeader');
                header.className = 'card-header bg-warning text-dark d-flex justify-content-between align-items-center';

                document.getElementById('tieuDeForm').innerHTML =
                    '<i class="bi bi-pencil-fill me-2"></i>Sửa Sản Phẩm: <strong>' + sp.ten_san_pham + '</strong>';

                document.getElementById('btnHuy').style.display = 'inline-block';

                const btnSubmit = document.getElementById('btnSubmit');
                btnSubmit.className = 'btn btn-warning';
                document.getElementById('iconSubmit').className = 'bi bi-save me-1';
                document.getElementById('textSubmit').textContent = 'Lưu thay đổi';

                document.getElementById('labelAnh').textContent = 'Hình ảnh mới (để trống nếu không đổi)';

                document.getElementById('cardForm').scrollIntoView({ behavior: 'smooth' });
            })
            .catch(() => alert('Không thể tải dữ liệu sản phẩm!'));
    }


    function chuyenVeCheDoDaThem() {

        document.getElementById('formChung').reset();
        document.getElementById('formChung').action = '{{ route('san-pham.store') }}';

        document.getElementById('methodField').innerHTML = '';

        const header = document.getElementById('cardFormHeader');
        header.className = 'card-header bg-success text-white d-flex justify-content-between align-items-center';

        document.getElementById('tieuDeForm').innerHTML =
            '<i class="bi bi-plus-circle me-2"></i>Thêm Sản Phẩm Mới';

        document.getElementById('btnHuy').style.display = 'none';

        const btnSubmit = document.getElementById('btnSubmit');
        btnSubmit.className = 'btn btn-success';
        document.getElementById('iconSubmit').className = 'bi bi-plus-lg me-1';
        document.getElementById('textSubmit').textContent = 'Thêm sản phẩm';

        document.getElementById('labelAnh').textContent = 'Hình ảnh';

        document.getElementById('anhHienTai').style.display = 'none';
        document.getElementById('xemTruocAnh').style.display = 'none';
    }

</script>
</body>
</html>