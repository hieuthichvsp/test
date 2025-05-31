@extends('layouts.app')
@section('title', 'Quản lý đơn vị')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="ibox float-e-margins">
            <div class="ibox-title my-ibox-title">
                <h2 class="h2-title">Danh sách đơn vị</h2>
                @can('isAdmin')
                <div class="btn-action">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#importExcelModal">
                        <i class="fa fa-file-excel-o"></i>
                        <span class="hidden-sm hidden-xs"> Import Excel</span>
                    </button>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#addModal">
                        <i class="fa fa-plus"></i>
                        <span class="hidden-sm hidden-xs"> Thêm đơn vị</span>
                    </button>
                </div>
                @endcan
            </div>
            <div class="ibox-content">
                @include('quanlydonvi.donvi.partials.table')
            </div>
        </div>
    </div>
</div>
@include('quanlydonvi.donvi.partials.modals')
@endsection
@section('js')
<script>
    $(document).ready(function() {
        // Xử lý nút sửa đơn vị
        $('#dataTables-donvi').on('click', '.edit-btn', function() {
            // Cập nhật action của form
            var id = $(this).data('id');
            var tendonvi = $(this).data('tendonvi');
            var tenviettat = $(this).data('tenviettat');
            var maloai = $(this).data('maloai');
            $('#tendonvi-edit').val(tendonvi);
            $('#tenviettat-edit').val(tenviettat);
            $('#maloai-edit').val(maloai);
            var updUrl = '{{ route("donvi.update", ":id") }}'.replace(':id', id);
            $('#editForm').attr('action', updUrl);
            $('#editModal').modal('show');
        });

        // Xử lý nút xóa
        $('#dataTables-donvi').on('click', '.delete-btn', function() {
            var id = $(this).data('id');
            var url = '{{ route("donvi.destroy", ":id") }}'.replace(':id', id);
            $('#deleteForm').attr('action', url);
            $('#deleteModal').modal('show');

        });

        // Khởi tạo DataTables
        $('#dataTables-donvi').DataTable({
            pageLength: 10,
            responsive: true,
            autoWidth: false,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            order: [],
            columnDefs: [{
                orderable: false,
                targets: [0, 4],
                className: 'text-center'
            }],
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
            fixedHeader: true,
            dom: "<'row'" +
                "<'col-md-4'l>" +
                "<'col-md-4'B>" +
                "<'col-md-4'f>" +
                ">" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            buttons: [{
                    extend: 'copy',
                    text: "Copy",
                    exportOptions: {
                        columns: ':visible:not(:last-child)'
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: 'Excel',
                    title: 'Danh sách đơn vị',
                    filename: 'danh_sach_don_vi',
                    exportOptions: {
                        columns: ':visible:not(:last-child)'
                    }
                },
                {
                    extend: 'pdf',
                    text: "PDF",
                    title: 'Danh sách đơn vị',
                    filename: 'danh_sach_don_vi',
                    exportOptions: {
                        columns: ':visible:not(:last-child)'
                    }
                },
                {
                    extend: 'print',
                    title: 'Danh sách đơn vị',
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
        // Xử lý sự kiện khi đóng modal
        $('#addModal').on('hidden.bs.modal', function() {
            $(this).find('form')[0].reset();
            $(this).find('select').val('').trigger('change');
        });
    });
</script>
@endsection