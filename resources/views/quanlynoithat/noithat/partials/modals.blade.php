<!-- Modal lọc đơn vị -->
<div class="modal fade" id="selectDonViModal" tabindex="-1" role="dialog" aria-labelledby="selectDonViModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-blue text-white">
        <h4 class="modal-title" id="selectDonViModalLabel">Chọn đơn vị</h4>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="selectDonViForm">
          <div class="form-group">
            <label for="toChuc">Tổ chức</label>
            <select class="form-control" id="toChuc" name="toChuc">
              <option value="" selected disabled>-- Chọn tổ chức --</option>
              @foreach($tochucs as $loai)
              <option value="{{ $loai->id }}">{{ $loai->tenloai }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label for="donvi">Đơn vị</label>
            <select class="form-control" id="donVi" name="donVi" disabled>
              <option value="" selected disabled>-- Chọn đơn vị --</option>
              @foreach($donvis as $dv)
              <option value="{{ $dv->id }}" data-maloai="{{ $dv->maloai }}">
                {{ $dv->tendonvi }} ({{ $dv->tenviettat }})
              </option>
              @endforeach
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
        <button type="button" class="btn btn-primary" id="btnXem">Xem</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Chỉnh sửa nội thất -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="editForm" method="POST">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="editModalLabel">Chỉnh sửa thiết bị nội thất</h4>
        </div>
        <div class="modal-body modal-edit">
          <!-- Form Inputs -->
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="tentb" class="control-label">Tên thiết bị</label>
              <input type="text" class="form-control" id="tentb" name="tentb" required>
            </div>
            <div class="form-group col-md-6">
              <label for="mota" class="control-label">Mô tả</label>
              <textarea class="form-control" id="mota" name="mota"></textarea>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="maso" class="control-label">Mã số</label>
              <input type="text" class="form-control" id="maso" name="maso" required>
            </div>
            <div class="form-group col-md-6">
              <label for="namsd" class="control-label">Năm sử dụng</label>
              <input type="number" class="form-control" id="namsd" name="namsd">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="nguongoc" class="control-label">Nguồn gốc</label>
              <input type="text" class="form-control" id="nguongoc" name="nguongoc">
            </div>
            <div class="form-group col-md-6">
              <label for="donvitinh" class="control-label">Đơn vị tính</label>
              <input type="text" class="form-control" id="donvitinh" name="donvitinh" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="soluong" class="control-label">Số lượng</label>
              <input type="number" class="form-control" id="soluong" name="soluong">
            </div>
            <div class="form-group col-md-6">
              <label for="gia" class="control-label">Giá</label>
              <input type="number" class="form-control" id="gia" name="gia">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="chatluong" class="control-label">Chất lượng</label>
              <input type="text" class="form-control" id="chatluong" name="chatluong">
            </div>
            <div class="form-group col-md-6">
              <label for="tinhtrang" class="control-label">Tình trạng</label>
              <input type="text" class="form-control" id="tinhtrang" name="tinhtrang" required>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
          <button type="submit" class="btn btn-primary">Lưu</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Modal Xóa -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title text-end" id="deleteModalLabel">
          <i class="fa fa-exclamation-triangle text-danger"></i> Xóa nội thất
          <button type=" button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
      </div>
      <form id="deleteForm" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-body">
          <p><strong>Bạn có chắc chắn muốn xóa nội thất này?</strong></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-danger">Xóa</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Thêm nội thất-->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="addModalLabel">Thêm nội thất</h4>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <form id="addForm" method="POST" action="{{ route('kiemke.store') }}">
        @csrf
        <div class="modal-body modal-add">
          <div class="row">
            <div class="col-md-6 form-group">
              <label for="tentb">Tên thiết bị</label>
              <input type="text" class="form-control" name="tentb" required>
            </div>
            <div class="col-md-6 form-group">
              <label for="chatluong">Chất lượng</label>
              <input type="text" class="form-control" name="chatluong">
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 form-group">
              <label for="maso">Mã số</label>
              <input type="text" class="form-control" name="maso" required>
            </div>
            <div class="col-md-6 form-group">
              <label for="namsd">Năm sử dụng</label>
              <input type="number" class="form-control" name="namsd">
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 form-group">
              <label for="nguongoc">Nguồn gốc</label>
              <input type="text" class="form-control" name="nguongoc">
            </div>
            <div class="col-md-6 form-group">
              <label for="donvitinh">Đơn vị tính</label>
              <select class="form-control" name="donvitinh" required>
                <option value="">-- Chọn đơn vị tính --</option>
                @foreach($donvitinhs as $dvt)
                <option value="{{ $dvt->id }}">{{ $dvt->tendonvi }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 form-group">
              <label for="soluong">Số lượng</label>
              <input type="number" class="form-control" name="soluong">
            </div>
            <div class="col-md-6 form-group">
              <label for="gia">Giá</label>
              <input type="number" class="form-control" name="gia">
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 form-group">
              <label for="mota">Mô tả</label>
              <textarea class="form-control" name="mota"></textarea>
            </div>
            <div class="col-md-6 form-group">
              <label for="ghichu">Ghi chú</label>
              <textarea class="form-control" name="ghichu"></textarea>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 form-group">
              <label for="tontai">Tồn tại</label>
              <input type="text" class="form-control" name="tontai">
            </div>
            <div class="col-md-6 form-group">
              <label for="tinhtrang">Tình trạng</label>
              <input type="text" class="form-control" name="tinhtrang" required>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 form-group">
              <label for="model">Model</label>
              <input type="text" class="form-control" name="model">
            </div>
            <div class="col-md-6 form-group">
              <label for="matinhtrang">Mã tình trạng</label>
              <select class="form-control" name="matinhtrang" required>
                <option value="">-- Chọn tình trạng --</option>
                @foreach($tinhtrangs as $tt)
                <option value="{{ $tt->id }}">{{ $tt->tinhtrang }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 form-group">
              <label for="maphongkho">Mã phòng kho</label>
              <select class="form-control" name="maphongkho" required>
                <option value="">-- Chọn phòng kho --</option>
                @foreach($phongkhos as $pk)
                <option value="{{ $pk->id }}">{{ $pk->tenphong }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6 form-group">
              <label for="maloai">Mã loại</label>
              <select class="form-control" name="maloai" required>
                <option value="">-- Chọn loại thiết bị --</option>
                @foreach($loais as $loai)
                <option value="{{ $loai->id }}">{{ $loai->tenloai }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
          <button type="submit" class="btn btn-success">Lưu mới</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal thay đổi tình trạng -->
<div class="modal fade" id="modalThayDoiTinhTrang" tabindex="-1" role="dialog" aria-labelledby="modalLabelTinhTrang">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="modalLabelTinhTrang">Thay đổi tình trạng thiết bị</h4>
      </div>
      <div class="modal-body">
        <form id="formThayDoiTinhTrang">
          <div class="form-group">
            <label for="matinhtrang">Chọn tình trạng</label>
            <select class="form-control" name="matinhtrang" required>
              <option value="">-- Chọn tình trạng --</option>
              @foreach($tinhtrangs as $tt)
              <option value="{{ $tt->id }}">{{ $tt->tinhtrang }}</option>
              @endforeach
            </select>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="btnThayDoi">Thay Đổi</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
      </div>

    </div>
  </div>
</div>

<!-- Modal Lọc theo phòng -->
<div class="modal fade" id="modalLocPhong" tabindex="-1" role="dialog" aria-labelledby="modalLabelLocPhong">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="modalLabelPhongKho">Lọc thiết bị đồ gỗ theo phòng</h4>
      </div>
      <div class="modal-body">
        <form id="formLocPhong">
          <div class="form-group">
            <label for="maphongkho">Chọn phòng kho</label>
            <select class="form-control" name="maphongkho" id="maphongkho" required>
              <option value="">-- Chọn phòng kho --</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="btnHienThiPhong">Hiển thị</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
      </div>

    </div>
  </div>
</div>

<!-- Modal Xóa Nhiều Thiết Bị -->
<div class="modal fade" id="deleteModalxoa" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabelxoa" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">
          <i class="fa fa-exclamation-triangle text-danger"></i> Xác nhận xóa
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p><strong>Bạn có chắc chắn muốn xóa các nội thất đã chọn không?</strong></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
        <button type="button" class="btn btn-danger" id="btnXacNhanXoaNhieu">Xác nhận xóa</button>
      </div>
    </div>
  </div>
</div>