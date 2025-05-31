@extends('layouts.app')
@section('title','Quản lý tài khoản')
@section('css')
<link href="css/plugins/dataTables/datatables.min.css" rel="stylesheet">
@endsection
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="ibox float-e-margins">
            <div class="ibox-title my-ibox-title">
                <h2 class="h2-title">Danh sách tài khoản</h2>
                <button class="btn btn-primary" data-toggle="modal" data-target="#addModal">
                    <i class="fa fa-plus" title="Thêm tài khoản"></i>
                    <span class="hidden-sm hidden-xs"> Thêm tài khoản</span>
                </button>
            </div>
            <div class="ibox-content">
                <div class="table-responsive">
                    <table id="dataTables-taikhoan" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Họ tên</th>
                                <th>CCCD</th>
                                <th>Email</th>
                                <th>Chức vụ</th>
                                <th>Loại tài khoản</th>
                                <th>Đơn vị</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($taikhoans as $taikhoan)
                            <tr class="gradeX">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $taikhoan->hoten }}</td>
                                <td>{{ $taikhoan->cmnd }}</td>
                                <td>{{ $taikhoan->email }}</td>
                                <td>{{ $taikhoan->chucvu }}</td>
                                <td>{{ $taikhoan->loaitaikhoan->tenloai ?? '' }}</td>
                                <td>{{ $taikhoan->donvi->tendonvi ?? '' }}</td>
                                <td class="btn-action">
                                    <button class="btn btn-warning btn-xs edit-btn"
                                        data-tooltip="Cập nhật"
                                        data-id="{{ $taikhoan->id }}">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                    <button class="btn btn-danger btn-xs delete-btn"
                                        data-tooltip="Xóa"
                                        data-id="{{ $taikhoan->id }}">
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
@include('quanlytaikhoan.taikhoan.partials.modals')
@endsection
@section('js')
<script>
    $(document).ready(function() {
        // Xử lý nút sửa tài khoản
        $('#dataTables-taikhoan').on('click', '.edit-btn', function() {
            var id = $(this).data('id');
            // Lấy thông tin đơn vị qua AJAX
            $.ajax({
                url: '{{ route("taikhoan.edit", ":id") }}'.replace(':id', id),
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Điền thông tin vào form
                    $('#edit_hoten').val(response.taikhoan.hoten);
                    $('#edit_cmnd').val(response.taikhoan.cmnd);
                    $('#edit_email').val(response.taikhoan.email);
                    $('#edit_chucvu').val(response.taikhoan.chucvu);
                    $('#edit_maloaitk').val(response.taikhoan.maloaitk);
                    $('#edit_madonvi').val(response.taikhoan.madonvi);
                    // Cập nhật action của form
                    $('#editForm').attr('action', '{{ route("taikhoan.update", ":id") }}'.replace(':id', id));
                    // Hiển thị modal
                    $('#editModal').modal('show');
                },
                error: function(xhr) {
                    showToast('error', 'Đã xảy ra lỗi khi lấy thông tin tài khoản.');
                }
            });
        });

        // Xử lý nút xóa
        $('#dataTables-taikhoan').on('click', '.delete-btn', function() {
            var id = $(this).data('id');
            var url = '{{ route("taikhoan.destroy", ":id") }}'.replace(':id', id);
            $('#deleteForm').attr('action', url);
            $('#deleteModal').modal('show');
        });
        // Khởi tạo DataTables
        $('#dataTables-taikhoan').DataTable({
            pageLength: 10,
            responsive: true,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "Tất cả"]
            ],
            order: [],
            columnDefs: [{
                orderable: false,
                targets: [0, 7],
                className: 'text-center'
            }],
            fixedHeader: true,
            drawCallback: function(settings) {
                var api = this.api();
                var startIndex = api.context[0]._iDisplayStart;

                api.column(0, {
                    page: 'current'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = startIndex + i + 1;
                });
            },
            dom: "<'row mb-3'" +
                "<'col-md-4'l>" +
                "<'col-md-4'B>" +
                "<'col-md-4'f>" +
                ">" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            buttons: [{
                    extend: 'copy',
                    text: 'Copy',
                    exportOptions: {
                        columns: ':visible:not(:last-child)'
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: 'Excel',
                    filename: 'danh_sach_tai_khoan',
                    exportOptions: {
                        columns: ':visible:not(:last-child)'
                    }
                },
                {
                    extend: 'pdf',
                    text: 'PDF',
                    title: 'Danh sách tài khoản',
                    filename: 'danh_sach_tai_khoan',
                    exportOptions: {
                        columns: ':visible:not(:last-child)'
                    }
                },
                {
                    extend: 'print',
                    text: 'Print',
                    title: 'Danh sách tài khoản',
                    filename: 'danh_sach_tai_khoan',
                    exportOptions: {
                        columns: ':visible:not(:last-child)'
                    },
                    customize: function(win) {
                        $(win.document.body).addClass('white-bg').css('font-size', '10px');
                        $(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
                    }
                }
            ]
        });
        //Reset form khi đóng modal
        $('#addModal').on('hidden.bs.modal', function() {
            $(this).find('form')[0].reset();
            $(this).find('select').val('').trigger('change');
        });
    });
</script>
@endsection