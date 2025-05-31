@extends('layouts.app')
@section('title', 'Quản lý học kỳ')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="ibox float-e-margins">
            <div class="ibox-title my-ibox-title">
                <h2 class="h2-title">Danh sách học kỳ</h2>
                @can('isAdmin')
                <button class="btn btn-primary" data-toggle="modal" data-target="#addModal">
                    <i class="fa fa-plus"></i> Thêm học kỳ
                </button>
                @endcan
            </div>
            <div class="ibox-content">
                <div class="table-responsive">
                    <table id="dataTables-hocky" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên học kỳ</th>
                                <th>Từ năm</th>
                                <th>Đến năm</th>
                                <th>Học kỳ hiện tại</th>
                                @can('isAdmin')
                                <th>Thao tác</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hockies as $hocky)
                            <tr class="gradeX">
                                <td>{{ $loop->iteration }}</td>
                                <td>Học kỳ {{ $hocky->hocky }}</td>
                                <td>{{ $hocky->tunam}}</td>
                                <td>{{ $hocky->dennam}}</td>
                                <td>
                                    @can('isAdmin')
                                    <input type="checkbox" class="checkHocky" data-id="{{ $hocky->id }}" autocomplete="off" {{ $hocky->current == 1 ? 'checked' : '' }}>
                                    @else
                                    <input type="checkbox" {{ $hocky->current == 1 ? 'checked' : '' }} onclick="return false;">
                                    @endcan
                                </td>
                                @can('isAdmin')
                                <td class="btn-action">
                                    <button type="button" class="btn btn-warning btn-xs edit-btn"
                                        data-tooltip="Cập nhật"
                                        data-id="{{ $hocky->id }}">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-xs delete-btn"
                                        data-tooltip="Xóa"
                                        data-id="{{ $hocky->id }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
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
@include('ghisonhatky.quanlyhocky.partials.modals')
@endsection
@section('js')
<script>
    $(document).ready(function() {
        const hasPermission = "{{auth()->user()->can('isAdmin')}}"
        const targets = hasPermission ? [0, 4, 5] : [0, 4];
        // Khởi tạo DataTables
        let table = $('#dataTables-hocky').DataTable({
            pageLength: 10,
            responsive: true,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            order: [],
            columnDefs: [{
                orderable: false,
                targets: targets,
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
                        columns: [0, 1, 2, 3]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: 'Excel',
                    title: 'Danh sách học kỳ',
                    filename: 'danh_sach_hoc_ky',
                    exportOptions: {
                        columns: [0, 1, 2, 3]
                    }
                },
                {
                    extend: 'pdf',
                    text: 'PDF',
                    title: 'Danh sách học kỳ',
                    filename: 'danh_sach_hoc_ky',
                    exportOptions: {
                        columns: [0, 1, 2, 3]
                    }
                },
                {
                    extend: 'print',
                    text: 'Print',
                    title: 'Danh sách học kỳ',
                    exportOptions: {
                        columns: [0, 1, 2, 3]
                    },
                    customize: function(win) {
                        $(win.document.body).addClass('white-bg').css('font-size', '10px');
                        $(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
                    }
                }
            ]
        });
        // Xử lý nút xóa
        $('#dataTables-hocky').on('click', '.delete-btn', function() {
            var id = $(this).data('id');
            var url = '{{ route("hocky.destroy", ":id") }}'.replace(':id', id);
            // Hiển thị modal xác nhận xóa   
            $('#deleteForm').attr('action', url);
            $('#deleteModal').modal('show');
        });
        $('#dataTables-hocky').on('click', '.edit-btn', function() {
            // Lấy dòng hiện tại
            var row = $(this).closest('tr');
            var rowData = table.row(row).data();
            var id = $(this).data('id');
            var tenhocky_text = rowData[1]; // ví dụ "Học kỳ phụ 1"
            let tunam = parseInt(rowData[2].trim());
            let dennam = parseInt(rowData[3].trim());

            // Tạo ánh xạ tên học kỳ → value
            var hockyMap = {
                'Học kỳ 1': '1',
                'Học kỳ 2': '2',
                'Học kỳ phụ 1': 'phụ 1',
                'Học kỳ phụ 2': 'phụ 2',
            };

            // Tìm value tương ứng
            var hocky_value = hockyMap[tenhocky_text];

            // Gán vào select
            $('#edit_hocky').val(hocky_value);
            $('#edit_tunam').val(tunam);
            $('#edit_dennam').val(dennam);

            // Mở modal và cập nhật action  
            $('#editForm').attr('action', '{{ route("hocky.update", ":id") }}'.replace(':id', id));
            $('#editModal').modal('show');
        });

        // let tunam = "input[name='tunam']";
        // let dennam = "input[name='dennam']";

        // function validateYear(aSelector, bSelector) {
        //     let a = parseInt($(aSelector).val());
        //     let b = parseInt($(bSelector).val());
        //     if (a >= b) {
        //         $(".error-message").show();
        //         $(".error-message").text("Năm bắt đầu không được lớn hơn hoặc bằng năm kết thúc.");
        //         return false;
        //     } else {
        //         $(".error-message").hide('');
        //         return true;
        //     }
        // }
        // $("#addModal").on("submit", function(e) {
        //     let validate = validateYear(tunam, dennam);
        //     if (!validate) {
        //         e.preventDefault();
        //     }
        // });
        // $("#editModal").on("submit", function(e) {
        //     let validate = validateYear(tunam, dennam);
        //     if (!validate)
        //         e.preventDefault();
        // });
        // $(tunam).on("input", validateYear);
        // $(dennam).on("input", validateYear);
        // $(tunam).on("focus", function() {
        //     $(".error-message").hide();
        // });
        // $(dennam).on("focus", function() {
        //     $(".error-message").hide();
        // });
    });
    $('#addModal').on('hidden.bs.modal', function() {
        $(this).find('form')[0].reset();
        $(".error-message").hide();
    });
    $(document).on('click', '.checkHocky', function(e) {
        e.preventDefault();
        const checkbox = $(this);
        const hockyId = checkbox.data('id');
        Swal.fire({
            title: 'Xác nhận đổi học kỳ hiện tại?',
            text: 'Chỉ một học kỳ có thể là hiện tại. Bạn có muốn thay đổi?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy bỏ',
            reverseButtons: true,
            allowOutsideClick: false, // Bảo vệ thêm
            allowEscapeKey: false
        }).then((result) => {
            if (result.isConfirmed) {
                checkbox.prop('checked', true);
                $.ajax({
                    url: '{{ route("hocky.saveHocKyCurrent", ":id") }}'.replace(':id', hockyId),
                    type: 'POST',
                    dataType: 'json',
                    data: {},
                    success: function(response) {
                        if (response.status) {
                            Swal.fire({
                                title: 'Thành công!',
                                text: response.message || 'Đã cập nhật học kỳ hiện tại.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.reload();
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Lỗi!',
                            text: 'Không thể cập nhật học kỳ. Vui lòng thử lại.\n' + xhr.responseText,
                            icon: 'error'
                        });
                    }
                });
            }
        });
    });
</script>
@endsection