<!-- Modal thêm -->
<div class="modal fade" id="addSQLKModal" tabindex="-1" role="dialog" aria-describedby="addModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="addModalLabel">Thêm nhật ký kho</h3>
            </div>
            <form action="{{route('soquanlykho.store')}}" method="POST">
                @csrf
                @method('POST')
                <div class="modal-body modal-add">
                    <!-- Phòng máy -->
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="form-label fw-bold">Học kỳ</label>
                            <select name="mahocky-add" class="form-control">
                                @foreach($hockys as $hk)
                                <option value="{{ $hk->id }}" @if($hk==$hockysCurrent) selected @endif>
                                    Học kỳ {{ $hk->hocky }} ({{ $hk->tunam }} - {{ $hk->dennam }})
                                    @if($hk == $hockysCurrent) - Học kỳ hiện tại @endif
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label fw-bold">Chọn phòng</label>
                            <select class="form-control" name='maphong-add' id='maphong-add'>
                                <option value="" disabled selected>--Xem tất cả--</option>
                                @foreach($phongmays as $phong)
                                <option value="{{ $phong->id }}">
                                    {{ $phong->maphong }} ({{ $phong->tenphong }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="form-label fw-bold">Thiết bị</label>
                            <select name="matb-add" id='dsThietBi' class="form-control" required>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label fw-bold">Giáo viên sử dụng</label>
                            <select name="matk-add" class="form-control" id="matk-add" required>
                                <option value="" disabled selected>--Chọn giảng viên--</option>
                                @foreach($taikhoans as $tk)
                                <option value="{{ $tk->id }}">{{ $tk->hoten }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="form-label fw-bold">Ngày mượn</label>
                            <input id="ngaymuon-add" name="ngaymuon-add" type="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label fw-bold">Ngày trả</label>
                            <input id="ngaytra-add" name="ngaytra-add" type="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label fw-bold">Mục đích sử dụng</label>
                        <textarea rows="3" id="mucdicsd-add" name="mucdichsd-add" type="text" class="form-control"></textarea>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 form-group">
                            <label class="form-label fw-bold">Tình trạng trước khi sử dụng</label>
                            <textarea rows="3" id="tinhtrangtruoc-add" name="tinhtrangtruoc-add" type="text" class="form-control"></textarea>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label fw-bold">Tình trạng sau khi sử dụng</label>
                            <textarea rows="3" id="tinhtrangsau-add" name="tinhtrangsau-add" type="text" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal Sửa -->
<div class="modal fade" id="editSQLKModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                    <span>&times;</span>
                </button>
                <h3 class="modal-title" id="editModalLabel">Cập nhật</h3>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body modal-edit">
                    <div class="form-group">
                        <label class="form-label fw-bold">Tên thiết bị</label>
                        <input name="tentb-edit" id='tentb-edit' class="form-control" disabled required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="form-label fw-bold">Ngày mượn</label>
                            <input id="ngaymuon-edit" name="ngaymuon-edit" type="date" class="form-control" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label fw-bold">Ngày trả</label>
                            <input id="ngaytra-edit" name="ngaytra-edit" type="date" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label fw-bold">Mục đích sử dụng</label>
                        <textarea rows="3" id="mucdichsd-edit" name="mucdichsd-edit" type="text" class="form-control"></textarea>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 form-group">
                            <label class="form-label fw-bold">Tình trạng trước khi sử dụng</label>
                            <textarea rows="3" id="tinhtrangtruoc-edit" name="tinhtrangtruoc-edit" type="text" class="form-control"></textarea>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label fw-bold">Tình trạng sau khi sử dụng</label>
                            <textarea rows="3" id="tinhtrangsau-edit" name="tinhtrangsau-edit" type="text" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Xóa -->
<div class="modal fade" id="deleteSQLKModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="deleteModalLabel">
                    <i class="fa fa-exclamation-triangle text-danger"></i> Xóa nhật ký kho
                </h4>
            </div>
            <div class="modal-body">
                <p><strong>Bạn có chắc chắn muốn xóa nhật ký kho này không ?</strong></p>
            </div>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </div>
            </form>
        </div>
    </div>
</div>