<!-- Modal Thêm mới -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addNTBModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="addForm" action="{{ route('nhomthietbi.store') }}" method="POST" id="addForm">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="addModalLabel">Thêm nhóm thiết bị</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tennhom" class="control-label label-required">Tên nhóm thiết bị</label>
                        <input type="text" class="form-control" id="tennhom" name="tennhom" required>
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
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editNTBModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" id="editForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="addModalLabel">Cập nhật nhóm thiết bị</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_tennhom" class="control-label label-required">Tên nhóm thiết bị</label>
                        <input type="text" class="form-control" id="edit_tennhom" name="tennhom" required>
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
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteNTBModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form method="POST" id="deleteForm">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="deleteModalLabel">
                        <i class="fa fa-exclamation-triangle text-danger"></i> Xác nhận xóa
                    </h4>
                </div>
                <div class="modal-body">
                    <p><strong>Bạn có chắc chắn muốn xóa nhóm thiết bị này không?</strong></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal import -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('nhomthietbi.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="addModalLabel">Import nhóm thiết bị</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="file">Chọn file Excel</label>
                        <input type="file" class="form-control" id="file" name="file" required accept=".xlsx, .xls">
                        <small class="form-text text-muted">
                            File Excel phải có cột "tennhom" chứa tên nhóm thiết bị.
                            <a href="{{ route('nhomthietbi.downloadTemplate') }}" class="text-primary">
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