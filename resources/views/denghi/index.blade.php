@extends('layouts.app')
@section('title', 'Quản lý đề nghị')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="ibox float-e-margins">
            <div class="ibox-title my-ibox-title d-flex justify-content-between align-items-center">
                <h2 class="h2-title">Danh sách đề nghị</h2>
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
                                <td class="btn-action">
                                    <button class="btn btn-warning btn-xs edit-btn"
                                        data-tooltip="Cập nhật"
                                        data-id="{{ $denghi->id }}"
                                        data-toggle="modal"
                                        data-target="#editDeNghiModal">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    @can('isAdmin')
                                    <button class="btn btn-danger btn-xs delete-btn"
                                        data-tooltip="Xóa"
                                        data-id="{{ $denghi->id }}"
                                        data-toggle="modal"
                                        data-target="#deleteDeNghiModal">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    @endcan
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
@include('denghi.partials.modals')
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
                "<'col-md-4'B>" +
                "<'col-md-4'f>" +
                ">" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            buttons: [{
                    extend: 'copy',
                    exportOptions: {
                        columns: ':visible:not(:last-child)'
                    }
                },

                {
                    extend: 'excel',
                    text: 'Excel',
                    title: 'Danh sách đề nghị',
                    filenam: "danh_sach_de_nghi",
                    exportOptions: {
                        columns: ':visible:not(:last-child)'
                    }
                },
                {
                    extend: 'pdf',
                    title: 'Danh sách đề nghị',
                    filenam: "danh_sach_de_nghi",
                    exportOptions: {
                        columns: ':visible:not(:last-child)'
                    }
                },
                {
                    extend: 'print',
                    title: 'Danh sách đề nghị',
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

        // Xử lý sự kiện khi nhấn nút Sửa
        $('.edit-btn').click(function() {
            var id = $(this).data('id');
            let url_upd = "{{route('denghi.update',':id')}}".replace(':id', id);
            $('#editForm').attr('action', url_upd);

            $.ajax({
                url: '{{route("denghi.edit",":id")}}'.replace(":id", id),
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
                            const url_download = "{{route('denghi.download',':id')}}".replace(':id', file.id);
                            var row = '<tr>' +
                                '<td>' + (index + 1) + '</td>' +
                                '<td>' + file.ten_file + '</td>' +
                                '<td>' + file.loai_file + '</td>' +
                                '<td>' +
                                '<a href="' + url_download + '" class="btn btn-primary btn-xs"><i class="fa fa-download"></i> Tải xuống</a> ' +
                                '@can("hasRole_Admin_Manager")<button type="button" class="btn btn-danger btn-xs delete-file" data-id="' + file.id + '"><i class="fa fa-trash"></i> Xóa</button>@endcan' +
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
            let url_del = "{{route('denghi.destroy',':id')}}".replace(':id', id)
            $('#deleteForm').attr('action', url_del);
        });

        // Xử lý sự kiện xóa file trong modal chỉnh sửa
        $(document).on('click', '.delete-file', function() {
            var fileId = $(this).data('id');
            var row = $(this).closest('tr');

            if (confirm('Bạn có chắc chắn muốn xóa tập tin này không?')) {
                $.ajax({
                    url: '{{route("denghi.deleteFile",":id")}}'.replace(":id", fileId),
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
                        toastr.error('Đã xảy ra lỗi khi xóa tập tin' + xhr.responseText);
                    }
                });
            }
        });
    });
</script>
@endsection