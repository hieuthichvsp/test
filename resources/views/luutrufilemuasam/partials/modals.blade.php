<!-- Modal Thêm -->
<div class="modal fade" id="addFileModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="addModalLabel">Thêm file mua sắm mới</h4>
            </div>
            <form action="{{ route('dsfilemuasam.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-sm-4 control-label">Tên file <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <input type="text" name="tenfile" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 control-label">Mô tả</label>
                        <div class="col-sm-8">
                            <textarea name="mota" rows="4" class="form-control" placeholder="Nhập mô tả về file..."></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 control-label">Danh mục mua sắm <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <select name="danhmucmuasam_id" class="form-control" required>
                                <option value="">-- Chọn danh mục mua sắm --</option>
                                @foreach($danhmucs as $danhmuc)
                                <option value="{{ $danhmuc->id }}">{{ $danhmuc->ten_danhmuc }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 control-label">Đề nghị <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <select name="denghi_id" class="form-control" required>
                                <option value="">-- Chọn đề nghị --</option>
                                @foreach($denghis as $denghi)
                                <option value="{{ $denghi->id }}">{{ $denghi->ten_de_nghi }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 control-label">File đính kèm <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <input type="file" name="file" class="form-control" required>
                            <small class="text-muted">Để trống nếu không muốn thay đổi file. Định dạng hỗ trợ: PDF</small>
                        </div>
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
<div class="modal fade" id="editFileModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="editModalLabel">Cập nhật thông tin file</h4>
            </div>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-sm-4 control-label">Tên file <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <input type="text" name="tenfile" id="edit_tenfile" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 control-label">Mô tả</label>
                        <div class="col-sm-8">
                            <textarea name="mota" id="edit_mota" rows="4" class="form-control" placeholder="Nhập mô tả về file..."></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 control-label">Danh mục mua sắm <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <select name="danhmucmuasam_id" id="edit_danhmucmuasam_id" class="form-control" required>
                                <option value="">-- Chọn danh mục mua sắm --</option>
                                @foreach($danhmucs as $danhmuc)
                                <option value="{{ $danhmuc->id }}">{{ $danhmuc->ten_danhmuc }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 control-label">Đề nghị <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <select name="denghi_id" id="edit_denghi_id" class="form-control" required>
                                <option value="">-- Chọn đề nghị --</option>
                                @foreach($denghis as $denghi)
                                <option value="{{ $denghi->id }}">{{ $denghi->ten_de_nghi }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 control-label">File đính kèm mới</label>
                        <div class="col-sm-8">
                            <input type="text" name="file" id="edit_file" class="form-control" readonly disabled>
                            <small class="text-muted">Để trống nếu không muốn thay đổi file. Định dạng hỗ trợ: PDF</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Xóa -->
<div class="modal fade" id="deleteFileModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="width: 400px; margin: auto;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="deleteModalLabel">
                    <i class="fa fa-exclamation-triangle text-danger"></i> Xác nhận xóa
                </h4>
            </div>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa file này không? Hành động này không thể hoàn tác.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tải Dữ Liệu -->
<div class="modal fade" id="downloadFileModal" tabindex="-1" role="dialog" aria-labelledby="downloadModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="addModalLabel">Tải file lưu trữ</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('dsfilemuasam.downloadByCategory') }}" method="GET" id="downloadForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="danhmuc_id">Danh mục mua sắm</label>
                        <select class="form-control" id="danhmuc_id" name="danhmuc">
                            <option value="">-- Chọn danh mục --</option>
                            @foreach($danhmucs as $danhmuc)
                            <option value="{{ $danhmuc->id }}">{{ $danhmuc->ten_danhmuc }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mt-3">
                        <label for="hocky_id">Học kỳ</label>
                        <select class="form-control" id="hocky_id" name="hocky">
                            <option value="">-- Chọn học kỳ --</option>
                            @foreach($hockys as $hocky)
                            <option value="{{ $hocky->id }}">{{ $hocky->hocky }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary" id="downloadBtn">
                        <i class="fa fa-download"></i> Tải xuống
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>