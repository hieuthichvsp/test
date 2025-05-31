<!-- Modal Thêm -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-end" id="addModalLabel">Thêm đơn vị mới</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <form id="addForm" action="{{ route('donvi.store') }}" method="POST">
                @csrf
                @method('POST')
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label label-required">Tên đơn vị</label>
                        <input type="text" id="tendonvi-add" name="tendonvi-add" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="control-label label-required">Tên viết tắt</label>
                        <input type="text" id="tenviettat-add" name="tenviettat-add" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="control-label label-required">Loại đơn vị</label>
                        <select name="maloai-add" id="maloai-add" class="form-control" required>
                            <option value="" disabled selected>--- Chọn loại đơn vị ---</option>
                            @foreach($loaidonvi as $loai)
                            <option value="{{ $loai->id }}">{{ $loai->tenloai }}</option>
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
                <h4 class="modal-title text-end" id="editModalLabel">Cập nhật đơn vị</h4>
                <button type=" button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group col-md-12">
                        <label class="control-label label-required">Tên đơn vị</label>
                        <input type="text" name="tendonvi-edit" id="tendonvi-edit" class="form-control" required>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="control-label label-required">Tên viết tắt</label>
                        <input type="text" name="tenviettat-edit" id="tenviettat-edit" class="form-control" required>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="control-label label-required">Tổ chức</label>
                        <select name="maloai-edit" id="maloai-edit" class="form-control" required>
                            @foreach($loaidonvi as $loai)
                            <option value="{{ $loai->id }}">{{ $loai->tenloai }}</option>
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
                    <i class="fa fa-exclamation-triangle text-danger"></i> Xóa đơn vị
                </h4>
                <button type=" button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Bạn có chắc chắn muốn xóa đơn vị này không?</strong></p>
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
                    <span>&times;</span>
                </button>
            </div>
            <form action='{{route("donvi.import")}}' method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="modal-body">
                    <h4>Chọn tổ chức</h4>
                    <select name="tenloai" id="tenloai" class="form-control" style="margin-bottom: 20px;">
                        <option value="" disabled selected>-- Chọn đơn vị --</option>
                        @foreach ($loaidonvi as $item)
                        <option value="{{ $item->id }}">{{ $item->tenloai }}</option>
                        @endforeach
                    </select>
                    <h4>Chọn file tải lên</h4>
                    <input type="file" name="file" id="file" accept=".xlsx,.xls" class="form-control">
                    <strong>
                        <a href="{{ asset('download/excel/danh_sach_don_vi_mau.xlsx') }}" download>
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