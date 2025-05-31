<div class="table-responsive">
    <table id="dataTables-btsc" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên thiết bị</th>
                <th>Ngày bảo trì</th>
                <th>Mô tả hư hỏng</th>
                <th>Nội dung bảo trì</th>
                <th>Người bảo trì</th>
                <th>Người kiểm tra</th>
                <th>Ghi chú</th>
                <th>Ngày tạo</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
@section('js-btsc')
<script>
    $(document).ready(function() {
        //Autocomplete phòng máy
        sessionStorage.setItem("idhocky1", $('#hockySearch1').val());
        initRoomAutocomplete("#phongSearch1", {
            url: "{{ route('nhatkyphongmay.search-phong') }}",
            select: function(event, ui) {
                $("#phongSearch1").val(ui.item.label);
                sessionStorage.setItem("idphong1", ui.item.id);
                sessionStorage.setItem('tenphong1', ui.item.label);
                if (table) {
                    table.ajax.reload();
                }
                return true;
            },
            change: function(event, ui) {
                if (!ui.item) {
                    $("#phongSearch1").val("");
                }
            }
        });

        function restoreState() {
            $('#hockySearch1').val(sessionStorage.getItem('idhocky1'));
            $("#phongSearch1").val(sessionStorage.getItem('tenphong1'));
        }
        let table = $('#dataTables-btsc').DataTable({
            responsive: false,
            autoWidth: false,
            ajax: {
                url: "{{ route('nhatkyphongmay.baotrisuachua.filter') }}",
                data: function(d) {
                    d.mahocky = $('#hockySearch1').val() || null;
                    d.maphong = sessionStorage.getItem("idphong1") || null;
                }
            },
            columns: [{
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: 'tentb',
                    name: 'matb',
                },
                {
                    data: 'ngaybaotri',
                    name: 'ngaybaotri'
                },
                {
                    data: 'motahuhong',
                    name: 'motahuhong'
                },
                {
                    data: 'noidungbaotri',
                    name: 'noidungbaotri'
                },
                {
                    data: 'nguoibaotri_hoten',
                    name: 'nguoibaotri_hoten',
                },
                {
                    data: 'nguoikiemtra_hoten',
                    name: 'nguoikiemtra_hoten',
                },
                {
                    data: 'ghichu',
                    name: 'ghichu'
                },
                {
                    data: 'ngaytao',
                    name: 'ngaytao'
                },
                {
                    data: null,
                    name: null,
                    render: function(data, type, row) {
                        return `<div class="d-flex" style="gap: 5px;">
                            <a href="#" class="btn btn-warning btn-xs edit-btn"
                                data-tooltip="Cập nhật"
                                data-id="${row.id}">
                                <i class="fa fa-pencil"></i>
                            </a>
                            <a href="#" class="btn btn-danger btn-xs delete-btn"
                                data-tooltip="Xóa"
                                data-id="${row.id}">
                                <i class="fa fa-trash"></i>
                            </a>
                        </div>`;
                    },
                }
            ],
            columnDefs: [{
                targets: [0, 9],
                orderable: false,
                searchable: false,
                className: 'text-center'
            }],
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
        $('#hockySearch1').on('change', function() {
            if (table) {
                table.ajax.reload();
            }
        });
        $('#phongSearch1').on('change', function() {
            if (table) {
                table.ajax.reload();
            }
        });

        //Modal update
        $('#dataTables-btsc').on('click', '.edit-btn', function(e) {
            let id = $(this).data('id');
            $.ajax({
                url: "{{ route('nhatkyphongmay.baotrisuachua.edit', ':id') }}".replace(':id', id),
                type: 'GET',
                success: function(data) {
                    $('#editBTSCModal #ngaybaotri-edit').val(data.baotri.ngaybaotri);
                    $('#editBTSCModal #motahuhong-edit').val(data.baotri.motahuhong);
                    $('#editBTSCModal #noidungbaotri-edit').val(data.baotri.noidungbaotri);
                    $('#editBTSCModal #nguoibaotri-edit').val(data.baotri.nguoibaotri);
                    $('#editBTSCModal #nguoikiemtra-edit').val(data.baotri.nguoikiemtra);
                    $('#editBTSCModal #ghichu-edit').val(data.baotri.ghichu);
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching data:", error);
                }
            });
            $('#editBTSCModal #editForm').attr('action', '{{ route("nhatkyphongmay.baotrisuachua.update", ":id") }}'.replace(':id', id));
            $('#editBTSCModal').modal('show');
        });
        //Modal delete
        $('#dataTables-btsc').on('click', '.delete-btn', function(e) {
            let id = $(this).data('id');
            $('#deleteBTSCModal #deleteForm').attr('action', '{{ route("nhatkyphongmay.baotrisuachua.destroy", ":id") }}'.replace(':id', id));
            $('#deleteBTSCModal').modal('show');
        });
        $('#phong_id-add').on('change', function() {
            let phong_id = $(this).val();
            $.ajax({
                url: "{{ route('nhatkyphongmay.baotrisuachua.getListDevices', ':phong_id') }}".replace(':phong_id', phong_id),
                type: 'GET',
                success: function(data) {
                    $('#dsThietBi').empty();
                    if (data.listDevices.length === 0) {
                        $('#dsThietBi').append('<option disabled>Không có thiết bị nào</option>');
                        return;
                    }
                    data.listDevices.forEach(function(device) {
                        $('#dsThietBi').append(
                            `<option value="${device.id}">Mã TB: ${device.maso} - Tên TB: ${device.tentb} - Mô tả: ${device.mota}</option>`
                        );
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching data:", error);
                }
            });
        });
        restoreState();
    });
</script>
@endsection