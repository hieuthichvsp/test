<!-- Modal Thêm-->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="addModalLabel">Thêm vật tư</h4>
            </div>
            <form action="{{route('quanlyvattu.store')}}" method="POST">
                @csrf
                @method('POST')
                <div class="modal-body modal-edit">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="hocky">Học kỳ</label>
                            <select id="hocky_id-add" name='hocky_id-add' class="form-control">
                                @foreach($hockies as $hk)
                                <option value="{{ $hk->id }}" @if($hk==$hockyCurrent) selected @endif>
                                    Học kỳ {{ $hk->hocky }} ({{ $hk->tunam }} - {{ $hk->dennam }})
                                    @if($hk == $hockyCurrent) - Học kỳ hiện tại @endif
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="hocphan">Mã học phần:</label>
                            <select id="hocphan_id-add" name='hocphan_id-add' class="form-control">
                                @foreach ($hocphans as $hp)
                                <option value="{{ $hp->id }}">
                                    {{ $hp->maHP }} - {{ $hp->tenHP }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label>Tên vật tư</label>
                            <textarea rows="3" type="text" id="ten-add" name="ten-add" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Đơn giá</label>
                            <input type="number" id="dongia-add" name="dongia-add" class="form-control" min="0">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Khái toán</label>
                            <input type="number" id="khaitoan-add" name="khaitoan-add" class="form-control" min="0">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Định mức</label>
                            <input type="text" id="dinhmuc-add" name="dinhmuc-add" class="form-control">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Mã hiệu</label>
                            <input type="text" id="mahieu-add" name="mahieu-add" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Năm sản xuất</label>
                            <input type="number" id="namsx-add" name="namsx-add" class="form-control">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Nhãn hiệu</label>
                            <input type="text" id="nhanhieu-add" name="nhanhieu-add" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Xuất xứ</label>
                            <input type="text" id="xuatxu-add" name="xuatxu-add" class="form-control">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Hãng sản xuất</label>
                            <input type="text" id="hangsx-add" name="hangsx-add" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Số lượng</label>
                            <input type="number" id="soluong-add" name="soluong-add" class="form-control">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Đơn vị tính</label>
                            <select id="dvt_id-edit" name='dvt_id-add' class="form-control">
                                @foreach ($dvts as $dvt)
                                <option value="{{ $dvt->id }}">
                                    {{ $dvt->tendonvi }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label>Cấu hình</label>
                            <textarea rows="3" type="text" id="cauhinh-add" name="cauhinh-add" class="form-control"></textarea>
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

<!-- Modal Sửa-->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="editModalLabel">Cập nhật vật tư</h4>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body modal-edit">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="hocky">Học kỳ</label>
                            <select id="hocky_id-edit" name='hocky_id-edit' class="form-control">
                                @foreach($hockies as $hk)
                                <option value="{{ $hk->id }}" @if($hk==$hockyCurrent) selected @endif>
                                    Học kỳ {{ $hk->hocky }} ({{ $hk->tunam }} - {{ $hk->dennam }})
                                    @if($hk == $hockyCurrent) - Học kỳ hiện tại @endif
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="hocphan">Mã học phần:</label>
                            <select id="hocphan_id-edit" name='hocphan_id-edit' class="form-control">
                                @foreach ($hocphans as $hp)
                                <option value="{{ $hp->id }}">
                                    {{ $hp->maHP }} - {{ $hp->tenHP }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label>Tên vật tư</label>
                            <textarea rows="3" type="text" id="ten-edit" name="ten-edit" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Đơn giá</label>
                            <input type="number" id="dongia-edit" name="dongia-edit" class="form-control" min="0">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Khái toán</label>
                            <input type="number" id="khaitoan-edit" name="khaitoan-edit" class="form-control" min="0">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Định mức</label>
                            <input type="text" id="dinhmuc-edit" name="dinhmuc-edit" class="form-control">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Mã hiệu</label>
                            <input type="text" id="mahieu-edit" name="mahieu-edit" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Năm sản xuất</label>
                            <input type="number" id="namsx-edit" name="namsx-edit" class="form-control">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Nhãn hiệu</label>
                            <input type="text" id="nhanhieu-edit" name="nhanhieu-edit" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Xuất xứ</label>
                            <input type="text" id="xuatxu-edit" name="xuatxu-edit" class="form-control">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Hãng sản xuất</label>
                            <input type="text" id="hangsx-edit" name="hangsx-edit" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Số lượng</label>
                            <input type="number" id="soluong-edit" name="soluong-edit" class="form-control">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Đơn vị tính</label>
                            <select id="dvt_id-edit" name='dvt_id-edit' class="form-control">
                                @foreach ($dvts as $dvt)
                                <option value="{{ $dvt->id }}">
                                    {{ $dvt->tendonvi }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label>Cấu hình</label>
                            <textarea rows="3" type="text" id="cauhinh-edit" name="cauhinh-edit" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Xóa-->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="width: 400px; margin: auto;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="deleteModalLabel">
                    <i class="fa fa-exclamation-triangle text-danger"></i> Xóa vật tư
                </h4>
            </div>
            <div class="modal-body">
                <p><strong>Bạn có chắc chắn muốn xóa bản ghi này không?</strong></p>
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