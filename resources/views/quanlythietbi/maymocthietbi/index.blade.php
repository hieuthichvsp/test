@extends('layouts.app')
@section('title', 'Quản lý máy móc thiết bị')

@section('css')
<link href="{{ asset('css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
@endsection

@section('content')
<!-- Thêm overlay loading -->
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="ibox float-e-margins">
            <div class="ibox-title my-ibox-title">
                <h2 class="h2-title">Danh sách máy móc thiết bị</h2>
                @canany('hasRole_A_M_L')
                <div class="btn-action">
                    <button class="btn btn-success" data-toggle="modal" data-target="#addMMTBModal">
                        <i class="fa fa-plus" title="Thêm thiết bị"></i>
                        <span class="hidden-sm hidden-xs"> Thêm thiết bị</span>
                    </button>
                    @can('hasRole_Admin_Manager')
                    <button class="btn btn-success" data-toggle="modal" data-target="#importModal">
                        <i class="fa fa-file-excel-o" title="Import excel"></i>
                        <span class="hidden-sm hidden-xs"> Import excel</span>
                    </button>
                    <button type="button" class="btn btn-info" id="btnThongKe" data-toggle="modal" data-target="#thongKeModal">
                        <i class="fa fa-bar-chart" title="Thống kê"></i>
                        <span class="hidden-sm hidden-xs"> Thống kê</span>
                    </button>
                    @endcan
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#filterModal">
                        <i class="fa fa-filter" title="Lọc dữ liệu"></i>
                        <span class="hidden-sm hidden-xs"> Lọc dữ liệu</span>
                    </button>

                    @if(request()->has('maloai') || request()->has('manhom') || request()->has('maphongkho') || request()->has('matinhtrang'))
                    <a href="{{ route('maymocthietbi.index') }}" class="btn btn-warning">
                        <i class="fa fa-times" title="Hủy lọc"></i>
                        <span class="hidden-sm hidden-xs"> Hủy lọc</span>
                    </a>
                    <a href="{{ route('thongke.xuatexcel') }}" class="btn btn-success">
                        <i class="fa fa-file-excel-o" title="Xuất excel"></i>
                        <span class="hidden-sm hidden-xs"> Xuất excel</span>
                    </a>
                    @endif
                </div>
                @endcan
            </div>
            <div class="ibox-content">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="maymocthietbi-table" style="width: 100%">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Mã số</th>
                                <th>Tên thiết bị</th>
                                <th>Model</th>
                                <th>Loại</th>
                                <th>Đơn vị</th>
                                <th>Số lượng</th>
                                <th>Tình trạng</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@include('quanlythietbi.maymocthietbi.partials.modals')
@endsection

@section('js')
<script src="{{ asset('js/plugins/dataTables/datatables.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#filter_madonvi').change(function() {
            let madonvi = $(this).val();

            // Hiển thị tất cả các phòng/kho nếu không chọn đơn vị
            if (madonvi === '') {
                $('#filter_maphongkho option').show();
                return;
            }
            $('#filter_maphongkho option').hide();
            $('#filter_maphongkho option[value=""]').show();
            $('#filter_maphongkho option[data-donvi="' + madonvi + '"]').show();
            if ($('#filter_maphongkho option:selected').is(':hidden')) {
                $('#filter_maphongkho').val('');
            }
        });
        $(document).ready(function() {
            if ($('#filter_madonvi').val() !== '') {
                $('#filter_madonvi').trigger('change');
            }
        });
        const hasPermission = "{{auth()->user()->can('hasRole_A_M_L')}}";

        // Khởi tạo DataTable với server-side processing
        let table = $('#maymocthietbi-table').DataTable({
            serverSide: true,
            ajax: {
                url: "{{ route('maymocthietbi.get-data') }}",
                data: function(d) {
                    d.madonvi = "{{ request('madonvi') }}";
                    d.maphongkho = "{{ request('maphongkho') }}";
                    d.maloai = "{{ request('maloai') }}";
                    d.manhom = "{{ request('manhom') }}";
                    d.matinhtrang = "{{ request('matinhtrang') }}";
                    d.namsd = "{{ request('namsd') }}";
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'maso',
                    name: 'maso'
                },
                {
                    data: 'tentb',
                    name: 'tentb'
                },
                {
                    data: 'model',
                    name: 'model'
                },
                {
                    data: 'loaimaymocthietbi.tenloai',
                    name: 'loaimaymocthietbi.tenloai'
                },
                {
                    data: 'donvitinh',
                    name: 'donvitinh'
                },
                {
                    data: 'soluong',
                    name: 'soluong'
                },
                {
                    data: 'tinhtrangthietbi.tinhtrang',
                    name: 'tinhtrangthietbi.tinhtrang'
                },
                {
                    data: 'action',
                    name: 'action'
                }
            ],
            pageLength: 10,
            orders: [],
            columnDefs: [{
                orderable: false,
                targets: [0, 8],
                className: 'text-center'
            }],
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
                    text: 'Copy',
                    exportOptions: {
                        columns: ':visible:not(:last-child)'
                    }
                },
                {
                    extend: 'excel',
                    text: 'Excel',
                    title: 'Danh sách máy móc thiết bị',
                    filename: 'danh_sach_may_moc_thiet_bi',
                    exportOptions: {
                        columns: ':visible:not(:last-child)'
                    }
                },
                {
                    extend: 'pdf',
                    text: 'PDF',
                    title: 'Danh sách máy móc thiết bị',
                    filename: 'danh_sach_may_moc_thiet_bi',
                    exportOptions: {
                        columns: ':visible:not(:last-child)'
                    }
                },
                {
                    extend: 'print',
                    text: 'Print',
                    title: 'Danh sách máy móc thiết bị',
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

        // Xử lý sự kiện cho các nút
        $('#maymocthietbi-table').on('click', '.edit-btn', function() {
            let id = $(this).data('id');
            $.ajax({
                url: "{{route('maymocthietbi.edit', ':id')}}".replace(':id', id),
                type: 'GET',
                success: function(response) {
                    // Điền thông tin vào form chỉnh sửa
                    $('#edit_tentb').val(response.maymocthietbi.tentb);
                    $('#edit_model').val(response.maymocthietbi.model);
                    $('#edit_maso').val(response.maymocthietbi.maso);
                    $('#edit_somay').val(response.maymocthietbi.somay);
                    $('#edit_maloai').val(response.maymocthietbi.maloai);
                    $('#edit_manhom').val(response.maymocthietbi.manhom);
                    $('#edit_donvitinh').val(response.maymocthietbi.donvitinh);
                    $('#edit_soluong').val(response.maymocthietbi.soluong);
                    $('#edit_gia').val(response.maymocthietbi.gia);
                    $('#edit_namsd').val(response.maymocthietbi.namsd);
                    $('#edit_nguongoc').val(response.maymocthietbi.nguongoc);
                    $('#edit_matinhtrang').val(response.maymocthietbi.matinhtrang);
                    $('#edit_maphongkho').val(response.maymocthietbi.maphongkho);
                    $('#edit_chatluong').val(response.maymocthietbi.chatluong);
                    $('#edit_mota').val(response.maymocthietbi.mota);
                    $('#edit_ghichu').val(response.maymocthietbi.ghichu);
                    $('#edit_ghichutinhtrang').val(response.maymocthietbi.ghichutinhtrang);

                    // Hiển thị modal chỉnh sửa
                    $('#editForm').attr('action', "{{route('maymocthietbi.update', ':id')}}".replace(':id', id));
                    $('#editMMTBModal').modal('show');

                    // Ẩn overlay loading
                },
                error: function() {
                    // Ẩn overlay loading
                    alert('Có lỗi xảy ra khi tải thông tin thiết bị');
                }
            });
        });

        // Xử lý sự kiện cho nút xóa
        $('#maymocthietbi-table').on('click', '.delete-btn', function() {
            let id = $(this).data('id');
            $('#deleteForm').attr('action', "{{route('maymocthietbi.destroy', ':id')}}".replace(':id', id));
        });

        // Xử lý sự kiện cho nút xem chi tiết
        $('#maymocthietbi-table').on('click', '.view-btn', function() {
            let id = $(this).data('id');

            $.ajax({
                url: "{{route('maymocthietbi.show', ':id')}}".replace(':id', id),
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (!response || !response.maymocthietbi) {
                        alert('Không tìm thấy dữ liệu thiết bị');
                        return;
                    }

                    // Get data objects with null checks
                    let mmtb = response.maymocthietbi || {};
                    let pk = response.phongkho || {};
                    let ltb = response.loaithietbi || {};
                    let ntb = response.nhomthietbi || {};
                    let tt = response.tinhtrang || {};

                    // Helper function to safely set field value
                    function setFieldValue(fieldId, value) {
                        let element = $('#' + fieldId);
                        if (element.length) {
                            element.val(value || 'N/A');
                        }
                    }

                    // Set all field values using helper function
                    setFieldValue('view_tentb', mmtb.tentb);
                    setFieldValue('view_model', mmtb.model);
                    setFieldValue('view_maso', mmtb.maso);
                    setFieldValue('view_somay', mmtb.somay);
                    setFieldValue('view_loaithietbi', ltb.tenloai);
                    setFieldValue('view_nhomthietbi', ntb.tennhom);
                    setFieldValue('view_donvitinh', mmtb.donvitinh);
                    setFieldValue('view_soluong', mmtb.soluong);
                    setFieldValue('view_gia', mmtb.gia);
                    setFieldValue('view_namsd', mmtb.namsd);
                    setFieldValue('view_nguongoc', mmtb.nguongoc);
                    setFieldValue('view_tinhtrang', mmtb.tinhtrang);
                    setFieldValue('view_chatluong', mmtb.chatluong);
                    setFieldValue('view_phongkhoa', pk.tenphong);
                    setFieldValue('view_matinhtrang', tt.tinhtrang);
                    setFieldValue('view_mota', mmtb.mota);
                    setFieldValue('view_ghichu', mmtb.ghichu);
                    setFieldValue('view_ghichutinhtrang', mmtb.ghichutinhtrang);

                    $('#viewMMTBModal').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error('Ajax error:', error);
                    alert('Có lỗi xảy ra khi tải thông tin thiết bị. Vui lòng thử lại sau.');
                }
            });
        });

        // Xử lý sự kiện submit form
        $('#addForm, #editForm, #deleteForm').on('submit', function() {});

        // Xử lý khi chọn chỉ lọc dữ liệu
        $('#btnFilterOnly').click(function() {
            $('#confirmExportModal').modal('hide');
            $('#filterForm').submit();
        });
        $('#addModal').on('hidden.bs.modal', function() {
            $(this).find('form')[0].reset();
            $(this).find('select').val('').trigger('change');
        });
    });
</script>
<script>
    $(document).ready(function() {
        // Xử lý sự kiện khi nhấn nút thống kê
        $('#btnThongKe').click(function() {
            // Reset form thống kê
            $('#thongKeForm')[0].reset();
        });

        // Xử lý khi chọn đơn vị để lọc phòng/kho trong modal thống kê
        $('#thongke_madonvi').change(function() {
            let madonvi = $(this).val();

            if (madonvi === '') {
                // Nếu chọn "Tất cả đơn vị", hiển thị tất cả phòng/kho
                $('#thongke_maphongkho option').show();
            } else {
                // Ẩn tất cả các phòng/kho
                $('#thongke_maphongkho option:not(:first)').hide();

                // Hiển thị các phòng/kho thuộc đơn vị được chọn
                $('#thongke_maphongkho option[data-donvi="' + madonvi + '"]').show();

                // Nếu phòng/kho đang chọn không thuộc đơn vị mới, reset về "Tất cả"
                if ($('#thongke_maphongkho option:selected').is(':hidden')) {
                    $('#thongke_maphongkho').val('');
                }
            }
        });

        // Xử lý form thống kê
        $('#thongKeForm').submit(function() {
            let maphongkho = $('#thongke_maphongkho').val();
            let matinhtrang = $('#thongke_matinhtrang').val();
            return true;
        });
    });
</script>
@endsection