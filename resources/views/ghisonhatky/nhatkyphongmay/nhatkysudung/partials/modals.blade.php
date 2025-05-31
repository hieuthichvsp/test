<!-- Modal thêm mới -->
<div class="modal fade" id="addModalNew" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-end" id="editModalLabel">Thêm nhật ký phòng máy</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('nhatkyphongmay.nhatkysudung.storeNew') }}" method="POST">
                @csrf
                <div class="modal-body modal-add">
                    <!-- Chọn học kỳ -->
                    <div class="form-group">
                        <label for="hockySearch">HỌC KỲ</label>
                        <select id="hockySearch" name="mahocky" class="form-control">
                            @foreach($hockys as $hk)
                            <option value="{{ $hk->id }}" @if($hk==$hockysCurrent) selected @endif>
                                Học kỳ {{ $hk->hocky }} ({{ $hk->tunam }} - {{ $hk->dennam }})
                                @if($hk == $hockysCurrent) - Học kỳ hiện tại @endif
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row mb-3">
                        <!-- Chọn phòng máy -->
                        <div class="col-md-6">
                            <div class="form-group autocomplete">
                                <label for="phongSearchCreate">PHÒNG MÁY</label>
                                <input id="phongSearchCreate" type="text" class="form-control" required placeholder="Nhập tên phòng (VD: A201)">
                                <input type="hidden" id="idPhongMay" name="maphong">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Chọn giáo viên -->
                            <div class="form-group">
                                <label for="magv" class="form-label fw-bold">GIÁO VIÊN</label>
                                <select name="matk" class="form-control">
                                    <option value="" selected disabled>
                                        --Chọn giáo viên--
                                    </option>
                                    @foreach($taikhoans as $tk)
                                    <option value="{{ $tk->id }}">
                                        {{$tk->hoten}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <!--Chọn ngày -->
                    <div class="form-group">
                        <label class="control-label">NGÀY</label>
                        <input type="date" id="ngay" name="ngay" class="form-control datePicker">
                    </div>
                    <div class="row mb-3">
                        <!--Chọn giờ vào -->
                        <div class="col-md-6" style="position:relative;">
                            <div class="form-group">
                                <label>Giờ vào</label>
                                <div class="input-group clockpicker-input">
                                    <input type="text" name="giovao" class="form-control" data-type="in" data-group="ca1" value="06:30" readonly>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Giờ ra</label>
                                <div class="input-group clockpicker-input">
                                    <input type="text" class="form-control" name="giora" data-type="out" data-group="ca1" value="12:30" readonly>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                </div>
                                <div class="error-message" style="color: red; display:none; margin-top:5px;">Giờ ra không được nhỏ hơn Giờ vào.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Mục đích sử dụng -->
                    <div class="form-group mb-3">
                        <label for="edit-mucdichsd" class="form-label fw-bold">MÔN HỌC/MỤC ĐÍCH SỬ DỤNG</label>
                        <textarea name="mucdichsd" class="form-control" rows="2"></textarea>
                    </div>

                    <!-- Tình trạng trước -->
                    <div class="form-group mb-3">
                        <label for="edit_tinhtrangtruoc" class="form-label fw-bold">TÌNH TRẠNG TRƯỚC KHI SỬ DỤNG</label>
                        <textarea name="tinhtrangtruoc" class="form-control" rows="3"></textarea>
                    </div>

                    <!-- Tình trạng sau -->
                    <div class="form-group mb-3">
                        <label for="edit_tinhtrangsau" class="form-label fw-bold">TÌNH TRẠNG SAU KHI SỬ DỤNG</label>
                        <textarea name="tinhtrangsau" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                    <button type="submit" id="saveButton" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Sửa -->
<div class="modal fade" id="editPMModal" tabindex="-1" role="dialog" aria-describedby="editModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="editModalLabel">Cập nhật</h3>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body modal-edit">
                    <!-- Phòng máy -->
                    <div class="form-group autocomplete">
                        <label class="form-label fw-bold">PHÒNG MÁY</label>
                        <input id="edit-phong" type="text" class="form-control" placeholder="Nhập tên phòng (VD: A201)" required>
                    </div>
                    <div class="row mb-3">
                        <!--Chọn giờ vào -->
                        <div class="col-md-6" style="position:relative;">
                            <div class="form-group">
                                <label>Giờ vào</label>
                                <div class="input-group clockpicker-input">
                                    <input type="text" name="giovao" class="form-control" data-type="in" data-group="ca1" value="06:30" readonly>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!--Chọn giờ ra -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Giờ ra</label>
                                <div class="input-group clockpicker-input">
                                    <input type="text" class="form-control" name="giora" data-type="out" data-group="ca1" value="12:30" readonly>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                </div>
                                <div class="error-message" style="color: red; display:none; margin-top:5px;">Giờ ra không được nhỏ hơn Giờ vào.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Mục đích sử dụng -->
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold">Mục đích sử dụng</label>
                        <textarea name="mucdichsd" class="form-control" rows="3"></textarea>
                    </div>

                    <!-- Tình trạng trước -->
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold">Tình trạng trước khi sử dụng</label>
                        <textarea name="tinhtrangtruoc" class="form-control" rows="3"></textarea>
                    </div>

                    <!-- Tình trạng sau -->
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold">Tình trạng sau khi sử dụng</label>
                        <textarea name="tinhtrangsau" class="form-control" rows="3"></textarea>
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

<!-- Modal Xóa -->
<div class="modal fade" id="deletePMModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="deleteModalLabel">
                    <i class="fa fa-exclamation-triangle text-danger"></i> Xóa nhật ký phòng máy
                </h4>
            </div>
            <div class="modal-body">
                <p><strong>Bạn có chắc chắn muốn xóa nhật ký phòng máy này không?</strong></p>
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

<!-- Modal Cập nhật tình trạng thiết bị -->
<div class="modal fade" id="modalUpdatePCStatus" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="editModalLabel">Cập nhật tình trạng thiết bị</h3>
            </div>
            @php
            $hasPermission=auth()->user()->can('hasRole_A_M_L')
            @endphp
            <form id="editStatusForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body modal-edit">
                    <div class="form-group row">
                        <label class="col-sm-4 control-label">Tên thiết bị</label>
                        <div class="col-sm-8">
                            <input type="text" name="tentb" id="edit-tentb" class="form-control" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 control-label">Mô tả</label>
                        <div class="col-sm-8">
                            <textarea rows="3" type="text" name="mota" id="edit-mota" class="form-control" disabled></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 control-label">Ghi chú</label>
                        <div class="col-sm-8">
                            <textarea rows="5" type="text" name="ghichu" id="edit-ghichu" class="form-control" @disabled(!$hasPermission)></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 control-label">Tình trạng</label>
                        <div class="col-sm-8">
                            <select name="tinhtrang" id="edit-tinhtrang" class="form-control" @disabled(!$hasPermission)>
                                <option value=1>Đang sử dụng</option>
                                <option value=5>Hư hỏng</option>
                            </select>
                            <span style="font-size: 12px;">Nếu thiết bị đã được sửa chữa thì cập nhật lại tình trạng mới.</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                    @can('hasRole_A_M_L')
                    <button type="submit" class="btn btn-primary">Lưu</button>
                    @endcan
                </div>
            </form>
        </div>
    </div>
</div>