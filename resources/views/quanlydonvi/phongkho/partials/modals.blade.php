<!-- Modal Thêm -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-end" id="addModalLabel">Thêm phòng kho mới</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <form id="addForm" action="{{ route('phongkho.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label label-required">Mã phòng</label>
                        <input type="text" name="maphong-add" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="control-label label-required">Tên phòng</label>
                        <input type="text" name="tenphong-add" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="control-label">Khu</label>
                            <input type="text" name="khu-add" id="khu-add" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">Lầu</label>
                            <input type="number" name="lau-add" id="lau-add" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">Số phòng</label>
                            <input type="number" name="sophong-add" id="sophong-add" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label label-required">Giáo viên quản lý</label>
                        <select name="magvql-add" class="form-control" required>
                            <option value="" selected disabled>--- Chọn giáo viên quản lý ---</option>
                            @foreach($magvqls as $gv)
                            <option value="{{ $gv->id }}">{{ $gv->hoten }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label label-required">Đơn vị</label>
                        <select name="madonvi-add" class="form-control">
                            <option value="" selected disabled>--- Chọn đơn vị ---</option>
                            @foreach($donvis as $dv)
                            <option value="{{ $dv->id }}">{{ $dv->tendonvi }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Sửa -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-end" id="editModalLabel">Cập nhật phòng kho</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label label-required">Mã phòng</label>
                        <input type="text" name="maphong-edit" id="maphong-edit" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="control-label label-required">Tên phòng</label>
                        <input type="text" name="tenphong-edit" id="tenphong-edit" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="control-label">Khu</label>
                            <input type="text" name="khu-edit" id="khu-edit" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">Lầu</label>
                            <input type="number" name="lau-edit" id="lau-edit" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">Số phòng</label>
                            <input type="number" name="sophong-edit" id="sophong-edit" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label label-required">Giáo viên quản lý</label>
                        <select name="magvql-edit" id="magvql-edit" class="form-control" required>
                            @foreach($magvqls as $gv)
                            <option value="{{ $gv->id }}">{{ $gv->hoten }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label label-required">Loại đơn vị</label>
                        <select name="madonvi-edit" id="madonvi-edit" class="form-control">
                            @foreach($donvis as $dv)
                            <option value="{{ $dv->id }}">{{ $dv->tendonvi }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Xóa -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-end" id="deleteModalLabel">
                    <i class="fa fa-exclamation-triangle text-danger"></i> Xóa phòng
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Bạn có chắc chắn muốn xóa phòng này không?</strong></p>
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal upload excel -->
<div class="modal fade" id="importExcelModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-end" id="uploadModalLabel">Tải lên file Excel</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action='{{route("phongkho.import")}}' method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="modal-body">
                    <h4>Chọn đơn vị</h4>
                    <select name="donvi" id="donvi" class="form-control" style="margin-bottom: 20px;">
                        <option value="" disabled selected>-- Chọn đơn vị --</option>
                        @foreach ($donvis as $item)
                        <option value="{{ $item->id }}">{{ $item->tendonvi }}</option>
                        @endforeach
                    </select>
                    <h4>Chọn giáo viên quản lý</h4>
                    <select name="gvql" id="gvql" class="form-control" style="margin-bottom: 20px;">
                        <option value="" disabled selected>-- Chọn giáo viên quản lý --</option>
                    </select>
                    <h4>Chọn file tải lên</h4>
                    <input type="file" name="file" id="file" accept=".xlsx,.xls" class="form-control">
                    <strong>
                        <a href="{{ asset('download/excel/Danh_sach_phong_kho_mau.xlsx') }}" download>
                            <i class="fa fa-download"></i> Tải xuống file Excel mẫu
                        </a>
                    </strong>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>