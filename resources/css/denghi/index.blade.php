@extends('layouts.app')
@section('title', 'Quản lý đề nghị')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12" style="padding-left: 0;">
            <div class="ibox float-e-margins">
                <div class="ibox-title my-ibox-title d-flex justify-content-between align-items-center">
                    <h2>Danh sách đề nghị</h2>
                    <div>
                        <button class="btn btn-success" data-toggle="modal" data-target="#addDeNghiModal">
                            <i class="fa fa-plus"></i> Thêm đề nghị
                        </button>
                    </div>    
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-denghi">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên đề nghị</th>
                                    <th>Mô tả</th>
                                    <th>Học kỳ</th>
                                    <th>Danh mục</th>
                                    <th>Người tạo</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($denghis as $denghi)
                                <tr class="gradeX">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $denghi->ten_de_nghi }}</td>
                                    <td>{{ $denghi->mo_ta }}</td>
                                    <td>{{ $denghi->hocky->hocky }}</td>
                                    <td>{{ $denghi->danhmuc_muasam->ten_danhmuc }}</td>
                                    <td>{{ $denghi->taikhoan->hoten }}</td>
                                    <td class="text-center" style="display: flex; justify-content: center; align-items: center; gap: 20px;">
                                        <button class="btn btn-warning btn-sm edit-btn"
                                            data-tooltip="Cập nhật"
                                            data-id="{{ $denghi->id }}"
                                            data-toggle="modal"
                                            data-target="#editDeNghiModal">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm delete-btn"
                                            data-tooltip="Xóa"
                                            data-id="{{ $denghi->id }}"
                                            data-toggle="modal"
                                            data-target="#deleteDeNghiModal">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Thêm mới -->
<div class="modal fade" id="addDeNghiModal" tabindex="-1" role="dialog" aria-labelledby="addDeNghiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('denghi.store') }}" method="POST" id="addForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="addModalLabel">Thêm đề nghị</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="ten_de_nghi">Tên đề nghị</label>
                        <input type="text" class="form-control" id="ten_de_nghi" name="ten_de_nghi" required>
                    </div>
                    <div class="form-group">
                        <label for="mo_ta">Mô tả</label>
                        <textarea class="form-control" id="mo_ta" name="mo_ta" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="id_hocky">Học kỳ</label>
                        <select class="form-control" id="id_hocky" name="id_hocky" required>
                            <option value="">-- Chọn học kỳ --</option>
                            @foreach($hockys as $hocky)
                                <option value="{{ $hocky->id }}">{{ $hocky->hocky }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="id_danhmuc">Danh mục</label>
                        <select class="form-control" id="id_danhmuc" name="id_danhmuc" required>
                            <option value="">-- Chọn danh mục --</option>
                            @foreach($danhmucs as $danhmuc)
                                <option value="{{ $danhmuc->id }}">{{ $danhmuc->ten_danhmuc }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="files">Tập tin đính kèm</label>
                        <input type="file" class="form-control" id="files" name="files[]" multiple>
                        <small class="text-muted">Có thể chọn nhiều file</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Xem chi tiết -->


<!-- Modal Sửa -->
<div class="modal fade" id="editDeNghiModal" tabindex="-1" role="dialog" aria-labelledby="editDeNghiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="POST" id="editForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="addModalLabel">Cập nhật/Upload đề nghị</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_ten_de_nghi">Tên đề nghị</label>
                        <input type="text" class="form-control" id="edit_ten_de_nghi" name="ten_de_nghi" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_mo_ta">Mô tả</label>
                        <textarea class="form-control" id="edit_mo_ta" name="mo_ta" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="edit_id_hocky">Học kỳ</label>
                        <select class="form-control" id="edit_id_hocky" name="id_hocky" required>
                            <option value="">-- Chọn học kỳ --</option>
                            @foreach($hockys as $hocky)
                                <option value="{{ $hocky->id }}">{{ $hocky->hocky }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_id_danhmuc">Danh mục</label>
                        <select class="form-control" id="edit_id_danhmuc" name="id_danhmuc" required>
                            <option value="">-- Chọn danh mục --</option>
                            @foreach($danhmucs as $danhmuc)
                                <option value="{{ $danhmuc->id }}">{{ $danhmuc->ten_danhmuc }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tập tin hiện tại</label>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="edit_files_table">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Tên file</th>
                                        <th>Loại file</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Dữ liệu sẽ được thêm bằng JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_files">Thêm tập tin mới</label>
                        <input type="file" class="form-control" id="edit_files" name="files[]" multiple>
                        <small class="text-muted">Có thể chọn nhiều file</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Xóa -->
<div class="modal fade" id="deleteDeNghiModal" tabindex="-1" role="dialog" aria-labelledby="deleteDeNghiModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" id="deleteForm">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="deleteModalLabel">
                        <i class="fa fa-exclamation-triangle text-danger"></i> Xác nhận xóa
                    </h4>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa đề nghị này không?</p>
                    <p>Tất cả các tập tin đính kèm cũng sẽ bị xóa.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
    $(document).ready(function() {
        // Khởi tạo DataTable
        $('.dataTables-denghi').DataTable({
            pageLength: 10,
            responsive: true,
            dom: "<'row mb-3'" +
                "<'col-md-4'l>" +
                "<'col-md-4 text-center'B>" +
                "<'col-md-4 d-flex justify-content-end'f>" +
                ">" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            buttons: [
                { extend: 'copy' },
                {
                    extend: 'csv',
                    text: 'CSV',
                    charset: 'utf-8',
                    bom: true
                },
                {
                    extend: 'excel',
                    text: 'Excel',
                    title: 'Danh sách đề nghị',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }
                },
                {
                    extend: 'pdf',
                    title: 'Danh sách đề nghị',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }
                },
                {
                    extend: 'print',
                    title: 'Danh sách đề nghị',
                    customize: function(win) {
                        $(win.document.body)
                            .addClass('white-bg')
                            .css('font-size', '10px');
                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    }
                }
            ]
        });

        // Xử lý sự kiện khi nhấn nút Xem chi tiết
        

        // Xử lý sự kiện khi nhấn nút Sửa
        $('.edit-btn').click(function() {
            var id = $(this).data('id');
            $('#editForm').attr('action', '/denghi/upd/' + id);
            
            $.ajax({
                url: '/denghi/edit/' + id,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#edit_ten_de_nghi').val(data.denghi.ten_de_nghi);
                    $('#edit_mo_ta').val(data.denghi.mo_ta);
                    $('#edit_id_hocky').val(data.denghi.id_hocky);
                    $('#edit_id_danhmuc').val(data.denghi.id_danhmuc);
                    
                    // Hiển thị danh sách file
                    var filesTable = $('#edit_files_table tbody');
                    filesTable.empty();
                    
                    if (data.denghi.luutru_taptins.length > 0) {
                        $.each(data.denghi.luutru_taptins, function(index, file) {
                            var row = '<tr>' +
                                '<td>' + (index + 1) + '</td>' +
                                '<td>' + file.ten_file + '</td>' +
                                '<td>' + file.loai_file + '</td>' +
                                '<td>' +
                                '<a href="/denghi/download/' + file.id + '" class="btn btn-primary btn-xs"><i class="fa fa-download"></i> Tải xuống</a> ' +
                                '<button type="button" class="btn btn-danger btn-xs delete-file" data-id="' + file.id + '"><i class="fa fa-trash"></i> Xóa</button>' +
                                '</td>' +
                                '</tr>';
                            filesTable.append(row);
                        });
                    } else {
                        filesTable.append('<tr><td colspan="4" class="text-center">Không có tập tin đính kèm</td></tr>');
                    }
                },
                error: function(xhr) {
                    console.log('Đã xảy ra lỗi khi lấy thông tin đề nghị');
                }
            });
        });

        // Xử lý sự kiện khi nhấn nút Xóa
        $('.delete-btn').click(function() {
            var id = $(this).data('id');
            $('#deleteForm').attr('action', '/denghi/del/' + id);
        });

        // Xử lý sự kiện xóa file trong modal chỉnh sửa
        $(document).on('click', '.delete-file', function() {
            var fileId = $(this).data('id');
            var row = $(this).closest('tr');
            
            if (confirm('Bạn có chắc chắn muốn xóa tập tin này không?')) {
                $.ajax({
                    url: '/denghi/deletefile/' + fileId,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        row.remove();
                        if ($('#edit_files_table tbody tr').length === 0) {
                            $('#edit_files_table tbody').append('<tr><td colspan="4" class="text-center">Không có tập tin đính kèm</td></tr>');
                        }
                        toastr.success('Xóa tập tin thành công');
                    },
                    error: function(xhr) {
                        toastr.error('Đã xảy ra lỗi khi xóa tập tin');
                    }
                });
            }
        });
    });
</script>

<style>
    .modal-lg {
        width: 80%;
        max-width: 1000px;
    }
    
    .my-ibox-title {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    [data-tooltip] {
        position: relative;
        cursor: pointer;
    }
    
    [data-tooltip]:before {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        padding: 5px 10px;
        background-color: #333;
        color: white;
        border-radius: 3px;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s, visibility 0.3s;
    }
    
    [data-tooltip]:hover:before {
        opacity: 1;
        visibility: visible;
    }
</style>
@endsection