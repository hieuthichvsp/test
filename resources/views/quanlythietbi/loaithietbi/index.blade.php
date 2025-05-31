@extends('layouts.app')
@section('title', 'Quản lý loại thiết bị')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12" style="padding-left: 0;">
            <div class="ibox float-e-margins">
                <div class="ibox-title my-ibox-title d-flex justify-content-between align-items-center">
                    <h2 class="h2-title">Danh sách loại thiết bị máy móc</h2>
                    @canany('hasRole_A_M_L')
                    <div class="btn-action">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#importModal">
                            <i class="fa fa-file-excel-o"></i><span class="hidden-sm hidden-xs"> Import Excel</span>
                        </button>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#addLTBModal">
                            <i class="fa fa-plus"></i> <span class="hidden-sm hidden-xs"> Thêm loại thiết bị</span>
                        </button>
                    </div>
                    @endcan
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table id="dataTables-ltb" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên loại thiết bị</th>
                                    @can('hasRole_A_M_L')
                                    <th>Thao tác</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($loaithietbis as $ltb)
                                <tr class="gradeX">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $ltb->tenloai }}</td>
                                    @canany('hasRole_A_M_L')
                                    <td class="btn-action">
                                        <button class="btn btn-warning btn-xs edit-btn"
                                            data-tooltip="Cập nhật"
                                            data-id="{{ $ltb->id }}"
                                            data-tenloai="{{ $ltb->tenloai }}"
                                            data-toggle="modal"
                                            data-target="#editLTBModal">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        @can('isAdmin')
                                        <button class="btn btn-danger btn-xs delete-btn"
                                            data-tooltip="Xóa"
                                            data-id="{{ $ltb->id }}"
                                            data-toggle="modal"
                                            data-target="#deleteLTBModal">
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
</div>
@include('quanlythietbi.loaithietbi.partials.modals')
@endsection

@section('js')
<script>
    $(document).ready(function() {
        // Xử lý nút sửa
        $('.edit-btn').click(function() {
            var id = $(this).data('id');
            $.ajax({
                url: '{{ route("loaithietbi.edit", ":id") }}'.replace(':id', id),
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('#edit_tenloai').val(response.loaithietbi.tenloai);
                    $('#editForm').attr('action', '{{ route("loaithietbi.update", ":id") }}'.replace(':id', id));
                },
                error: function(xhr) {
                    console.log('Đã xảy ra lỗi khi lấy thông tin loại thiết bị');
                }
            });
        });

        // Xử lý nút xóa
        $('.delete-btn').click(function() {
            var id = $(this).data('id');
            $('#deleteForm').attr('action', '{{ route("loaithietbi.destroy", ":id") }}'.replace(':id', id));
        });
        const hasPermission = "{{auth()->user()->can('hasRole_A_M_L')}}";
        const columnDefs = hasPermission ? [{
            orderable: false,
            targets: [0, 2],
            className: 'text-center'
        }] : [{
            orderable: false,
            targets: [0],
            className: 'text-center'
        }];
        // Khởi tạo DataTables
        $('#dataTables-ltb').DataTable({
            pageLength: 10,
            responsive: true,
            order: [],
            columnDefs: columnDefs,
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
                    extend: 'excel',
                    text: 'Excel',
                    title: 'Danh sách loại thiết bị máy móc',
                    filename: 'danh_sach_loai_thiet_bi_may_moc',
                    exportOptions: {
                        columns: ':visible:not(:last-child)'
                    }
                },
                {
                    extend: 'pdf',
                    text: 'PDF',
                    title: 'Danh sách loại thiết bị máy móc',
                    filename: 'danh_sach_loai_thiet_bi_may_moc',
                    exportOptions: {
                        columns: ':visible:not(:last-child)'
                    }
                },
                {
                    extend: 'print',
                    text: 'Print',
                    title: 'Danh sách loại thiết bị máy móc',
                    exportOptions: {
                        columns: ':visible:not(:last-child)'
                    },
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
    });
</script>
@endsection