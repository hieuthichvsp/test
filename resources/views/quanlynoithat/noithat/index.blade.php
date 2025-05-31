@extends('layouts.app')
@section('title', 'Quản lý nội thất')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight" id="filterControls" style="display: none;">
    <div class="row">
        <div class="ibox float-e-margins">
            <div class="ibox-title my-ibox-title">
                <h2 class="h2-title">Danh sách thiết bị nội thất</h2>
                <div class="btn-action">
                    @can('hasRole_A_M_L')
                    <button class="btn btn-primary" data-toggle="modal" data-target="#addModal" id="btnThemMoi">
                        <i class="fa fa-plus" title="Thêm nội thất"></i>
                        <span class="hidden-sm hidden-xs"> Thêm nội thất</span>
                    </button>
                    <button class="btn btn-warning" data-toggle="modal" data-target="#statusModal" id="btnDoiTinhTrang">
                        <i class="fa fa-refresh" title="Đổi tình trạng"></i>
                        <span class="hidden-sm hidden-xs"> Đổi tình trạng</span>
                    </button>
                    @endcan
                    @can('isAdmin')
                    <button id="btnXoaNhieu" class="btn btn-danger">
                        <i class="fa fa-trash" title="Xóa nhiều thiết bị"></i>
                        <span class="hidden-sm hidden-xs"> Xóa nhiều thiết bị</span>
                    </button>
                    @endcan
                    <button id="btnLocPhong" class="btn btn-info">
                        <i class="fa fa-filter" title="Phòng"></i>
                        <span class="hidden-sm hidden-xs"> Phòng</span>
                    </button>
                </div>
            </div>

            <div class="ibox-content">
                <div class="table-responsive">
                    <table id="dataTables-noithat" class="table table-bordered table-striped table-hover table-condensed" style="display:none;">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Chọn hết<br><input type="checkbox" id="checkAll"></th>
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
                                @canany('hasRole_A_M_L')
                                <th>Thao tác</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            <!-- dữ liệu bảng -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@include('quanlynoithat.noithat.partials.modals')
@endsection
@section('js')
<script>
    var idToChuc = null;
    var idDonVi = null;

    $(document).ready(function() {
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
            idToChuc = $(this).val();
            const donViSelect = $('#donVi');
            donViSelect.empty().append('<option value="">--- Chọn đơn vị ---</option>');
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
        $('#donVi').on('change', function() {
            idDonVi = $(this).val();
            console.log('Đơn vị được chọn:', idDonVi);
        });
        const hasPermission = "{{auth()->user()->can('hasRole_A_M_L')}}"
        const targets = hasPermission ? [0, 1, 12] : [0, 1];
        // Khởi tạo DataTable
        var table = $('#dataTables-noithat').DataTable({
            pageLength: 10,
            responsive: true,
            lengthMenu: [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, "Tất cả"]
            ],
            order: [], // Không sắp xếp mặc định
            columnDefs: [{
                orderable: false,
                targets: targets, // Không sắp xếp cột STT và Thao tác
                className: 'text-center' // Căn giữa cho cột STT
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
                        columns: [0, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11] // Xuất trừ cột thao tác
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: 'Excel',
                    filename: 'Danh sách nội thất',
                    exportOptions: {
                        columns: [0, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11] // Xuất trừ cột thao tác
                    }
                },
                {
                    extend: 'pdf',
                    text: 'PDF',
                    title: 'Danh sách nội thất',
                    exportOptions: {
                        columns: [0, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11] // Xuất trừ cột thao tác
                    }
                },
                {
                    extend: 'print',
                    text: 'Print',
                    title: 'Danh sách nội thất',
                    exportOptions: {
                        columns: [0, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11] // Xuất trừ cột thao tác
                    },
                    customize: function(win) {
                        $(win.document.body).addClass('white-bg').css('font-size', '10px');
                        $(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
                    },

                }
            ]

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
                url: '{{ route("noithat.locTheoDonVi") }}',
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
                            `
                            <input type="checkbox" class="selectRow" data-id="${item.id}">
                            `,
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
                            `
                <button 
                    class="btn btn-xs btn-warning edit-btn"
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
                    data-tooltip="Cập nhật">
                    <i class="fa fa-edit"></i>
                </button>
                @can('hasRole_Admin_Manager')
                <button 
                    class="btn btn-xs btn-danger delete-btn"
                    data-id="${item.id}"
                    data-tooltip="Xóa"
                    title="Xóa">
                    <i class="fa fa-trash"></i>
                </button>
                @endcan
                `
                        ]).draw(false);
                    });

                    // Hiển thị bảng và bộ lọc
                    $('#dataTables-noithat').show();
                    $('#filterControls').show();
                    // Sau khi dữ liệu được tải về hoặc bạn muốn hiển thị lại các nút
                    $('.dt-buttons').show();

                    $('#dataTables-noithat_wrapper .dataTables_filter').show(); // hiện ô tìm kiếm
                    $('#dataTables-noithat_wrapper .dataTables_length').show(); // hiện chọn số dòng
                    $('#dataTables-noithat_wrapper .dataTables_paginate').show(); // hiện phân trang
                    $('#dataTables-noithat_wrapper .dataTables_info').show(); // hiện info số dòng
                },
                error: function(xhr) {
                    toastr.error('Có lỗi xảy ra khi tải dữ liệu: ' + xhr.responseText);
                }
            });
        });
    })

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

        // Cập nhật action form
        $('#editForm').attr('action', "{{route('noithat.update',':id')}}".replace(':id', id));
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

                toastr.success('Cập nhật đồ gỗ thành công!');
            },
            error: function(xhr) {
                toastr.error('Có lỗi xảy ra khi cập nhật đồ gỗ: ' + xhr.responseText);
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
            url: '{{ route("noithat.locTheoDonVi") }}',
            method: 'GET',
            data: {
                tochuc_id,
                donvi_id
            },
            success: function(data) {
                var table = $('#dataTables-noithat').DataTable();
                table.clear(); // Xóa dữ liệu cũ

                if (data.length > 0) {
                    data.forEach(function(item) {
                        table.row.add([
                            '', // Cột STT để DataTable tự đánh số
                            `
                            <input type="checkbox" class="selectRow" data-id="${item.id}">
                            `,
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
                            data-tooltip="Cập nhật">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button 
                            class="btn btn-xs btn-danger delete-btn" 
                            data-id="${item.id}" 
                            data-tooltip="Xóa">
                            <i class="fa fa-trash"></i>
                        </button>
                        `
                        ]).draw(false);
                    });
                } else {
                    // Nếu không có dữ liệu, thêm 1 dòng trống
                    table.row.add([
                        '', '', 'Không có thiết bị nào.', '', '', '', '', '', '', '', '', '', ''
                    ]).draw(false);
                }

                $('#dataTables-noithat').show();
                $('#filterControls').show();
            },
            error: function(xhr) {
                toastr.error('Có lỗi xảy ra khi tải lại bảng thiết bị: ' + xhr.responseText);
            }
        });
    }


    $(document).on('click', '#btnThemMoi', function() {
        $('#addModal').modal('show');
        $('#addForm')[0].reset(); // Reset form khi mở lại
    });

    // Gửi form thêm thiết bị bằng AJAX
    $('#addForm').submit(function(e) {
        e.preventDefault();
        const formData = $(this).serialize(); // Serialize form data

        $.ajax({
            url: '{{route("noithat.store")}}', // Đường dẫn lưu thiết bị
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    toastr.success('Thêm thiết bị đồ gỗ thành công!');
                    $('#addModal').modal('hide'); // Đóng modal
                    reloadTable(); // Load lại bảng sau khi thêm
                } else if (response.error) {
                    toastr.error('Có lỗi xảy ra khi thêm thiết bị đồ gỗ: ' + response.error);
                }

            },
            error: function(xhr, status, error) {
                toastr.error('Có lỗi xảy ra khi thêm thiết bị đồ gỗ: ' + xhr.responseText);
            }
        });
    });

    // Mở modal khi nhấn nút xóa
    $('#dataTables-noithat').on('click', '.delete-btn', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        const action = "{{ route('noithat.destroy', ':id') }}".replace(':id', id);
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
                toastr.success('Xóa thiết bị thành công!');
            },
            error: function(xhr) {
                toastr.error('Có lỗi xảy ra khi xóa thiết bị: ' + xhr.responseText);
            }
        });
    });



    // Chọn tất cả
    $('#checkAll').on('click', function() {
        var checked = $(this).prop('checked');
        $('.selectRow').prop('checked', checked);
    });

    // Cập nhật trạng thái chọn tất cả nếu có checkbox bị bỏ
    $(document).on('change', '.selectRow', function() {
        var total = $('.selectRow').length;
        var checked = $('.selectRow:checked').length;
        $('#checkAll').prop('checked', total === checked);
    });

    let selectedIds = [];

    $('#btnDoiTinhTrang').on('click', function() {
        selectedIds = []; // Reset danh sách

        $('.selectRow:checked').each(function() {
            selectedIds.push($(this).data('id'));
        });

        if (selectedIds.length === 0) {
            toastr.error('Vui lòng chọn ít nhất một thiết bị để thay đổi tình trạng!');
            return;
        }
        $('#modalThayDoiTinhTrang').modal('show');
    });

    $('#btnThayDoi').on('click', function() {
        var tinhTrang = $('#formThayDoiTinhTrang select[name="matinhtrang"]').val();

        if (!tinhTrang) {
            toastr.error('Vui lòng chọn tình trạng mới!');
            return;
        }
        $.ajax({
            url: "{{ route('noithat.updateTinhTrang') }}",
            method: 'POST',
            data: {
                ids: selectedIds,
                matinhtrang: tinhTrang,
                _token: '{{ csrf_token() }}'
            },
            success: function(res) {
                toastr.success('Cập nhật tình trạng thiết bị thành công!');
                $('#modalThayDoiTinhTrang').modal('hide');
                reloadTable();
            },
            error: function(xhr) {
                toastr.error('Có lỗi xảy ra khi cập nhật tình trạng thiết bị: ' + xhr.responseText);
            }
        });
    });
    // Xóa tất cả các thiết bị đã chọn
    let selectedIdsxoa = []; // Biến toàn cục dùng chung cho cả thay đổi tình trạng và xóa

    // Nút "Xóa nhiều"
    $('#btnXoaNhieu').on('click', function() {
        selectedIdsxoa = []; // Đúng: reset mảng đúng biến

        $('.selectRow:checked').each(function() {
            selectedIdsxoa.push($(this).data('id'));
        });

        if (selectedIdsxoa.length === 0) {
            toastr.error('Vui lòng chọn ít nhất một thiết bị để xóa!');
            return; // Không hiện modal nếu không có dòng nào được chọn
        }

        $('#deleteModalxoa').modal('show'); // Chỉ hiện khi có dòng được chọn
    });

    // Xử lý khi người dùng xác nhận xóa
    $('#btnXacNhanXoaNhieu').on('click', function() {
        if (!selectedIdsxoa || selectedIdsxoa.length === 0) {
            toastr.warning('Bạn chưa chọn thiết bị nào để xóa.');
            return;
        }

        $.ajax({
            url: "{{ route('noithat.xoaNhieu') }}",
            method: 'POST',
            data: {
                ids: selectedIdsxoa,
                _method: 'DELETE',
                _token: '{{ csrf_token() }}'
            },
            success: function(res) {
                toastr.success(res.message || 'Đã xóa thiết bị thành công!');
                $('#deleteModalxoa').modal('hide');
                reloadTable(); // Hàm reload lại bảng dữ liệu của bạn
            },
            error: function(xhr) {
                toastr.error('Có lỗi xảy ra khi xóa thiết bị: ' + xhr.responseText);
            }
        });
    });


    //lọc phongh
    $('#btnLocPhong').on('click', function() {
        if (!idDonVi) {
            toastr.warning('Vui lòng chọn Đơn vị trước khi lọc phòng.');
            return;
        }

        $.ajax({
            url: `{{ route('noithat.getPhongTheoDonVi', ':madonvi') }}`.replace(':madonvi', idDonVi),
            type: 'GET',
            success: function(data) {
                var phongSelect = $('#maphongkho');
                phongSelect.empty().append('<option value="">-- Chọn phòng kho --</option>');
                data.forEach(function(phong) {
                    phongSelect.append(`<option value="${phong.id}">${phong.tenphong}</option>`);
                });
                $('#modalLocPhong').modal('show');
            },
            error: function() {
                toastr.error('Có lỗi xảy ra khi tải danh sách phòng kho.');
            }
        });
    });
    $('#btnHienThiPhong').on('click', function() {
        var maphong = $('#maphongkho').val();
        if (!maphong) {
            toastr.warning('Vui lòng chọn phòng kho trước khi hiển thị thiết bị.');
            return;
        }

        $.ajax({
            url: "{{ route('noithat.getThietBiTheoPhong',':maphong') }}".replace(':maphong', maphong),
            type: 'GET',
            success: function(data) {
                // Giả sử bạn đã khởi tạo DataTable sẵn và lưu vào biến table
                // Ví dụ: 
                var table = $('#dataTables-noithat').DataTable();

                table.clear(); // Xóa dữ liệu cũ

                if (data.length > 0) {
                    data.forEach(function(item) {
                        table.row.add([
                            '', // Cột STT, DataTable tự đánh số nếu cấu hình
                            `<input type="checkbox" class="selectRow" data-id="${item.id}">`,
                            item.tentb || '',
                            item.mota || '',
                            item.maso || '',
                            item.namsd || '',
                            item.nguongoc || '',
                            item.donvitinh || '',
                            item.soluong || '',
                            item.gia || '',
                            item.chatluong || '',
                            item.tinhtrang || '',
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
                        ]).draw(false);
                    });
                } else {
                    // Nếu không có dữ liệu, thêm 1 dòng trống
                    table.row.add([
                        '', '', 'Không có thiết bị nào.', '', '', '', '', '', '', '', '', '', ''
                    ]).draw(false);
                }

                $('#dataTables-noithat').show();
                $('#filterControls').show();
                $('#modalLocPhong').modal('hide');

            },
            error: function(xhr) {
                toastr.error('Có lỗi xảy ra khi tải lại bảng thiết bị: ' + xhr.responseText);
            }
        });
    });
</script>

@endsection