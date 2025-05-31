<!-- Modal Thêm -->
<div class="modal fade" id="addModalNew" tabindex="-1" role="dialog" aria-labelledby="addLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="addModalLabel">Thêm nhật ký mới</h3>
            </div>
            <form action="{{ route('nhatkyloaithietbi.storeNew') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <!-- Dòng 1: Năm sử dụng - Phòng máy - Giáo viên -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="hockySearch">NĂM SỬ DỤNG</label>
                                <select id="hockySearch" name="idhocky" class="form-control">
                                    @foreach($hockys->reverse() as $hk)
                                    <option value="{{ $hk->id }}">{{ $hk->namsd }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group autocomplete">
                                <label for="phongSearchCreate">PHÒNG MÁY</label>
                                <input required id="phongSearchCreate" type="text" class="form-control"
                                    name="phongSearch" placeholder="Nhập tên phòng (VD: A201)">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="magv">GIÁO VIÊN</label>
                                <select name="magv" id="magv" class="form-control">
                                    <option value="" selected disabled>--Chọn giáo viên--</option>
                                    @foreach($taikhoans as $tk)
                                    <option value="{{ $tk->id }}">{{ $tk->hoten }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Dòng 2: Ngày - Giờ vào - Giờ ra -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ngay">NGÀY</label>
                                <input type="text" id="ngay" name="ngay" class="form-control datePicker">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit_giovao">GIỜ VÀO</label>
                                <input type="text" id="edit_giovao" name="giovao" class="form-control timePicker"
                                    required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit_giora">GIỜ RA</label>
                                <input type="text" id="edit_giora" name="giora" class="form-control timePicker"
                                    required>
                            </div>
                        </div>
                    </div>

                    <!-- Dòng 3: Thiết bị sử dụng (chọn 1 thiết bị) -->
                    <div class="form-group">
                        <label for="thietbisd">THIẾT BỊ SỬ DỤNG</label>
                        <select name="thietbisd" id="thietbisd" class="form-control">
                            <option value="" selected disabled>--Chọn thiết bị--</option>
                            @foreach($thietbis as $tb)
                            <option value="{{ $tb->id }}">{{ $tb->tentb }} - {{ $tb->mota }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Môn học / Mục đích -->
                    <div class="form-group">
                        <label for="edit_mucdichsd">MÔN HỌC / MỤC ĐÍCH SỬ DỤNG</label>
                        <textarea name="mucdichsd" id="edit_mucdichsd" class="form-control" rows="2"></textarea>
                    </div>

                    <!-- Tình trạng trước -->
                    <div class="form-group">
                        <label for="edit_tinhtrangtruoc">TÌNH TRẠNG TRƯỚC KHI SỬ DỤNG</label>
                        <textarea name="tinhtrangtruoc" id="edit_tinhtrangtruoc" class="form-control"
                            rows="3"></textarea>
                    </div>

                    <!-- Tình trạng sau -->
                    <div class="form-group">
                        <label for="edit_tinhtrangsau">TÌNH TRẠNG SAU KHI SỬ DỤNG</label>
                        <textarea name="tinhtrangsau" id="edit_tinhtrangsau" class="form-control" rows="3"></textarea>
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
<!-- Modal Sửa -->
<div class="modal fade" id="editPMModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="editModalLabel">Cập nhật nhật ký</h3>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <!-- Phòng máy -->
                    <div class="form-group autocomplete">
                        <label class="form-label fw-bold">PHÒNG MÁY</label>
                        <input required id="edit-phong" type="text" class="form-control" name="phongSearch"
                            placeholder="Nhập tên phòng (VD: A201)">
                    </div>

                    <!-- Thiết bị -->
                    <div class="form-group">
                        <label class="form-label fw-bold">Thiết bị</label>
                        <select class="form-control" name="thietbisd" id="edit-thietbi" required>
                            <!-- Các thiết bị sẽ được nạp từ cơ sở dữ liệu -->
                            @foreach($thietbis as $device)
                            <option value="{{ $device->id }}" id="device-{{ $device->id }}">
                                {{ $device->tentb }} - {{ $device->mota }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Giờ vào và Giờ ra -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-bold">Giờ vào</label>
                                <input type="text" id="edit-giovao" name="giovao" class="form-control timePicker"
                                    required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-bold">Giờ ra</label>
                                <input type="text" id="edit-giora" name="giora" class="form-control timePicker"
                                    required>
                            </div>
                        </div>
                    </div>

                    <!-- Mục đích sử dụng -->
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold">Mục đích sử dụng</label>
                        <textarea name="mucdichsd" class="form-control" rows="3" id="edit-mucdichsd"></textarea>
                    </div>

                    <!-- Tình trạng trước khi sử dụng -->
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold">Tình trạng trước khi sử dụng</label>
                        <textarea name="tinhtrangtruoc" class="form-control" rows="3"
                            id="edit-tinhtrangtruoc"></textarea>
                    </div>

                    <!-- Tình trạng sau khi sử dụng -->
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold">Tình trạng sau khi sử dụng</label>
                        <textarea name="tinhtrangsau" class="form-control" rows="3" id="edit-tinhtrangsau"></textarea>
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
@section('js')
<script>
    $(document).ready(function() {
        $('#editPMModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Nút sửa được nhấn
            var id = button.data('id');
            var phong = button.data('phong');
            var giovao = button.data('giovao');
            var giora = button.data('giora');
            var mucdichsd = button.data('mucdichsd');
            var tinhtrangtruoc = button.data('tinhtrangtruoc');
            var tinhtrangsau = button.data('tinhtrangsau');
            var phongname = button.data('phongname');
            var thietbi = button.data('thietbi'); // Lấy thông tin thiết bị
            console.log(phong)

            // Điền dữ liệu vào các trường input của modal
            $('#editForm').attr('action', '/nhatkythietbi/' + id); // Cập nhật action cho form
            $('#edit-phong').val(phongname); // Điền tên phòng vào input
            $('#edit-giovao').val(giovao); // Điền giờ vào vào input
            $('#edit-giora').val(giora); // Điền giờ ra vào input
            $('#edit-mucdichsd').val(mucdichsd); // Điền mục đích sử dụng
            $('#edit-tinhtrangtruoc').val(tinhtrangtruoc); // Điền tình trạng trước
            $('#edit-tinhtrangsau').val(tinhtrangsau); // Điền tình trạng sau

            // Chọn thiết bị từ danh sách
            $('#edit-thietbi').val(thietbi); // Cập nhật thiết bị trong dropdown
        });
    });
</script>
@endsection

<!-- Modal Xóa -->
<div class="modal fade" id="deletePMModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="width: 400px; margin: auto;">
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