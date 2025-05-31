<!-- Modal thêm  -->
<div class="modal fade" id="addMMTBModal" tabindex="-1" role="dialog" aria-labelledby="addMMTBModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('maymocthietbi.store') }}" method="POST" id="addForm">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                        <span>&times;</span>
                    </button>
                    <h4 class="modal-title" id="addModalLabel">Thêm máy móc thiết bị</h4>
                </div>
                <div class="modal-body modal-add">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tentb" class='label-required'>Tên thiết bị</label>
                                <input type="text" class="form-control" id="tentb" name="tentb" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="model" class='label-required'>Model</label>
                                <input type="text" class="form-control" id="model" name="model" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="maso" class='label-required'>Mã số</label>
                                <input type="text" class="form-control" id="maso" name="maso" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="somay">Số máy</label>
                                <input type="number" class="form-control" id="somay" name="somay">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="maloai" class='label-required'>Loại thiết bị</label>
                                <select class="form-control" id="maloai" name="maloai" required>
                                    <option value="">-- Chọn loại thiết bị --</option>
                                    @foreach($loaithietbis as $loaithietbi)
                                    <option value="{{ $loaithietbi->id }}">{{ $loaithietbi->tenloai }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="manhom" class='label-required'>Nhóm thiết bị</label>
                                <select class="form-control" id="manhom" name="manhom" required>
                                    <option value="">-- Chọn nhóm thiết bị --</option>
                                    @foreach($nhomthietbis as $nhomthietbi)
                                    <option value="{{ $nhomthietbi->id }}">{{ $nhomthietbi->tennhom }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="donvitinh">Đơn vị tính</label>
                                <input type="text" class="form-control" id="donvitinh" name="donvitinh">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gia">Giá</label>
                                <input type="text" class="form-control" id="gia" name="gia">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="namsd">Năm sử dụng</label>
                                <input type="number" class="form-control" id="namsd" name="namsd">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nguongoc">Nguồn gốc</label>
                                <input type="text" class="form-control" id="nguongoc" name="nguongoc">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="soluong">Số lượng</label>
                                <input type="number" class="form-control" id="soluong" name="soluong" min="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="matinhtrang" class='label-required'>Tình trạng</label>
                                <select class="form-control" id="matinhtrang" name="matinhtrang" required>
                                    <option value="">-- Chọn mã tình trạng --</option>
                                    @foreach($tinhtrangthietbis as $tinhtrangthietbi)
                                    <option value="{{ $tinhtrangthietbi->id }}">{{ $tinhtrangthietbi->tinhtrang }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="maphongkho" class='label-required'>Phòng/Kho</label>
                                <select class="form-control" id="maphongkho" name="maphongkho" required>
                                    <option value="">-- Chọn phòng/kho --</option>
                                    @foreach($phongkhos as $phongkho)
                                    <option value="{{ $phongkho->id }}">{{ $phongkho->tenphong }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="chatluong">Chất lượng</label>
                                <input type="text" class="form-control" id="chatluong" name="chatluong">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ghichu">Ghi chú</label>
                                <textarea class="form-control" id="ghichu" name="ghichu" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ghichutinhtrang">Ghi chú tình trạng</label>
                                <textarea class="form-control" id="ghichutinhtrang" name="ghichutinhtrang" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="mota">Mô tả</label>
                                <textarea class="form-control" id="mota" name="mota" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>
@can('hasRole_Admin_Manager')
<!-- Modal xóa -->
<div class="modal fade" id="deleteMMTBModal" tabindex="-1" role="dialog" aria-labelledby="deleteMMTBModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" id="deleteForm">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                        <span>&times;</span>
                    </button>
                    <h4 class="modal-title" id="deleteModalLabel">
                        <i class="fa fa-exclamation-triangle text-danger"></i> Xác nhận xóa
                    </h4>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa máy móc thiết bị này không?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan
@can('hasRole_A_M_L')
<!-- Modal sửa -->
<div class="modal fade" id="editMMTBModal" tabindex="-1" role="dialog" aria-labelledby="editMMTBModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="POST" id="editForm">
                @csrf
                @method('PUT')
                <div class="modal-header" style="position: sticky; top: 0; background: white; z-index: 1000;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                        <span>&times;</span>
                    </button>
                    <h4 class="modal-title" id="editModalLabel">Cập nhật máy móc thiết bị</h4>
                </div>
                <div class="modal-body" style="max-height: calc(100vh - 210px); overflow-y: auto;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_tentb" class="control-label label-required">Tên thiết bị </label>
                                <input type="text" class="form-control" id="edit_tentb" name="tentb" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_model" class="control-label label-required">Model </label>
                                <input type="text" class="form-control" id="edit_model" name="model" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_maso">Mã số</label>
                                <input type="text" class="form-control" id="edit_maso" name="maso">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_somay">Số máy</label>
                                <input type="number" class="form-control" id="edit_somay" name="somay">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_maloai" class="control-label label-required">Loại thiết bị</label>
                                <select class="form-control" id="edit_maloai" name="maloai" required>
                                    <option value="">--- Chọn loại thiết bị ---</option>
                                    @foreach($loaithietbis as $loaithietbi)
                                    <option value="{{ $loaithietbi->id }}">{{ $loaithietbi->tenloai }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_manhom" class="control-label label-required">Nhóm thiết bị</label>
                                <select class="form-control" id="edit_manhom" name="manhom" required>
                                    <option value="">--- Chọn nhóm thiết bị ---</option>
                                    @foreach($nhomthietbis as $nhomthietbi)
                                    <option value="{{ $nhomthietbi->id }}">{{ $nhomthietbi->tennhom }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_donvitinh">Đơn vị tính</label>
                                <input type="text" class="form-control" id="edit_donvitinh" name="donvitinh">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_soluong">Số lượng</label>
                                <input type="number" class="form-control" id="edit_soluong" name="soluong" min="0">
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_namsd">Năm sử dụng</label>
                                <input type="number" class="form-control" id="edit_namsd" name="namsd">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_nguongoc">Nguồn gốc</label>
                                <input type="text" class="form-control" id="edit_nguongoc" name="nguongoc">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_tinhtrang">Ghi chép tình trạng</label>
                                <input type="text" class="form-control" id="edit_tinhtrang" name="tinhtrang">
                            </div>
                        </div> -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_gia">Giá</label>
                                <input type="text" class="form-control" id="edit_gia" name="gia">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_matinhtrang" class="control-label label-required">Tình trạng</label>
                                <select class="form-control" id="edit_matinhtrang" name="matinhtrang">
                                    <option value="">--- Chọn mã tình trạng ---</option>
                                    @foreach($tinhtrangthietbis as $tinhtrangthietbi)
                                    <option value="{{ $tinhtrangthietbi->id }}">{{ $tinhtrangthietbi->tinhtrang }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_maphongkho">Phòng/Kho</label>
                                <select class="form-control" id="edit_maphongkho" name="maphongkho">
                                    <option value="">-- Chọn phòng/kho --</option>
                                    @foreach($phongkhos as $phongkho)
                                    <option value="{{ $phongkho->id }}">{{ $phongkho->tenphong }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_chatluong">Chất lượng</label>
                                <input type="text" class="form-control" id="edit_chatluong" name="chatluong">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="edit_mota">Mô tả</label>
                                <textarea class="form-control" id="edit_mota" name="mota" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_ghichu">Ghi chú</label>
                                <textarea class="form-control" id="edit_ghichu" name="ghichu" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_ghichutinhtrang">Ghi chú tình trạng</label>
                                <textarea class="form-control" id="edit_ghichutinhtrang" name="ghichutinhtrang" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="position: sticky; bottom: 0; background: white; z-index: 1000;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan

<!-- Modal lọc dữ liệu -->
<div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="filterForm" method="GET" action="{{ route('maymocthietbi.index') }}">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                        <span>&times;</span>
                    </button>
                    <h4 class="modal-title" id="editModalLabel">Lọc dữ liệu</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Đơn vị</label>
                                <select class="form-control" name="madonvi" id="filter_madonvi">
                                    <option value="">-- Tất cả đơn vị --</option>
                                    @foreach($donvis as $donvi)
                                    <option value="{{ $donvi->id }}" {{ request('madonvi') == $donvi->id ? 'selected' : '' }}>
                                        {{ $donvi->tendonvi }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Phòng/Kho</label>
                                <select class="form-control" name="maphongkho" id="filter_maphongkho">
                                    <option value="">-- Tất cả phòng/kho --</option>
                                    @foreach($phongkhos as $phongkho)
                                    <option value="{{ $phongkho->id }}" {{ request('maphongkho') == $phongkho->id ? 'selected' : '' }} data-donvi="{{ $phongkho->madonvi }}">
                                        {{ $phongkho->tenphong }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nhóm thiết bị</label>
                                <select class="form-control" name="manhom" id="filter_manhom">
                                    <option value="">-- Tất cả nhóm thiết bị --</option>
                                    @foreach($nhomthietbis as $nhomthietbi)
                                    <option value="{{ $nhomthietbi->id }}" {{ request('manhom') == $nhomthietbi->id ? 'selected' : '' }}>
                                        {{ $nhomthietbi->tennhom }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Loại thiết bị</label>
                                <select class="form-control" name="maloai" id="filter_maloai">
                                    <option value="">-- Tất cả loại thiết bị --</option>
                                    @foreach($loaithietbis as $loaithietbi)
                                    <option value="{{ $loaithietbi->id }}" {{ request('maloai') == $loaithietbi->id ? 'selected' : '' }}>
                                        {{ $loaithietbi->tenloai }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tình trạng thiết bị</label>
                                <select class="form-control" name="matinhtrang" id="filter_matinhtrang">
                                    <option value="">-- Tất cả tình trạng --</option>
                                    @foreach($tinhtrangthietbis as $tinhtrangthietbi)
                                    <option value="{{ $tinhtrangthietbi->id }}" {{ request('matinhtrang') == $tinhtrangthietbi->id ? 'selected' : '' }}>
                                        {{ $tinhtrangthietbi->tinhtrang }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Năm sử dụng</label>
                                <select class="form-control" name="namsd" id="filter_namsd">
                                    <option value="">-- Tất cả các năm --</option>
                                    @php
                                    $currentYear = date('Y');
                                    for($year = 2018; $year <= $currentYear; $year++) {
                                        $selected=request('namsd')==$year ? 'selected' : '' ;
                                        echo "<option value='$year' $selected>$year</option>" ;
                                        }
                                        @endphp
                                        </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" id="btnFilterOnly">Lọc dữ liệu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal xem chi tiết -->
<div class="modal fade" id="viewMMTBModal" tabindex="-1" role="dialog" aria-labelledby="viewMMTBModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="position: sticky; top: 0; background: white; z-index: 1;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                    <span>&times;</span>
                </button>
                <h4 class="modal-title" id="editModalLabel">Chi tiết máy móc thiết bị</h4>
            </div>
            <div class="modal-body" style="max-height: calc(100vh - 200px); overflow-y: auto;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tên thiết bị:</label>
                            <input type="text" id="view_tentb" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Model:</label>
                            <input type="text" id="view_model" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Mã số:</label>
                            <input type="text" id="view_maso" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Số máy:</label>
                            <input type="text" id="view_somay" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Loại thiết bị:</label>
                            <input type="text" id="view_loaithietbi" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nhóm thiết bị:</label>
                            <input type="text" id="view_nhomthietbi" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Đơn vị tính:</label>
                            <input type="text" id="view_donvitinh" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Số lượng:</label>
                            <input type="text" id="view_soluong" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Giá:</label>
                            <input type="text" id="view_gia" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Năm sử dụng:</label>
                            <input type="text" id="view_namsd" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nguồn gốc:</label>
                            <input type="text" id="view_nguongoc" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tình trạng:</label>
                            <input type="text" id="view_tinhtrang" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Chất lượng:</label>
                            <input type="text" id="view_chatluong" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Mô tả:</label>
                            <input type="text" id="view_mota" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Phòng/Khoa:</label>
                            <input type="text" id="view_phongkhoa" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Ghi chú:</label>
                            <input type="text" id="view_ghichu" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Ghi chú tình trạng:</label>
                            <input type="text" id="view_ghichutinhtrang" class="form-control" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="position: sticky; bottom: 0; background: white; z-index: 1;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Import dữ liệu -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('maymocthietbi.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                        <span>&times;</span>
                    </button>
                    <h4 class="modal-title" id="editModalLabel">Import máy móc thiết bị</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="file" class="control-label label-required">Chọn file Excel</label>
                        <input type="file" class="form-control" id="file" name="file" required accept=".xlsx, .xls">
                        <small class="form-text text-muted">
                            File Excel phải có các cột: tentb, model, maso, somay, loai_thietbi, nhom_thietbi, donvitinh, soluong, gia, chatluong, tinhtrang, ghichu, ghichutinhtrang, namsd, nguongoc.
                            <a href="{{ route('maymocthietbi.downloadTemplate') }}" class="text-primary" onclick="$('#loading-overlay').show(); setTimeout(function() { $('#loading-overlay').hide(); }, 100);">
                                <i class="fa fa-download"></i> Tải mẫu Excel
                            </a>
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Thống kê -->
<div class="modal fade" id="thongKeModal" tabindex="-1" role="dialog" aria-labelledby="thongKeModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('thongke.xuatfileexcel') }}" method="GET" id="thongKeForm">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                        <span>&times;</span>
                    </button>
                    <h4 class="modal-title" id="editModalLabel">Thống kê thiết bị theo phòng khoa</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="thongke_madonvi">Đơn vị</label>
                                <select class="form-control" id="thongke_madonvi" name="madonvi">
                                    <option value="">-- Tất cả đơn vị --</option>
                                    @foreach($donvis as $donvi)
                                    <option value="{{ $donvi->id }}">{{ $donvi->tendonvi }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="thongke_maphongkho">Phòng/Khoa</label>
                                <select class="form-control" id="thongke_maphongkho" name="maphongkho">
                                    <option value="">-- Tất cả phòng/khoa --</option>
                                    @foreach($phongkhos as $phongkho)
                                    <option value="{{ $phongkho->id }}" data-donvi="{{ $phongkho->madonvi }}">{{ $phongkho->tenphong }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="thongke_matinhtrang">Tình trạng thiết bị</label>
                                <select class="form-control" id="thongke_matinhtrang" name="matinhtrang">
                                    <option value="">-- Tất cả tình trạng --</option>
                                    @foreach($tinhtrangthietbis as $tinhtrangthietbi)
                                    <option value="{{ $tinhtrangthietbi->id }}">{{ $tinhtrangthietbi->tinhtrang }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary" onclick="$('#loading-overlay').show(); setTimeout(function() { $('#loading-overlay').hide(); }, 0);">
                        <i class="fa fa-file-excel-o"></i> Xuất thống kê Excel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>