<!-- Modal Thêm mới -->
<div class="modal fade" id="addLTBModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                    <span>&times;</span>
                </button>
                <h4 class="modal-title" id="addModalLabel">Thêm loại thiết bị</h4>
            </div>
            <form action="{{ route('loaithietbi.store') }}" method="POST" id="addForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tenloai" class="control-label label-required">Tên loại thiết bị</label>
                        <input type="text" class="form-control" id="tenloai" name="tenloai" required>
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

<!-- Modal Sửa -->
<div class="modal fade" id="editLTBModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                    <span>&times;</span>
                </button>
                <h4 class="modal-title text-end" id="editModalLabel">Cập nhật loại thiết bị</h4>
            </div>
            <form method="POST" id="editForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_tenloai" class="control-label label-required">Tên loại thiết bị</label>
                        <input type="text" class="form-control" id="edit_tenloai" name="tenloai" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Xóa -->
<div class="modal fade" id="deleteLTBModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                    <span>&times;</span>
                </button>
                <h4 class="modal-title" id="deleteModalLabel">
                    <i class="fa fa-exclamation-triangle text-danger"></i> Xác nhận xóa
                </h4>
            </div>
            <form method="POST" id="deleteForm">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p><strong>Bạn có chắc chắn muốn xóa loại thiết bị này không?</strong></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Import Excel -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('loaithietbi.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                        <span>&times;</span>
                    </button>
                    <h4 class="modal-title" id="addModalLabel">Import loại thiết bị</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="file" class="control-label label-required">Chọn file Excel</label>
                        <input type="file" class="form-control" id="file" name="file" required accept=".xlsx, .xls">
                        <small class="form-text text-muted">
                            File Excel phải có cột "tenloai" chứa tên loại thiết bị.
                            <a href="{{ route('loaithietbi.downloadTemplate') }}" class="text-primary">
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