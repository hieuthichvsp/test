<!-- Modal Thêm -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="addModalLabel">Thêm thông tin lưu trữ mới</h3>
            </div>
            <form action="{{ route('quanlydanhmuc.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label label-required">Tên thông tin</label>
                        <input type="text" name="tenthongtin" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Mô tả</label>
                        <textarea name="mota" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="control-label label-required">Danh mục</label>
                        <select name="id_danhmuc" class="form-control" required>
                            <option value="">Chọn danh mục</option>
                            @foreach($danhmucs as $danhmuc)
                            <option value="{{ $danhmuc->id }}">{{ $danhmuc->ten_danhmuc }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label label-required">Đề nghị</label>
                        <select name="id_denghi" class="form-control" required>
                            <option value="">Chọn đề nghị</option>
                            @foreach($denghis as $denghi)
                            <option value="{{ $denghi->id }}">{{ $denghi->ten_de_nghi }}</option>
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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="editModalLabel">Cập nhật thông tin lưu trữ</h3>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label label-required">Tên thông tin</label>
                        <input type="text" name="tenthongtin" id="edit_tenthongtin" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Mô tả</label>
                        <textarea name="mota" id="edit_mota" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="control-label label-required">Danh mục</label>
                        <select name="id_danhmuc" id="edit_id_danhmuc" class="form-control" required>
                            <option value="">Chọn danh mục</option>
                            @foreach($danhmucs as $danhmuc)
                            <option value="{{ $danhmuc->id }}">{{ $danhmuc->ten_danhmuc }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label label-required">Đề nghị</label>
                        <select name="id_denghi" id="edit_id_denghi" class="form-control" required>
                            <option value="">Chọn đề nghị</option>
                            @foreach($denghis as $denghi)
                            <option value="{{ $denghi->id }}">{{ $denghi->ten_de_nghi }}</option>
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
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="deleteModalLabel">
                    <i class="fa fa-exclamation-triangle text-danger"></i> Xác nhận xóa
                </h3>
            </div>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p style="font-size: 16px;">Bạn có chắc chắn muốn xóa thông tin này không?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Hủy
                    </button>
                    <button type="submit" class="btn btn-danger">
                        Xóa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal tạo quy trình -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="createModalLabel">
                    <i class="fa fa-plus-circle"></i> Tạo quy trình
                </h3>
            </div>
            <form id="createForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p style="font-size: 16px;"><i class="fa fa-exclamation-triangle text-warning"></i> Bạn có chắc chắn muốn tạo quy trình lưu trữ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <i class="fa fa-times"></i> Hủy
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fa fa-trash"></i> Tạo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Upload File -->
<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="addModalLabel">Upload File Lưu Trữ</h4>
            </div>
            <form id="uploadForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="file_id" id="file_id" value="">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="d-flex align-items-center mb-2">
                            <label class="font-weight-bold mr-3">Mua sắm:</label>
                            <span class="mb-0" id="muasam">Chưa có thông tin</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="d-flex align-items-center mb-2">
                            <label class="font-weight-bold mr-3">Danh mục:</label>
                            <span class="mb-0" id="danhmuc">Chưa chọn danh mục</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="d-flex align-items-center mb-2">
                            <label class="font-weight-bold mr-3">Đề nghị:</label>
                            <span class="mb-0" id="denghi">Chưa có đề nghị</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="d-flex align-items-center mb-2">
                            <label class="font-weight-bold mr-3">Loại file:</label>
                            <span class="mb-0" id="loaifile">Chưa có loại file</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="d-flex align-items-center">
                            <label class="font-weight-bold mr-3">Tên file:</label>
                            <input type="text" name="tenfile" class="form-control">
                            <small class="text-muted ml-2">Nhập tên nếu muốn thay đổi</small>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="d-flex align-items-center">
                            <label class="font-weight-bold mr-3">File đính kèm:</label>
                            <input type="file" name="file" class="form-control w-50" required>
                            <small class="text-muted ml-2">Hỗ trợ các định dạng: PDF</small>
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

<!-- Modal thông tin file -->
<div class="modal fade" id="showModal" tabindex="-1" role="dialog" aria-labelledby="showModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="addModalLabel">Thông tin file Lưu Trữ</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="d-flex align-items-center mb-2">
                        <label class="font-weight-bold mr-3">Mua sắm:</label>
                        <span class="mb-0" id="show_muasam">Chưa có thông tin</span>
                    </div>
                </div>

                <div class="form-group">
                    <div class="d-flex align-items-center mb-2">
                        <label class="font-weight-bold mr-3">Danh mục:</label>
                        <span class="mb-0" id="show_danhmuc">Chưa chọn danh mục</span>
                    </div>
                </div>

                <div class="form-group">
                    <div class="d-flex align-items-center mb-2">
                        <label class="font-weight-bold mr-3">Đề nghị:</label>
                        <span class="mb-0" id="show_denghi">Chưa có đề nghị</span>
                    </div>
                </div>

                <div class="form-group">
                    <div class="d-flex align-items-center mb-2">
                        <label class="font-weight-bold mr-3">Loại file:</label>
                        <span class="mb-0" id="show_loaifile">Chưa có loại file</span>
                    </div>
                </div>

                <div class="form-group">
                    <div class="d-flex align-items-center mb-2">
                        <label class="font-weight-bold mr-3">Tên file:</label>
                        <span class="mb-0" id="show_tenfile">Chưa có tên file</span>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                <a href="#" id="download_file" class="btn btn-primary" data-id="">Download file</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal download file -->
<div class="modal fade" id="downloadFilterModal" tabindex="-1" role="dialog" aria-labelledby="downloadFilterModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="addModalLabel">Tải file</h3>
            </div>
            <form id="downloadFilterForm" method="GET" action="{{ route('quanlydanhmuc.downloadByCategory') }}">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="danhmuc_id">Danh mục:</label>
                        <select name="danhmuc_id" id="danhmuc_id" class="form-control">
                            <option value="all">Tất cả danh mục</option>
                            @foreach($danhmucs as $danhmuc)
                            <option value="{{ $danhmuc->id }}">{{ $danhmuc->ten_danhmuc }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="hocky_id">Học kỳ:</label>
                        <select name="hocky_id" id="hocky_id" class="form-control">
                            <option value="all">Tất cả học kỳ</option>
                            @foreach($hockys as $hocky)
                            <option value="{{ $hocky->id }}">{{ $hocky->hocky }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Hủy</button>
                    <button type="submit" class="btn btn-primary">
                        Tải xuống
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>