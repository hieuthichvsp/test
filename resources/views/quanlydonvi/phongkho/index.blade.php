@extends('layouts.app')
@section('title', 'Quản lý phòng kho')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12" style="padding-left: 0;">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="my-ibox-title">
                        <h2 class="h2-title">Danh sách phòng kho</h2>
                        @can('isAdmin')
                        <div class="btn-action">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#importExcelModal">
                                <i class="fa fa-file-excel-o"></i>
                                <span class="hidden-sm hidden-xs"> Import Excel</span>
                            </button>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#addModal">
                                <i class="fa fa-plus"></i>
                                <span class="c"> Thêm phòng</span>
                            </button>
                        </div>
                        @endcan
                    </div>
                    <div style="padding: 10px 0">
                        <label for="donvi-filter" class="control-label">Đơn vị quản lý</label>
                        <select id="donvi-filter" class="form-control">
                            <option value="">Tất cả đơn vị</option>
                            @foreach($donvis as $donvi)
                            <option value="{{ $donvi->id }}">{{ $donvi->tendonvi }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="ibox-content">
                    @include('quanlydonvi.phongkho.partials.table')
                </div>
            </div>
        </div>
    </div>
</div>
@include('quanlydonvi.phongkho.partials.modals')
@endsection
@section('js')
<script>
    $(document).ready(function() {
        // Khởi tạo DataTables
        var table = $('#dataTables-phong').DataTable({
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
                targets: [0, 8],
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
                    text: "Excel",
                    filename: 'danh_sach_phong_kho',
                    title: 'Danh sách phòng kho',
                    exportOptions: {
                        columns: ':visible:not(:last-child)'
                    }
                }, ,
                {
                    extend: 'pdf',
                    text: "PDF",
                    filename: 'danh_sach_phong_kho',
                    title: 'Danh sách phòng kho',
                    exportOptions: {
                        columns: ':visible:not(:last-child)'
                    }
                },
                {
                    extend: 'print',
                    text: "Print",
                    title: 'Danh sách phòng kho',
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
        // Lọc theo đơn vị
        $('#donvi-filter').change(function() {
            var selectedValue = $(this).val();
            if (selectedValue === '') {
                table.column(7).search('').draw();
            } else {
                var selectedText = $(this).find('option:selected').text();
                table.column(7).search(selectedText).draw();
            }
        });
        $('#dataTables-phong').on('click', '.edit-btn', function() {
            var id = $(this).data('id');
            $.ajax({
                url: '{{ route("phongkho.edit", ":id") }}'.replace(':id', id),
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Điền thông tin vào form
                    $('#tenphong-edit').val(response.phongkho.tenphong);
                    $('#maphong-edit').val(response.phongkho.maphong);
                    $('#khu-edit').val(response.phongkho.khu);
                    $('#lau-edit').val(response.phongkho.lau);
                    $('#sophong-edit').val(response.phongkho.sophong);
                    $('#magvql-edit').val(response.phongkho.magvql);
                    $('#madonvi-edit').val(response.phongkho.madonvi);
                    // Cập nhật action của form
                    $('#editForm').attr('action', '{{ route("phongkho.update", ":id") }}'.replace(':id', id));
                    $('#editModal').modal('show');
                },
                error: function(xhr) {
                    console.log('Lỗi', xhr);
                    showToast('error', 'Đã xảy ra lỗi khi lấy thông tin phòng kho');
                }
            });
        });
        // Lọc GVQL theo madonvi
        $('#dataTables-phong').on('click', '.delete-btn', function() {
            var id = $(this).data('id');
            var url = '{{ route("phongkho.destroy", ":id") }}'.replace(':id', id);
            $('#deleteForm').attr('action', url);
            $('#deleteModal').modal('show');
        });
        $('#donvi').on('change', function() {
            let madonvi = $(this).val();
            if (madonvi) {
                $.ajax({
                    url: "{{route('phongkho.getGVQLByDonVi',':id')}}".replace(':id', madonvi),
                    method: "GET",
                    success: function(data) {
                        console.log(data);
                        if (data.length > 0) {
                            $('#gvql').empty();
                            $.each(data, function(key, value) {
                                $('#gvql').append('<option value="' + value.id + '">' + value.hoten + '</option>');
                            });
                        } else {
                            $('#gvql').empty();
                            $('#gvql').append('<option value="" disabled selected> Đơn vị chưa có giáo viên quản lý</option>')
                        }
                    }
                });
            } else {
                $('#gvql').empty();
                $('#gvql').append('<option value="" disabled selected>-- Chọn giáo viên quản lý --</option>');
            }
        });
        $('#addModal').on('hidden.bs.modal', function() {
            $(this).find('form')[0].reset();
            $(this).find('select').val('').trigger('change');
        });
    });
</script>
@endsection