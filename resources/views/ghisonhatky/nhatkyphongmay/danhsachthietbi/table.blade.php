<div class="table-responsive">
    <table id="dataTables-dstb" class="table table-striped table-bordered table-hover" width="100%">
        <thead>
            <tr>
                <th class="text-center">STT</th>
                <th>Mã số</th>
                <th>Tên thiết bị</th>
                <th>Số máy</th>
                <th>Mô tả</th>
                <th>Số lượng</th>
                <th>Năm sử dụng</th>
                <th>Nguồn gốc</th>
                <th>Đơn vị tính</th>
                <th>Giá</th>
                <th>Phòng</th>
                <th>Chất lượng</th>
                @canany('hasRole_A_M_L')
                <th>Thao tác</th>
                @endcan
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
@section('js-dstb')
<script>
    $(document).ready(function() {
        $.fn.modal.Constructor.prototype.enforceFocus = function() {};
        $('.select2').select2({
            dropdownCssClass: 'select2-dropdown-modal',
            width: '100%',
            alowClear: true,
            tag: true,
        });
    });
</script>
<script>
    // Khởi tạo DataTable cho danh sách thiết bị
    $(document).ready(function() {
        const hasPermission = "{{auth()->user()->can('hasRole_A_M_L')}}";
        var table = $('#dataTables-dstb').DataTable({
            responsive: true,
            serverSide: true,
            // processing: true, // Đảm bảo rằng việc xử lý có hiển thị thông báo
            ajax: {
                url: "{{ route('nhatkyphongmay.danhsachthietbi.get_data') }}", // URL filter
                data: function(d) {
                    d._token = "{{ csrf_token() }}"; // Thêm token CSRF
                    // Thêm các param filter vào request
                    d.loaitb_id = $('#loaitb-filter').val();
                    d.tinhtrang_id = $('#tinhtrang-filter').val();
                    d.namsd = $('#namsd-filter').val();
                    d.nguongoc = $('#nguongoc-filter').val();
                    d.gia = $('#gia-filter').val();
                    d.chatluong = $('#chatluong-filter').val();
                    d.phong_id = $('#phong-filter').val();
                },
                type: 'GET',
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'Mã số',
                    name: 'maso'
                },
                {
                    data: 'Tên thiết bị',
                    name: 'tentb'
                },
                {
                    data: 'Số máy',
                    name: 'somay'
                },
                {
                    data: 'Mô tả',
                    name: 'mota'
                },
                {
                    data: 'Số lượng',
                    name: 'soluong'
                },
                {
                    data: 'Năm sử dụng',
                    name: 'namsd'
                },
                {
                    data: 'Nguồn gốc',
                    name: 'nguongoc'
                },
                {
                    data: 'Đơn vị tính',
                    name: 'donvitinh'
                },
                {
                    data: 'Giá',
                    name: 'gia'
                },
                {
                    data: 'Phòng',
                    name: 'phong_kho.tenphong'
                },
                {
                    data: 'Chất lượng',
                    name: 'chatluong'
                },
                {
                    data: 'Thao tác',
                    name: 'Thao tác',
                    visible: hasPermission,
                    className: 'text-center',
                    orderable: false,
                    searchable: false
                },

            ],
            order: [],
            buttons: [{
                    extend: 'copyHtml5'
                },
                {
                    extend: 'excel'
                },
                {
                    extend: 'print'
                }
            ],
            dom: "<'row mb-3'" +
                "<'col-md-4'l>" +
                "<'col-md-4 text-center'B>" +
                "<'col-md-4 d-flex justify-content-end'f>" +
                ">" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        });

        // Mở modal lọc
        $('.btn-filter').click(function() {
            $('#filter-modal').modal('show');
        });
        // Submit form lọc
        $('#btnFilter').click(function(e) {
            e.preventDefault();
            const formData = $('#filter-form').serialize();
            // Reload DataTable với tham số lọc
            table.ajax.url("{{ route('nhatkyphongmay.danhsachthietbi.filter') }}?" + formData).load();
            // Đóng modal
            $('#filter-modal').modal('hide');
            $('.btn-filter')
                .removeClass('btn-primary')
                .addClass('btn-warning')
                .html('<i class="fa fa-filter"></i> Đã lọc dữ liệu');
        });
        // Reset form lọc
        $('.btn-reset').on('click', function() {
            $('#filter-form .select2').val('').trigger('change');
            table.ajax.url("{{ route('nhatkyphongmay.danhsachthietbi.filter') }}").load();
            $('.btn-filter')
                .removeClass('btn-warning')
                .addClass('btn-primary')
                .html('<i class="fa fa-filter"></i> Lọc dữ liệu');
        });
        // Xử lý sự kiện khi nhấn nút sửa
        $('#dataTables-dstb').on('click', '.edit-btn', function(e) {
            e.preventDefault();
            let id = $(this).data('id');

            $.ajax({
                url: '{{ route("nhatkyphongmay.danhsachthietbi.edit", ":id") }}'.replace(':id', id),
                type: 'GET',
                success: function(response) {
                    // Điền dữ liệu
                    Object.keys(response.thietbi).forEach(key => {
                        const $field = $(`#${key}-edit`);
                        if ($field.length) {
                            $field.val(response.thietbi[key]);
                        }
                    });
                    $('#edit-form').attr('action', '{{ route("nhatkyphongmay.danhsachthietbi.update", ":id") }}'.replace(':id', id));
                    $('#editModal').modal('show');
                },
                error: function(xhr) {
                    let errorMsg = xhr.responseJSON?.error || xhr.statusText;
                    alert('Lỗi: ' + errorMsg);
                }
            });
        });
    });
</script>
@endsection