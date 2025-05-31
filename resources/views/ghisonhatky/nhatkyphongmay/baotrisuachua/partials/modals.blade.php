<!-- Modal thêm -->
<div class="modal fade" id="addBTSCModal" tabindex="-1" role="dialog" aria-describedby="addModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="addModalLabel">Thêm bảo trì</h3>
            </div>
            <form action="{{route('nhatkyphongmay.baotrisuachua.store')}}" method="POST">
                @csrf
                @method('POST')
                <div class="modal-body modal-edit">
                    <!-- Phòng máy -->
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="form-label fw-bold">Học kỳ</label>
                            <select name="hocky_id-add" class="form-control">
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
                            <select class="form-control select2" name='phong_id-add' id='phong_id-add'>
                                <option value="" disabled selected>--Xem tất cả--</option>
                                @foreach($phongmays as $phong)
                                <option value="{{ $phong->id }}" {{ request('phong-filter') == $phong->id ? 'selected' : '' }}>
                                    {{ $phong->maphong }} ({{ $phong->tenphong }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label fw-bold">Danh sách thiết bị</label>
                        <select name="dsThietBi[]" id='dsThietBi' class="form-control" style="height: 200px;" aria-rowspan="6" multiple required>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label fw-bold">Ngày bảo trì</label>
                        <input id="ngaybaotri-add" name="ngaybaotri-add" type="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="form-label fw-bold">Mô tả nguyên nhân hư hỏng</label>
                            <textarea rows="3" id="motahuhong-edit" name="motahuhong-add" type="text" class="form-control"></textarea>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label fw-bold">Nội dung bảo trì, sửa chữa</label>
                            <textarea rows="3" id="noidungbaotri-add" name="noidungbaotri-add" type="text" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 form-group">
                            <label class="form-label fw-bold">Người bảo trì</label>
                            <select name="nguoibaotri-add" class="form-control" id="nguoibaotri-add" required>
                                <option value="" disabled selected>--Chọn người bảo trì--</option>
                                @foreach($taikhoans as $tk)
                                <option value="{{ $tk->id }}">{{ $tk->hoten }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!--Chọn giờ ra -->
                        <div class="col-md-6 form-group">
                            <label class="form-label fw-bold">Người kiểm tra</label>
                            <select name="nguoikiemtra-add" class="form-control" id="nguoikiemtra-add" required>
                                <option value="" disabled selected>--Chọn người kiểm tra--</option>
                                @foreach($taikhoans as $tk)
                                <option value="{{ $tk->id }}">{{ $tk->hoten }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label fw-bold">Ghi chú</label>
                        <textarea rows="3" id="ghichu-add" name="ghichu-add" type="text" class="form-control"></textarea>
                    </div>
                    <input type="hidden" name="matk-add" value="{{Auth::user()->id}}">
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
<div class="modal fade" id="editBTSCModal" tabindex="-1" role="dialog" aria-hidden="true">
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
                    <input type="hidden" name='user_update-edit' value='{{Auth::user()->id}}'>
                    <!-- Phòng máy -->
                    <div class="form-group">
                        <label class="form-label fw-bold">Ngày bảo trì</label>
                        <input id="ngaybaotri-edit" name="ngaybaotri-edit" type="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label fw-bold">Mô tả nguyên nhân hư hỏng</label>
                        <textarea rows="3" id="motahuhong-edit" name="motahuhong-edit" type="text" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label fw-bold">Nội dung bảo trì, sửa chữa</label>
                        <textarea rows="3" id="noidungbaotri-edit" name="noidungbaotri-edit" type="text" class="form-control"></textarea>
                    </div>
                    <div class="row mb-3">
                        <!--Chọn giờ vào -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-bold">Người bảo trì</label>
                                <select name="nguoibaotri-edit" class="form-control" id="nguoibaotri-edit" required>
                                    <option value="" disabled selected>--Chọn người bảo trì--</option>
                                    @foreach($taikhoans as $tk)
                                    <option value="{{ $tk->id }}">{{ $tk->hoten }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!--Chọn giờ ra -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-group">
                                    <label class="form-label fw-bold">Người kiểm tra</label>
                                    <select name="nguoikiemtra-edit" class="form-control" id="nguoikiemtra-edit" required>
                                        <option value="" disabled selected>--Chọn người kiểm tra--</option>
                                        @foreach($taikhoans as $tk)
                                        <option value="{{ $tk->id }}">{{ $tk->hoten }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label fw-bold">Ghi chú</label>
                        <textarea rows="5" id="ghichu-edit" name="ghichu-edit" type="text" class="form-control"></textarea>
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
<div class="modal fade" id="deleteBTSCModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="deleteModalLabel">
                    <i class="fa fa-exclamation-triangle text-danger"></i> Xóa nhật ký bảo trì/sửa chữa
                </h4>
            </div>
            <div class="modal-body">
                <p><strong>Bạn có chắc chắn muốn xóa nhật ký bảo trì/sửa chữa này không ?</strong></p>
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