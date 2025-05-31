<!-- Modal Thêm -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-end" id="addModalLabel">Thêm tài khoản</h4>
                <button type=" button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('taikhoan.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label label-required">Họ tên</label>
                        <input type="text" name="hoten" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="control-label label-required">Căn cước công dân</label>
                        <input type="text" name="cmnd" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="control-label label-required">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Chức vụ</label>
                        <input type="text" name="chucvu" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="control-label label-required">Loại tài khoản</label>
                        <select name="maloaitk" class="form-control" required>
                            <option value="" selected disabled>--- Chọn loại tài khoản ---</option>
                            @foreach($loaitaikhoans as $loai)
                            <option value="{{ $loai->id }}">{{ $loai->tenloai }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label label-required">Tổ chức</label>
                        <select name="madonvi" class="form-control" required>
                            <option value="" selected disabled>--- Chọn tổ chức ---</option>
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
                <h4 class="modal-title text-end" id="editModalLabel">Cập nhật thông tin tài khoản</h4>
                <button type=" button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label label-required">Họ tên</label>
                        <input type="text" name="hoten" id="edit_hoten" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="control-label label-required">Căn cước công dân</label>
                        <input type="text" name="cmnd" id="edit_cmnd" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="control-label label-required">Email</label>
                        <input type="email" name="email" id="edit_email" class="form-control" required>
                        <!-- @if ($errors->has('email'))
                            <span class="text-danger">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif -->
                    </div>
                    <div class="form-group">
                        <label class="control-label">Chức vụ</label>
                        <input type="text" name="chucvu" id="edit_chucvu" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="control-label label-required">Loại tài khoản</label>
                        <select name="maloaitk" id="edit_maloaitk" class="form-control" required>
                            <option value="" selected disabled>--- Chọn loại tài khoản ---</option>
                            @foreach($loaitaikhoans as $loai)
                            <option value="{{ $loai->id }}">{{ $loai->tenloai }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label label-required">Tổ chức</label>
                        <select name="madonvi" id="edit_madonvi" class="form-control">
                            <option value="" selected disabled>--- Chọn tổ chức ---</option>
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
                    <i class="fa fa-exclamation-triangle text-danger"></i> Xóa tài khoản
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Tất cả dữ liệu liên quan đến tài khoản này sẽ bị xóa.<br> Bạn có chắc chắn muốn xóa không?</strong></p>
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