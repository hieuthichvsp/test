<!-- Modal Thêm mới -->
<div class="modal fade" id="addDeNghiModal" tabindex="-1" role="dialog" aria-labelledby="addDeNghiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('denghi.store') }}" method="POST" id="addForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="addModalLabel">Thêm đề nghị</h4>
                </div>
                <div class="modal-body modal-add">
                    <div class="form-group">
                        <label for="ten_de_nghi" class="control-label label-required">Tên đề nghị</label>
                        <input type="text" class="form-control" id="ten_de_nghi" name="ten_de_nghi" required>
                    </div>
                    <div class="form-group">
                        <label for="mo_ta">Mô tả</label>
                        <textarea class="form-control" id="mo_ta" name="mo_ta" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="id_hocky" class="control-label label-required">Học kỳ</label>
                        <select class="form-control" id="id_hocky" name="id_hocky" required>
                            <option value="">-- Chọn học kỳ --</option>
                            @foreach($hockys as $hocky)
                            <option value="{{ $hocky->id }}">{{ $hocky->hocky }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="id_danhmuc" class="control-label label-required">Danh mục</label>
                        <select class="form-control" id="id_danhmuc" name="id_danhmuc" required>
                            <option value="">-- Chọn danh mục --</option>
                            @foreach($danhmucs as $danhmuc)
                            <option value="{{ $danhmuc->id }}">{{ $danhmuc->ten_danhmuc }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="files" class="control-label">Tập tin đính kèm</label>
                        <input type="file" class="form-control" id="files" name="files[]" multiple>
                        <small class="text-muted">Có thể chọn nhiều file</small>
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
<div class="modal fade" id="editDeNghiModal" tabindex="-1" role="dialog" aria-labelledby="editDeNghiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="POST" id="editForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="addModalLabel">Cập nhật/Upload đề nghị</h4>
                </div>
                <div class="modal-body modal-edit">
                    <div class="form-group">
                        <label for="edit_ten_de_nghi" class="control-label label-required">Tên đề nghị</label>
                        <input type="text" class="form-control" id="edit_ten_de_nghi" name="ten_de_nghi" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_mo_ta">Mô tả</label>
                        <textarea class="form-control" id="edit_mo_ta" name="mo_ta" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="edit_id_hocky" class="control-label label-required">Học kỳ</label>
                        <select class="form-control" id="edit_id_hocky" name="id_hocky" required>
                            <option value="">-- Chọn học kỳ --</option>
                            @foreach($hockys as $hocky)
                            <option value="{{ $hocky->id }}">{{ $hocky->hocky }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_id_danhmuc" class="control-label label-required">Danh mục</label>
                        <select class="form-control" id="edit_id_danhmuc" name="id_danhmuc" required>
                            <option value="">-- Chọn danh mục --</option>
                            @foreach($danhmucs as $danhmuc)
                            <option value="{{ $danhmuc->id }}">{{ $danhmuc->ten_danhmuc }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tập tin hiện tại</label>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="edit_files_table">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Tên file</th>
                                        <th>Loại file</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Dữ liệu sẽ được thêm bằng JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_files">Thêm tập tin mới</label>
                        <input type="file" class="form-control" id="edit_files" name="files[]" multiple>
                        <small class="text-muted">Có thể chọn nhiều file</small>
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
<div class="modal fade" id="deleteDeNghiModal" tabindex="-1" role="dialog" aria-labelledby="deleteDeNghiModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
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
                    <p>Bạn có chắc chắn muốn xóa đề nghị này không?</p>
                    <p>Tất cả các tập tin đính kèm cũng sẽ bị xóa.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </div>
            </form>
        </div>
    </div>
</div>