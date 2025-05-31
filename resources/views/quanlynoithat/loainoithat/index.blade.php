@extends('layouts.app')
@section('title', 'Quản lý loại nội thất')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="ibox float-e-margins">
            <div class="ibox-title my-ibox-title">
                <h2 class="h2-title">Danh sách loại nội thất</h2>
                @can('hasRole_A_M_L')
                <button class="btn btn-primary" data-toggle="modal" data-target="#addModal">
                    <i class="fa fa-plus" title="Thêm nội thất"></i>
                    <span class="hidden-sm hidden-xs"> Thêm loại nội thất</span>
                </button>
                @endcan
            </div>
            <div class="ibox-content">
                <div class="table-responsive">
                    <table id="dataTables-lnt" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên loại</th>
                                @canany('hasRole_A_M_L')
                                <th>Thao tác</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($loainoithats as $lnt)
                            <tr class="gradeX">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $lnt->tenloai}}</td>
                                @canany('hasRole_A_M_L')
                                <td class="btn-action">
                                    <button type="button" class="btn btn-warning btn-xs edit-btn"
                                        data-tooltip="Cập nhật"
                                        data-tenloai="{{ $lnt->tenloai }}"
                                        data-id="{{ $lnt->id }}">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    @can('hasRole_Admin_Manager')
                                    <button type="button" class="btn btn-danger btn-xs delete-btn"
                                        data-tooltip="Xóa"
                                        data-id="{{ $lnt->id }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    @endcan
                                </td>
                                @endcan
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@include('quanlynoithat.loainoithat.partials.modals')
@endsection
@section('js')
<script>
    $(document).ready(function() {
        // Xử lý nút sửa đơn vị
        $('#dataTables-lnt').on('click', '.edit-btn', function() {
            var id = $(this).data('id');
            var tenloai = $(this).data('tenloai');
            // Điền thông tin vào form
            $('#edit_tenloai').val(tenloai);
            $('#editForm').attr('action', '{{ route("loainoithat.update", ":id") }}'.replace(':id', id));
            // Hiển thị modal
            $('#editModal').modal('show');
            // Lấy thông tin đơn vị qua AJAX
            // $.ajax({
            //     url: '{{ route("loainoithat.edit", ":id") }}'.replace(':id', id),
            //     type: 'GET',
            //     dataType: 'json',
            //     success: function(response) {
            //         // Điền thông tin vào form
            //         $('#edit_tenloai').val(response.loainoithat.tenloai);
            //         // Cập nhật action của form
            //         $('#editForm').attr('action', '{{ route("loainoithat.update", ":id") }}'.replace(':id', id));
            //         // Hiển thị modal
            //         $('#editModal').modal('show');
            //     },
            //     error: function(xhr) {
            //         showToast('error', 'Đã xảy ra lỗi khi lấy thông tin loại nội thất');
            //     }
            // });
        });

        // Xử lý nút xóa
        $('#dataTables-lnt').on('click', '.delete-btn', function() {
            var id = $(this).data('id');
            var url = '{{ route("loainoithat.destroy", ":id") }}'.replace(':id', id);
            $('#deleteForm').attr('action', url);
            // Hiển thị modal
            $('#deleteModal').modal('show');
        });
        const hasPermission = "{{auth()->user()->can('hasRole_A_M_L')}}";
        const target = hasPermission ? [0, 2] : [0];
        // Khởi tạo DataTables
        $('#dataTables-lnt').DataTable({
            pageLength: 10,
            responsive: true,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            order: [], // Không sắp xếp mặc định
            columnDefs: [{
                orderable: false,
                className: 'text-center',
                targets: target
            }],
            drawCallback: function(settings) {
                var api = this.api();
                var startIndex = api.context[0]._iDisplayStart;

                api.column(0, {
                    page: 'current'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = startIndex + i + 1;
                });
            },
            fixedHeader: true,
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
                        columns: [0, 1]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: 'Excel',
                    filename: 'danh_sach_loai_noi_that',
                    title: 'Danh sách loại nội thất',
                    exportOptions: {
                        columns: [0, 1]
                    }
                },
                {
                    extend: 'pdf',
                    text: 'PDF',
                    filename: 'danh_sach_loai_noi_that',
                    title: 'Danh sách loại nội thất',
                    exportOptions: {
                        columns: [0, 1]
                    }
                },
                {
                    extend: 'print',
                    text: 'Print',
                    filename: 'danh_sach_loai_noi_that',
                    title: 'Danh sách loại nội thất',
                    exportOptions: {
                        columns: [0, 1]
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