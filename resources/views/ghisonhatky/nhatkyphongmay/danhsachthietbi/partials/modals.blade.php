<!-- Modal Sửa -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="editModalLabel">Cập nhật</h3>
            </div>
            <form id="edit-form" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body modal-edit">
                    <!-- Phòng máy -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Mã số</label>
                                <input type="text" class="form-control" name="maso-edit" id="maso-edit" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tên thiết bị</label>
                                <input type="text" class="form-control" name="tentb-edit" id="tentb-edit" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Số máy</label>
                                <input type="text" class="form-control" name="somay-edit" id="somay-edit">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Chất lượng</label>
                                <input type="text" class="form-control" name="chatluong-edit" id="chatluong-edit">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Năm sử dụng</label>
                                <select class="form-control select2" name='namsd-edit' id="namsd-edit">
                                    @foreach($namsds as $nsd)
                                    <option value="{{ $nsd->namsd }}" {{ request('namsd-edit') == $nsd->namsd ? 'selected' : '' }}>
                                        Năm {{ $nsd->namsd }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nguồn gốc</label>
                                <select class="form-control select2" name='nguongoc-edit'>
                                    @foreach($nguongocs as $ng)
                                    <option value="{{ $ng->nguongoc }}" {{ request('nguongoc-edit') == $ng->nguongoc ? 'selected' : '' }}>
                                        {{ $ng->nguongoc}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Đơn vị tính</label>
                                <input type="text" class="form-control" name="donvitinh-edit" id="donvitinh-edit">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Số lượng</label>
                                <input type="number" class="form-control" name="soluong-edit" id="soluong-edit">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Giá</label>
                                <input type="number" class="form-control" name="gia-edit" id="gia-edit">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tình trạng</label>
                                <select class="form-control select2" name='tinhtrang-edit' id="tinhtrang-edit">
                                    @foreach($tinhtrangs as $tt)
                                    <option value="{{ $tt->id }}" {{ request('tinhtrang-edit') == $tt->id ? 'selected' : '' }}>
                                        {{ $tt->tinhtrang }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Mô tả</label>
                                <textarea rows="4" type="text" class="form-control" name="mota-edit" id="mota-edit"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Ghi chú</label>
                                <textarea class="form-control" name="ghichu-edit" id="ghichu-edit" rows="3"></textarea>
                            </div>
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
<div class="modal fade" id="filter-modal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="editModalLabel">Lọc dữ liệu</h3>
            </div>
            <form id="filter-form" method="GET">
                @csrf
                @method('GET')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Chọn phòng</label>
                                <select class="form-control select2" name='phong-filter'>
                                    <option value="">--Xem tất cả--</option>
                                    @foreach($phongmays as $phong)
                                    <option value="{{ $phong->id }}" {{ request('phong-filter') == $phong->id ? 'selected' : '' }}>
                                        {{ $phong->maphong }} ({{ $phong->tenphong }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Chọn loại thiết bị</label>
                                <select class="form-control select2" name='loaitb-filter'>
                                    <option value="">--Xem tất cả--</option>
                                    @foreach($loaitbs as $ltb)
                                    <option value="{{ $ltb->id }}" {{ request('loaitb-filter') == $ltb->id ? 'selected' : '' }}>
                                        {{ $ltb->tenloai }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Chọn tình trạng</label>
                                <select class="form-control select2" name='tinhtrang-filter'>
                                    <option value="">--Xem tất cả--</option>
                                    @foreach($tinhtrangs as $tt)
                                    <option value="{{ $tt->id }}" {{ request('tinhtrang-filter') == $tt->id ? 'selected' : '' }}>
                                        {{ $tt->tinhtrang }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Chọn giá</label>
                                <select class="form-control select2" name='gia-filter'>
                                    <option value="">--Xem tất cả--</option>
                                    @foreach($gias as $key =>$value)
                                    <option value="{{ $key }}" {{ request('gia-filter') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Chọn nguồn gốc</label>
                                <select class="form-control select2" name='nguongoc-filter'>
                                    <option value="">--Xem tất cả--</option>
                                    @foreach($nguongocs as $ng)
                                    <option value="{{ $ng->nguongoc }}" {{ request('nguongoc-filter') == $ng->nguongoc ? 'selected' : '' }}>
                                        {{ $ng->nguongoc}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Chọn năm sử dụng</label>
                                <select class="form-control select2" name='namsd-filter'>
                                    <option value="">--Xem tất cả--</option>
                                    @foreach($namsds as $nsd)
                                    <option value="{{ $nsd->namsd }}" {{ request('namsd-filter') == $nsd->namsd ? 'selected' : '' }}>
                                        Năm {{ $nsd->namsd }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Chọn chất lượng</label>
                                <select class="form-control select2" name='chatluong-filter'>
                                    <option value="">--Xem tất cả--</option>
                                    @foreach($chatluongs as $key=>$value)
                                    <option value="{{ $key }}" {{ request('chatluong-filter') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" style="margin-bottom: 0;" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary" id="btnFilter">Lọc dữ liệu</button>
                </div>
            </form>
        </div>
    </div>
</div>