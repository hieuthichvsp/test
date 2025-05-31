<style>
    .thiet-bi-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .thiet-bi-item .form-control {
        margin-right: 5px;
    }

    .remove-thietbi {
        padding: 5px 10px;
    }
</style>
<!-- Modal Thêm -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="addModalLabel">Cấp vật tư</h3>
            </div>
            <form action="{{ route('capphatvattu.store') }}" method="POST">
                @csrf
                <div class="modal-body modal-add">
                    <div class="form-group">
                        <label class="control-label">Học kỳ</label>
                        <select class="form-control" name="hocky" required>
                            @foreach ($hockys as $hk)
                            <option value="{{ $hk->id }}">
                                Học kỳ {{ $hk->hocky }} ({{ $hk->tunam }}-{{ $hk->dennam }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group ">
                        <label class="control-label">Mã học phần</label>
                        <select class="form-control" name="maHP" id="maHPSelect" required>
                            <option value="">-- Chọn mã học phần --</option>
                            @foreach ($hocphans as $hp)
                            <option value="{{ $hp->id }}" data-tenhp="{{ $hp->tenHP }}">
                                {{ $hp->maHP }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="control-label">Tên học phần</label>
                        <input type="text" name="tenHP" id="tenHPInput" class="form-control" value="" readonly>
                    </div>


                    <div class="form-group">
                        <label class="control-label">Mã lớp</label>
                        <input type="text" name="maLop" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label class="control-label">Sĩ số lớp</label>
                        <input type="number" name="siSo" class="form-control" min="1" value="1" required>
                    </div>

                    <div class="form-group">
                        <label class="control-label">Giáo viên</label>
                        <select class="form-control" name="id_gv" required>
                            @foreach ($giangviens as $gv)
                            <option value="{{ $gv->id }}">
                                {{ $gv->hoten }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="thiet-bi-section">
                        <h4>Thiết bị</h4>
                        <div class="form-group thiet-bi-item">
                            <div class="col-sm-6">
                                <select type="text" name="thiet_bi[]" class="form-control" placeholder="Tên thiết bị">
                            </div>
                            <div class="col-sm-4">
                                <input type="number" name="so_luong[]" class="form-control" placeholder="Số lượng"
                                    value="0">
                            </div>
                            <div class="col-sm-2">
                                <button type="button" class="btn btn-danger btn-xs remove-thietbi">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <!-- Các thiết bị khác sẽ được thêm bằng JS -->
                    </div>

                    <button type="button" class="btn btn-default" id="themThietBi">
                        <i class="fa fa-plus"></i> Thêm thiết bị
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" id="lamLai">Làm lại</button>
                    <button type="submit" class="btn btn-primary">Thêm</button>
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
                <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="deleteModalLabel">
                    <i class="fa fa-exclamation-triangle text-danger"></i> Xóa cấp phát vật tư
                </h4>
            </div>
            <div class="modal-body">
                <p><strong>Bạn có chắc chắn muốn xóa không?</strong></p>
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