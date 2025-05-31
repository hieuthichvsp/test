@extends('layouts.app')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h2 style=" margin-top:0;margin-bottom:20px;" class="h2-title">Sổ quản lý kho</h2>
                <div style="margin-bottom: 20px;">
                    <label for="hockySearch2">HỌC KỲ</label>
                    <select id="hockySearch2" name="idhocky1" class="form-control">
                        @foreach($hockys as $hk)
                        <option value="{{ $hk->id }}" @if($hk==$hockysCurrent) selected @endif>
                            Học kỳ {{ $hk->hocky }} ({{ $hk->tunam }} - {{ $hk->dennam }})
                            @if($hk == $hockysCurrent) - Học kỳ hiện tại @endif
                        </option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-bottom: 20px;">
                    <div class="form-group autocomplete">
                        <label for="phongSearch2">PHÒNG MÁY</label>
                        <input id="phongSearch2" required type="text" class="form-control" name="phongSearch2" placeholder="Nhập tên phòng (VD: A201)">
                    </div>
                </div>
                @can('hasRole_A_M_L')
                <div class="row" style="margin-top: 15px;">
                    <div class="col-xs-12 col-sm-3" style="margin-bottom: 5px;">
                        <a class="btn btn-primary btn-block" data-toggle="modal" data-target="#addSQLKModal">
                            <i class="fa fa-calendar-plus-o"></i><span class="hidden-sm"> Thêm lịch bảo trì</span>
                        </a>
                    </div>
                    <div class="col-xs-12 col-sm-3" style="margin-bottom: 5px;">
                        <a class="btn btn-primary btn-block" id="btn-printlichbaotri">
                            <i class="fa fa-print"></i><span class="hidden-sm"> In sổ bảo trì</span>
                        </a>
                    </div>
                </div>
                @endcan
            </div>
            <div class="ibox-content">
                <!-- Bảng nhật ký -->
                @include('ghisonhatky.quanlysokho.partials.table')
            </div>
        </div>
    </div>
</div>
@include('ghisonhatky.quanlysokho.partials.modals')
@endsection
@section('js')
<script src="{{ asset('js/component/autocomplete-phong.js') }}"></script>
<script>
    $(document).ready(function() {
        sessionStorage.setItem("idhocky2", $('#hockySearch2').val());
        initRoomAutocomplete("#phongSearch2", {
            url: "{{ route('nhatkyphongmay.search-phong') }}",
            select: function(event, ui) {
                $("#phongSearch2").val(ui.item.label);
                sessionStorage.setItem("idphong2", ui.item.id);
                sessionStorage.setItem('tenphong2', ui.item.label);
                if (table) {
                    table.ajax.reload();
                }
                return true;
            },
            change: function(event, ui) {
                if (!ui.item) {
                    $("#phongSearch2").val("");
                }
            }
        });

        function restoreState() {
            $('#hockySearch2').val(sessionStorage.getItem('idhocky2'));
            $("#phongSearch2").val(sessionStorage.getItem('tenphong2'));
        }
        const hasPermission = "{{auth()->user()->can('hasRole_A_M_L')}}";
        let table = $('#dataTables-sqlk').DataTable({
            responsive: false,
            autoWidth: false,
            ajax: {
                url: "{{ route('soquanlykho.filter') }}",
                data: function(d) {
                    d.hocky_id = $('#hockySearch2').val() || null;
                    d.phong_id = sessionStorage.getItem("idphong2") || null;
                },
            },
            columns: [{
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: 'tentb',
                },
                {
                    data: 'ngaymuon',
                },
                {
                    data: 'ngaytra',
                },
                {
                    data: 'mucdichsd',
                },
                {
                    data: 'tinhtrangtruocsd',
                },
                {
                    data: 'tinhtrangsausd',
                },
                {
                    data: 'giaoviensd',
                },
                {
                    data: null,
                    visible: hasPermission,
                    render: function(data, type, row) {
                        return `
                        <div class="btn-action">
                            <a href="#" class="btn btn-warning btn-xs edit-btn"
                                data-tooltip="Cập nhật"
                                data-id="${row.id}">
                                <i class="fa fa-edit"></i>
                            </a>
                            @can('isAdmin')
                            <a href="#" class="btn btn-danger btn-xs delete-btn"
                                data-tooltip="Xóa"
                                data-id="${row.id}">
                                <i class="fa fa-trash"></i>
                            </a>
                            @endcan
                        </div>`;
                    },
                }
            ],
            columnDefs: [{
                targets: [0, 8],
                orderable: false,
                searchable: false,
                className: 'text-center'
            }],
            order: [],
            buttons: [{
                    extend: 'copyHtml5'
                },
                {
                    extend: 'pdf',
                    title: "Danh sách sổ kho",
                    filename: "danh_sach_so_kho",
                    exportOptions: {
                        columns: ':visible:not(:last-child)'
                    }
                },
                {
                    extend: 'excel',
                    title: "Danh sách sổ kho",
                    filename: "danh_sach_so_kho",
                    exportOptions: {
                        columns: ':visible:not(:last-child)'
                    }
                },
                {
                    extend: 'print',
                    title: "Danh sách sổ kho",
                    exportOptions: {
                        columns: ':visible:not(:last-child)'
                    }
                }
            ],
            dom: "<'row mb-3'" +
                "<'col-md-4'l>" +
                "<'col-md-4'B>" +
                "<'col-md-4'f>" +
                ">" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        });
        $('#hockySearch2').on('change', function() {
            if (table) {
                table.ajax.reload();
            }
        });
        $('#phongSearch2').on('change', function() {
            if (table) {
                table.ajax.reload();
            }
        });

        function dmyToYmd(dmy) {
            if (!dmy) return '';
            const [d, m, y] = dmy.split('/');
            return `${y}-${m.padStart(2, '0')}-${d.padStart(2, '0')}`;
        }
        //Modal update
        $('#dataTables-sqlk').on('click', '.edit-btn', function(e) {
            e.preventDefault();
            let $row = $(this).closest('tr');
            let rowData = table.row($row).data();
            console.log('Dữ liệu dòng hiện tại:', rowData);
            $('#ngaymuon-edit').val(dmyToYmd(rowData.ngaymuon));
            $('#ngaytra-edit').val(dmyToYmd(rowData.ngaytra));
            $('#editSQLKModal #mucdichsd-edit').val(rowData.mucdichsd);
            $('#editSQLKModal #tinhtrangtruoc-edit').val(rowData.tinhtrangtruocsd);
            $('#editSQLKModal #tinhtrangsau-edit').val(rowData.tinhtrangsausd);
            $('#editSQLKModal #tentb-edit').val(rowData.tentb);
            $('#editSQLKModal #editForm').attr(
                'action',
                '{{ route("soquanlykho.update", ":id") }}'.replace(':id', rowData.id)
            );

            // Hiển thị modal
            $('#editSQLKModal').modal('show');
        });


        //Modal delete
        $('#dataTables-sqlk').on('click', '.delete-btn', function(e) {
            let id = $(this).data('id');
            $('#deleteSQLKModal #deleteForm').attr('action', '{{ route("soquanlykho.destroy", ":id") }}'.replace(':id', id));
            $('#deleteSQLKModal').modal('show');
        });
        $('#maphong-add').on('change', function() {
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
                            `<option value="${device.id}">${device.maso} - ${device.tentb}</option>`
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