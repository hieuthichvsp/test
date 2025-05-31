@extends('layouts.app')
@section('title', 'Quản lý nhóm thiết bị')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="ibox float-e-margins">
            <div class="ibox-title my-ibox-title">
                <h2 class="h2-title">Danh sách nhóm thiết bị máy móc</h2>
                @canany('hasRole_A_M_L')
                <div class="btn-action">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#importModal">
                        <i class="fa fa-file-excel-o"></i> <span class="hidden-sm hidden-xs"> Import Excel</span>
                    </button>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#addModal">
                        <i class="fa fa-plus"></i><span class="hidden-sm hidden-xs"> Thêm nhóm thiết bị</span>
                    </button>
                </div>
                @endcan
            </div>
            <div class="ibox-content">
                <div class="table-responsive">
                    <table id="dataTables-ntb" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên nhóm thiết bị</th>
                                @canany('hasRole_A_M_L')
                                <th>Thao tác</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($nhomthietbis as $ntb)
                            <tr class="gradeX">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $ntb->tennhom }}</td>
                                @canany('hasRole_A_M_L')

                                <td class="btn-action">
                                    <button class="btn btn-warning btn-xs edit-btn"
                                        data-tooltip="Cập nhật"
                                        data-id="{{ $ntb->id }}"
                                        data-tennhom="{{$ntb->tennhom}}">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    @can('isAdmin')
                                    <button class="btn btn-danger btn-xs delete-btn"
                                        data-tooltip="Xóa"
                                        data-id="{{ $ntb->id }}">
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
@include('quanlythietbi.nhomthietbi.partials.modals')
@endsection
@section('js')
<script>
    $(document).ready(function() {
        // Xử lý nút sửa
        $('#dataTables-ntb').on('click', '.edit-btn', function() {
            var id = $(this).data('id');
            var tennhom = $(this).data('tennhom');
            $('#edit_tennhom').val(tennhom);
            $('#editForm').attr('action', '{{ route("nhomthietbi.update", ":id") }}'.replace(':id', id));
            $('#editModal').modal('show');
        });

        // Xử lý nút xóa
        $('#dataTables-ntb').on('click', '.delete-btn', function() {
            var id = $(this).data('id');
            $('#deleteForm').attr('action', '{{ route("nhomthietbi.destroy", ":id") }}'.replace(':id', id));
            $('#deleteModal').modal('show');

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
        $('.dataTables-ntb').DataTable({
            pageLength: 10,
            responsive: true,
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
                    text: 'Copy'
                },
                {
                    extend: 'excel',
                    text: 'Excel',
                    title: 'Danh sách nhóm thiết bị máy móc',
                    filename: 'danh_sach_nhom_thiet_bi_may_moc',
                    exportOptions: {
                        columns: ':visible:not(:last-child)'
                    }
                },
                {
                    extend: 'pdf',
                    text: 'PDF',
                    title: 'Danh sách nhóm thiết bị máy móc',
                    filename: 'danh_sach_nhom_thiet_bi_may_moc',
                    exportOptions: {
                        columns: ':visible:not(:last-child)'
                    }
                },
                {
                    extend: 'print',
                    text: 'Print',
                    title: 'Danh sách nhóm thiết bị máy móc',
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
        //Reset modal
        $('#addModal').on('hidden.bs.modal', function() {
            $(this).find('form')[0].reset();
        });
    });
</script>
@endsection