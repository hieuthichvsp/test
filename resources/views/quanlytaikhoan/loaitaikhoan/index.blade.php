@extends('layouts.app')
@section('title','Quản lý loại tài khoản')
@section('css')
<link href="css/plugins/dataTables/datatables.min.css" rel="stylesheet">
@endsection
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="ibox float-e-margins">
            <div class="ibox-title my-ibox-title">
                <h2 class="h2-title">Danh sách loại tài khoản</h2>
                <button class="btn btn-primary" data-toggle="modal" data-target="#addModal">
                    <i class="fa fa-plus" title="Thêm loại tài khoản"></i>
                    <span class="hidden-sm hidden-xs"> Thêm loại tài khoản</span>
                </button>
            </div>
            <div class="ibox-content">
                <div class="table-responsive">
                    <table id="dataTables-loaitaikhoan" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên loại tài khoản</th>
                                <th>Mô tả</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($loaitaikhoans as $loaitaikhoan)
                            <tr class="gradeX">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $loaitaikhoan->tenloai }}</td>
                                <td>{{ $loaitaikhoan->mota}}</td>
                                <td class="btn-action">
                                    <button type="button" class="btn btn-warning btn-xs edit-btn"
                                        data-tooltip="Cập nhật"
                                        data-id="{{ $loaitaikhoan->id }}">
                                        <i class="fa fa-pencil"></i>
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
@include('quanlytaikhoan.loaitaikhoan.partials.modals')
@endsection
@section('js')
<script>
    $(document).ready(function() {
        // Xử lý nút sửa tài khoản
        $('#dataTables-loaitaikhoan').on('click', '.edit-btn', function() {
            var id = $(this).data('id');
            // Lấy thông tin loại tài khoản từ table
            var row = $(this).closest('tr');
            var tenloai = row.find('td:nth-child(2)').text().trim();
            var mota = row.find('td:nth-child(3)').text().trim();
            // Cập nhật các trường trong modal
            $('#edit_tenloai').val(tenloai);
            $('#edit_mota').val(mota);
            // Cập nhật action của form
            $('#editForm').attr('action', '{{ route("loaitaikhoan.update", ":id") }}'.replace(':id', id));
            $('#editModal').modal('show');
        });

        // // Xử lý nút xóa
        // $('.delete-btn').click(function() {
        //     var id = $(this).data('id');
        //     var url = '{{ route("donvi.destroy", ":id") }}'.replace(':id', id);
        //     $('#deleteForm').attr('action', url);
        // });
        // Khởi tạo DataTables
        $('#dataTables-loaitaikhoan').DataTable({
            pageLength: 10,
            responsive: true,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "Tất cả"]
            ],
            order: [],
            columnDefs: [{
                orderable: false,
                targets: [0, 3],
                className: 'text-center'
            }],
            fixedHeader: true,
            // Tính toán lại STT dựa trên trang hiện tại
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
                "<'col-md-4'l>" + // show entries
                "<'col-md-4'B>" + // buttons
                "<'col-md-4'f>" + // search về phải
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
                    filename: 'danh_sach_loai_tai_khoan',
                    exportOptions: {
                        columns: ':visible:not(:last-child)'
                    }
                },
                {
                    extend: 'pdf',
                    text: 'PDF',
                    filename: 'danh_sach_loai_tai_khoan',
                    title: 'Danh sách loại tài khoản',
                    exportOptions: {
                        columns: ':visible:not(:last-child)'
                    }
                },
                {
                    extend: 'print',
                    text: 'Print',
                    title: 'Danh sách loại tài khoản',
                    filename: 'danh_sach_loai_tai_khoan',
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
        });
    });
</script>
@endsection