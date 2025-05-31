<!-- Modal Thêm -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="addModalLabel">Thêm học kỳ mới</h3>
            </div>
            <form action="{{ route('hocky.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label label-required">Tên học kỳ</label>
                        <select name="tenhocky" class="form-control" required>
                            <option value="" disabled>-- Chọn học kỳ--</option>
                            <option value="1">Học kỳ 1</option>
                            <option value="2">Học kỳ 2</option>
                            <option value="phụ 1">Học kỳ phụ 1</option>
                            <option value="phụ 2">Học kỳ phụ 2</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label label-required">Từ năm</label>
                        <input type="number" name="tunam" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="control-label label-required">Đến năm</label>
                        <input type="number" name="dennam" class="form-control" required>
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
<div class="modal fade" id="editModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="editModalLabel">Cập nhật học kỳ</h3>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label label-required">Học kỳ</label>
                        <select name="hocky" id="edit_hocky" class="form-control" required>
                            <option value="" disabled>--- Chọn học kỳ ---</option>
                            <option value="1">Học kỳ 1</option>
                            <option value="2">Học kỳ 2</option>
                            <option value="phụ 1">Học kỳ phụ 1</option>
                            <option value="phụ 2">Học kỳ phụ 2</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label label-required">Từ năm</label>
                        <input type="number" name="tunam" id="edit_tunam" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="control-label label-required">Đến năm</label>
                        <input type="number" name="dennam" id="edit_dennam" class="form-control" required>
                        <!-- <span class="text-danger hidden" id="error-dennam2">Năm kết thúc phải lớn hơn năm bắt đầu!</span> -->
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
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="deleteModalLabel">
                    <i class="fa fa-exclamation-triangle text-danger"></i> Xóa học kỳ
                </h4>
            </div>
            <div class="modal-body">
                <p><strong>Bạn có chắc chắn muốn xóa học kỳ này không?</strong></p>
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