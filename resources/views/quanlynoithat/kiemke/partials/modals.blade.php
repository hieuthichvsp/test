<!-- Modal lọc đơn vị -->
<div class="modal fade" id="selectDonViModal" tabindex="-1" role="dialog" aria-labelledby="selectDonViModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-blue text-white">
        <h5 class="modal-title" id="selectDonViModalLabel">Chọn đơn vị</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="selectDonViForm">
          <div class="form-group">
            <label for="toChuc">Tổ chức</label>
            <select class="form-control" id="toChuc" name="toChuc">
              <option value="" selected disabled>--- Chọn tổ chức ---</option>
              @foreach($tochucs as $loai)
              <option value="{{ $loai->id }}">{{ $loai->tenloai }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label for="donvi">Đơn vị</label>
            <select class="form-control" id="donVi" name="donVi" disabled>
              <option value="" selected disabled>--- Chọn đơn vị ---</option>
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

<!-- Modal Chỉnh sửa thiết bị -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="editModalLabel">Chỉnh sửa thiết bị nội thất</h4>
      </div>
      <form id="editForm" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body modal-edit">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="tentb" class="control-label">Tên thiết bị</label>
                <input type="text" class="form-control" id="tentb" name="tentb" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="maso" class="control-label">Mã số</label>
                <input type="text" class="form-control" id="maso" name="maso" required>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="namsd" class="control-label">Năm sử dụng</label>
                <input type="number" class="form-control" id="namsd" name="namsd">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="nguongoc" class="control-label">Nguồn gốc</label>
                <input type="text" class="form-control" id="nguongoc" name="nguongoc">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="donvitinh" class="control-label">Đơn vị tính</label>
                <input type="text" class="form-control" id="donvitinh" name="donvitinh" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="soluong" class="control-label">Số lượng</label>
                <input type="number" class="form-control" id="soluong" name="soluong">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="gia" class="control-label">Giá</label>
                <input type="number" class="form-control" id="gia" name="gia">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="chatluong" class="control-label">Chất lượng</label>
                <input type="text" class="form-control" id="chatluong" name="chatluong">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="tinhtrang" class="control-label">Tình trạng</label>
                <input type="text" class="form-control" id="tinhtrang" name="tinhtrang" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="namthongke" class="control-label">Năm thống kê</label>
                <input type="text" class="form-control" id="namthongke" name="namthongke" required>
              </div>
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
          <button type="submit" class="btn btn-primary">Lưu</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Xóa -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
  <div class="modal-dialog modal-md" role="document">
    <form id="deleteForm" method="POST">
      @csrf
      @method('DELETE')
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title text-end" id="deleteModalLabel">
            <i class="fa fa-exclamation-triangle text-danger"></i> Xóa đơn vị
          </h4>
          <button type=" button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Bạn có chắc chắn muốn xóa kiểm kê thiết bị này?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-danger">Xóa</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Modal Thêm Thiết Bị -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="addForm" method="POST" action="{{ route('kiemke.store') }}">
      @csrf
      <div class="modal-content">
        <div class="modal-header bg-success text-white">
          <h4 class="modal-title" id="addModalLabel">Thêm thiết bị nội thất</h4>
          <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body modal-add">
          <!-- Dòng 1: Tên thiết bị + Mô tả -->
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="tentb">Tên thiết bị</label>
                <input type="text" class="form-control" name="tentb" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="mota">Mô tả</label>
                <textarea class="form-control" name="mota"></textarea>
              </div>
            </div>
          </div>

          <!-- Dòng 2: Mã số + Năm sử dụng -->
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="maso">Mã số</label>
                <input type="text" class="form-control" name="maso" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="namsd">Năm sử dụng</label>
                <input type="number" class="form-control" name="namsd">
              </div>
            </div>
          </div>

          <!-- Dòng 3: Nguồn gốc + Đơn vị tính -->
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="nguongoc">Nguồn gốc</label>
                <input type="text" class="form-control" name="nguongoc">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="donvitinh">Đơn vị tính</label>
                <select class="form-control" name="donvitinh" required>
                  <option value="">-- Chọn đơn vị tính --</option>
                  @foreach($donvitinhs as $dvt)
                  <option value="{{ $dvt->id }}">{{ $dvt->tendonvi }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>

          <!-- Dòng 4: Số lượng + Giá -->
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="soluong">Số lượng</label>
                <input type="number" class="form-control" name="soluong">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="gia">Giá</label>
                <input type="number" class="form-control" name="gia">
              </div>
            </div>
          </div>

          <!-- Dòng 5: Chất lượng + Ghi chú -->
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="chatluong">Chất lượng</label>
                <input type="text" class="form-control" name="chatluong">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="ghichu">Ghi chú</label>
                <textarea class="form-control" name="ghichu"></textarea>
              </div>
            </div>
          </div>

          <!-- Dòng 6: Tồn tại + Tình trạng -->
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="tontai">Tồn tại</label>
                <input type="text" class="form-control" name="tontai">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="tinhtrang">Tình trạng</label>
                <input type="text" class="form-control" name="tinhtrang" required>
              </div>
            </div>
          </div>

          <!-- Dòng 7: Model + Mã tình trạng -->
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="model">Model</label>
                <input type="text" class="form-control" name="model">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="matinhtrang">Mã tình trạng</label>
                <select class="form-control" name="matinhtrang" required>
                  <option value="">-- Chọn tình trạng --</option>
                  @foreach($tinhtrangs as $tt)
                  <option value="{{ $tt->id }}">{{ $tt->tinhtrang }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>

          <!-- Dòng 8: Mã phòng kho + Mã loại -->
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="maphongkho">Mã phòng kho</label>
                <select class="form-control" name="maphongkho" required>
                  <option value="">-- Chọn phòng kho --</option>
                  @foreach($phongkhos as $pk)
                  <option value="{{ $pk->id }}">{{ $pk->tenphong }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
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

          <!-- Dòng 9: Tên thiết bị + Năm thống kê -->
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="idthietbi">Tên thiết bị</label>
                <select class="form-control" name="idthietbi" required>
                  <option value="">-- Chọn thiết bị --</option>
                  @foreach($thietbis as $tb)
                  <option value="{{ $tb->id }}">{{ $tb->tentb }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="namthongke">Năm thống kê</label>
                <input type="number" class="form-control" name="namthongke" required>
              </div>
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
          <button type="submit" class="btn btn-success">Lưu mới</button>
        </div>
      </div>
    </form>
  </div>
</div>