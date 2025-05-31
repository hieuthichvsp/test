@extends('layouts.app')
@section('title', 'Quản lý nội thất')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight" id='filterControls1' style="display: none;">
    <div class="row">
        <div class="ibox float-e-margins">
            <div class="ibox-title my-ibox-title">
                <h2 class="h2-title">Danh sách kiểm kê nội thất</h2>
                @can('hasRole_A_M_L')
                <button class="btn btn-primary" data-toggle="modal" data-target="#addModal" id='btnThemMoi'>
                    <i class="fa fa-plus" title="Thêm kiểm kê nội thất"></i>
                    <span class="hidden-sm hidden-xs"> Thêm kiểm kê nội thất</span>
                </button>
                @endcan
            </div>
            <div class="ibox-content">
                <div class="table-responsive">
                    <table id="dataTables-kknt" class="table table-bordered table-striped table-hover table-condensed" style="display:none;">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên thiết bị</th>
                                <th>Mô tả</th>
                                <th>Mã số</th>
                                <th>Năm sử dụng</th>
                                <th>Nguồn gốc</th>
                                <th>Đơn vị tính</th>
                                <th>Số lượng</th>
                                <th>Giá</th>
                                <th>Chất lượng</th>
                                <th>Tình trạng</th>
                                <th>Năm thống kê</th>
                                @canany('hasRole_A_M_L')
                                <th>Thao tác</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@include('quanlynoithat.kiemke.partials.modals')
@endsection
@section('js')
<script>
    $(document).ready(function() {
        // Kiểm tra nếu query string có `openModal=selectDonVi`, mở modal tương ứng
        const shouldOpenModal = "{{ request()->get('openModal') == 'selectDonVi' ? 'true' : 'false' }}";
        if (shouldOpenModal === 'true') {
            $('#selectDonViModal').modal('show');
        }
        // Mở modal khi click vào nút "Chọn đơn vị"
        $(document).on('click', '.openSelectDonVi', function(e) {
            e.preventDefault();
            $('#selectDonViModal').modal('show');
        });

        // Tải danh sách đơn vị theo tổ chức
        $('#toChuc').on('change', function() {
            const toChucId = $(this).val();
            const donViSelect = $('#donVi');
            donViSelect.empty().append('<option value="" selected disabled>--- Chọn đơn vị ---</option>');
            donViSelect.prop('disabled', false);
            if (toChucId) {
                $.ajax({
                    url: "{{ route('noithat.getDonViByToChuc', ':id') }}".replace(':id', toChucId),
                    type: 'GET',
                    success: function(data) {
                        data.forEach(function(donVi) {
                            donViSelect.append(`<option value="${donVi.id}">${donVi.tendonvi}</option>`);
                        });
                    },
                    error: function() {
                        toastr.error('Có lỗi xảy ra khi tải danh sách đơn vị.');
                    }
                });
            }
        });
        $(document).ready(function() {
            const hasPermission = "{{auth()->user()->can('hasRole_A_M_L')}}"
            const targets = hasPermission ? [0, 1, 12] : [0, 1];
            // Khởi tạo DataTable
            var table = $('#dataTables-kknt').DataTable({
                responsive: true,
                fixedHeader: true,
                pageLength: 10,
                lengthMenu: [
                    [5, 10, 25, 50, -1],
                    [5, 10, 25, 50, "Tất cả"]
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
                            columns: ':visible:not(:last-child)' // Xuất trừ cột thao tác
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        filename: 'Danh sách thiết bị',
                        exportOptions: {
                            columns: ':visible:not(:last-child)' // Xuất trừ cột thao tác
                        }
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF',
                        title: 'Danh sách thiết bị',
                        exportOptions: {
                            columns: ':visible:not(:last-child)'
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        title: 'Danh sách thiết bị',
                        exportOptions: {
                            columns: ':visible:not(:last-child)'
                        },
                        customize: function(win) {
                            $(win.document.body).addClass('white-bg').css('font-size', '10px');
                            $(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
                        },

                    }
                ],

            });

            // Bảng thiết bị sẽ được tự động tải dữ liệu khi thay đổi tổ chức và đơn vị
            $('#btnXem').on('click', function() {
                const tochuc_id = $('#toChuc').val();
                const donvi_id = $('#donVi').val();

                if (!tochuc_id || !donvi_id) {
                    toastr.error('Vui lòng chọn tổ chức và đơn vị trước khi xem dữ liệu.');
                    return;
                }

                $.ajax({
                    url: '{{ route("kiemke.locTheoDonVi") }}',
                    method: 'GET',
                    data: {
                        tochuc_id,
                        donvi_id
                    },
                    success: function(data) {
                        // Xóa tất cả các dòng hiện tại của bảng
                        $('#selectDonViModal').modal('hide');
                        table.clear();
                        // Thêm các dòng dữ liệu mới vào bảng
                        data.forEach(function(item) {
                            table.row.add([
                                '',
                                item.tentb,
                                item.mota,
                                item.maso,
                                item.namsd,
                                item.nguongoc,
                                item.donvitinh,
                                item.soluong,
                                item.gia,
                                item.chatluong,
                                item.tinhtrang,
                                item.namthongke,
                                `
                <button 
                    class="btn btn-xs btn-warning edit-btn" style="margin-right:5px"
                    data-id="${item.id}"
                    data-tentb="${item.tentb}"
                    data-mota="${item.mota}"
                    data-maso="${item.maso}"
                    data-namsd="${item.namsd}"
                    data-nguongoc="${item.nguongoc}"
                    data-donvitinh="${item.donvitinh}"
                    data-soluong="${item.soluong}"
                    data-gia="${item.gia}"
                    data-chatluong="${item.chatluong}"
                    data-tinhtrang="${item.tinhtrang}"
                    data-namthongke="${item.namthongke}"
                    data-tooltip="Cập nhật">
                    <i class="fa fa-edit"></i>
                </button>
                @can('hasRole_Admin_Manager')
                <button 
                    class="btn btn-xs btn-danger delete-btn"
                    data-id="${item.id}"
                    data-tooltip="Xóa">
                    <i class="fa fa-trash"></i>
                </button>
                @endcan`
                            ]).draw(false);
                        });

                        // Hiển thị bảng và bộ lọc
                        $('#dataTables-kknt').show();
                        $('#filterControls1').show();
                        $('.dt-buttons').show();
                    },
                    error: function(xhr) {
                        toastr.error('Có lỗi xảy ra khi tải dữ liệu: ' + xhr.responseText);
                    }
                });
            });
        })
        // Mở modal thêm thiết bị
        $(document).on('click', '#btnThemMoi', function() {
            $('#addModal').modal('show');
            $('#addForm')[0].reset(); // Reset form khi mở lại
        });

        // Gửi form thêm thiết bị bằng AJAX
        $('#addForm').submit(function(e) {
            e.preventDefault();

            const formData = $(this).serialize(); // Serialize form data

            $.ajax({
                url: '{{ route("kiemke.store") }}',
                method: 'POST',
                data: formData,
                success: function(response) {
                    console.log(response); // Debug

                    if (response.success) {
                        toastr.success('Thiết bị đã được thêm thành công.');
                        reloadTable(); // Load lại bảng
                        $('#addModal').modal('hide');
                    } else {
                        toastr.error('Có lỗi xảy ra, vui lòng thử lại.');
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error('Lỗi khi gửi yêu cầu: ' + xhr.responseText);
                }
            });
        });




        // Xử lý khi nhấn nút "Sửa" (mở modal chỉnh sửa)
        $(document).on('click', '.edit-btn', function() {
            var modal = $('#editModal');
            modal.modal('show'); // Mở modal

            // Lấy dữ liệu từ các thuộc tính data-*
            const id = $(this).data('id');
            const tentb = $(this).data('tentb');
            const mota = $(this).data('mota');
            const maso = $(this).data('maso');
            const namsd = $(this).data('namsd');
            const nguongoc = $(this).data('nguongoc');
            const donvitinh = $(this).data('donvitinh');
            const soluong = $(this).data('soluong');
            const gia = $(this).data('gia');
            const chatluong = $(this).data('chatluong');
            const tinhtrang = $(this).data('tinhtrang');
            const namthongke = $(this).data('namthongke');

            // Cập nhật giá trị vào form trong modal
            $('#tentb').val(tentb);
            $('#mota').val(mota);
            $('#maso').val(maso);
            $('#namsd').val(namsd);
            $('#nguongoc').val(nguongoc);
            $('#donvitinh').val(donvitinh);
            $('#soluong').val(soluong);
            $('#gia').val(gia);
            $('#chatluong').val(chatluong);
            $('#tinhtrang').val(tinhtrang);
            $('#namthongke').val(namthongke);

            // Cập nhật action form
            $('#editForm').attr('action', "{{route('kiemke.update', ':id')}}".replace(":id", id));
        });
    });

    $('#editForm').on('submit', function(e) {
        e.preventDefault(); // Không submit mặc định

        const form = $(this);
        const actionUrl = form.attr('action');
        const formData = form.serialize(); // Lấy dữ liệu form

        $.ajax({
            url: actionUrl,
            method: 'POST',
            data: formData,
            success: function(response) {
                $('#editModal').modal('hide'); // Đóng modal chỉnh sửa

                // Load lại bảng thiết bị
                reloadTable();

                toastr.success('Cập nhật kiểm kê đồ gỗ thành công!');
            },
            error: function(xhr) {
                // Xử lý lỗi nếu có
                toastr.error('Có lỗi xảy ra khi cập nhật thiết bị: ' + xhr.responseText);
            }
        });
    });


    // ====== Hàm load lại bảng thiết bị ======
    function reloadTable() {
        const tochuc_id = $('#toChuc').val();
        const donvi_id = $('#donVi').val();

        if (!tochuc_id || !donvi_id) {
            return;
        }

        $.ajax({
            url: '{{ route("kiemke.locTheoDonVi") }}',
            method: 'GET',
            data: {
                tochuc_id,
                donvi_id
            },
            success: function(data) {
                // Lấy lại đối tượng DataTable
                var table = $('#dataTables-kknt').DataTable();
                table.clear(); // Xóa dữ liệu cũ

                data.forEach(function(item) {
                    table.row.add([
                        '',
                        item.tentb,
                        item.mota,
                        item.maso,
                        item.namsd,
                        item.nguongoc,
                        item.donvitinh,
                        item.soluong,
                        item.gia,
                        item.chatluong,
                        item.tinhtrang,
                        item.namthongke,
                        `
                    <button 
                        class="btn btn-xs btn-warning edit-btn" style="margin-right:5px"
                        data-id="${item.id}"
                        data-tentb="${item.tentb}"
                        data-mota="${item.mota}"
                        data-maso="${item.maso}"
                        data-namsd="${item.namsd}"
                        data-nguongoc="${item.nguongoc}"
                        data-donvitinh="${item.donvitinh}"
                        data-soluong="${item.soluong}"
                        data-gia="${item.gia}"
                        data-chatluong="${item.chatluong}"
                        data-tinhtrang="${item.tinhtrang}"
                        data-namthongke="${item.namthongke}"
                        data-tooltip="Cập nhật">
                        <i class="fa fa-edit"></i>
                    </button>
                    @can('hasRole_Admin_Manager')
                    <button 
                        class="btn btn-xs btn-danger delete-btn"
                        data-id="${item.id}"
                        data-tooltip="Xóa">
                        <i class="fa fa-trash"></i>
                    </button>
                    @endcan
                    `
                    ]);
                });
                table.draw(); // Vẽ lại bảng
            },
            error: function(xhr) {
                tóastr.error('Có lỗi xảy ra khi tải lại bảng: ' + xhr.responseText);
            }
        });
    }

    // Mở modal khi nhấn nút xóa
    $(document).on('click', '.delete-btn', function() {
        const id = $(this).data('id');
        const action = "{{ route('kiemke.destroy', ':id') }}".replace(':id', id);
        $('#deleteForm').attr('action', action);
        $('#deleteModal').modal('show');
    });

    // Gửi form xóa
    $('#deleteForm').on('submit', function(e) {
        e.preventDefault();
        const actionUrl = $(this).attr('action');

        $.ajax({
            url: actionUrl,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'DELETE'
            },
            success: function(res) {
                $('#deleteModal').modal('hide');
                reloadTable(); // Load lại bảng sau khi xóa
                toastr.success('Xóa kiểm kê đồ gỗ thành công!');
            },
            error: function(xhr) {
                toastr.success('Có lỗi xảy ra khi xóa kiểm kê đồ gỗ: ' + xhr.responseText);
            }
        });
    });
</script>

@endsection