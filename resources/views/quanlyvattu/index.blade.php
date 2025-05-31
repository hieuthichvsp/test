@extends('layouts.app')
@section('title', 'Quản lý vật tư')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="ibox float-e-margins">
            <div class="ibox-title my-ibox-title">
                <h2 class="h2-title" style="margin-left: 15px;">Quản lý vật tư</h2>
                <div class="pull-right">
                    <div class="form-inline">
                        <button class="btn btn-primary add-btn" data-toggle="modal" data-target='#addModal' type="button" style="margin-left: 10px;">
                            <i class="fa fa-plus"></i> Thêm mới
                        </button>
                        <!-- <button class="btn btn-primary import-btn" data-toggle="modal" data-target='#importModal' type="button" style="margin-right: 15px;">
                            <i class="fa fa-file-excel-o"></i> <span class="hidden-sm hidden-xs"> Thêm danh sách</span>
                        </button> -->
                    </div>
                </div>
            </div>
            <div class="ibox-content">
                <div class="form-group" style="margin: 0 20px 20px 20px;">
                    <label for="hocky">Học kỳ:</label>
                    <select id="hocky_id" class="form-control">
                        @foreach($hockies as $hk)
                        <option value="{{ $hk->id }}" @if($hk==$hockyCurrent) selected @endif>
                            Học kỳ {{ $hk->hocky }} ({{ $hk->tunam }} - {{ $hk->dennam }})
                            @if($hk == $hockyCurrent) - Học kỳ hiện tại @endif
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="margin: 0 20px 20px 20px;">
                    <label for="hocphan">Mã học phần:</label>
                    <select id="hocphan_id" class="form-control">
                        @foreach ($hocphans as $hp)
                        <option value="{{ $hp->id }}">
                            {{ $hp->maHP }} - {{ $hp->tenHP }}
                        </option>
                        @endforeach
                    </select>
                </div>
                @include('quanlyvattu.partials.table')
            </div>
        </div>
    </div>
</div>
@include('quanlyvattu.partials.modals')
@endsection
@section('js')
<script>
    $(document).ready(function() {
        // Khởi tạo DataTables
        let table = $('#dataTables-qlvt').DataTable({
            pageLength: 10,
            autoWidth: false,
            responsive: true,
            ajax: {
                url: "{{ route('quanlyvattu.filter') }}",
                data: function(d) {
                    d.hocky_id = $('#hocky_id').val() || null;
                    d.hocphan_id = $('#hocphan_id').val() || null;
                }
            },
            columns: [{
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: 'ten'
                },
                {
                    data: 'donvitinh.tendonvi'
                },
                {
                    data: 'soluong'
                },
                {
                    data: 'dongia'
                },
                {
                    data: 'khaitoan'
                },
                {
                    data: 'dinhmuc'
                },
                {
                    data: 'mahieu'
                },
                {
                    data: 'nhanhieu'
                },
                {
                    data: 'namsx'
                },
                {
                    data: 'xuatxu'
                },
                {
                    data: 'hangsx'
                },
                {
                    data: 'cauhinh'
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return `<div style="display: flex; justify-content: center; align-items: center; gap:10px;">
                            <button class="btn btn-warning btn-xs edit-btn" data-tooltip="Sửa"
                                data-id="${row.id}">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-xs delete-btn" data-tooltip="Xóa"
                                data-id="${row.id}">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>`;
                    }
                }
            ],
            columnDefs: [{
                orderable: false,
                targets: [0, 13],
                className: 'text-center'
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
                    exportOptions: {
                        columns: ':not(:eq(8))'
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: 'Excel',
                    filename: 'Danh sách vật tư',
                    exportOptions: {
                        columns: ':not(:eq(13))'

                    }
                },
                {
                    extend: 'pdf',
                    title: 'Danh sách vật tư',
                    exportOptions: {
                        columns: ':not(:eq(13))'
                    }
                },
                {
                    extend: 'print',
                    title: 'Danh sách vật tư',
                    exportOptions: {
                        columns: ':not(:eq(13))'
                    },
                    customize: function(win) {
                        $(win.document.body).addClass('white-bg').css('font-size', '10px');
                        $(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
                    }
                }
            ]
        });

        //Xử lý nút sửa
        $('#dataTables-qlvt').on('click', '.edit-btn', function(e) {
            var id = $(this).data('id');
            var rowData = table.rows().data().toArray().find(row => row.id == id);
            if (rowData) {
                // Gán dữ liệu vào các input trong modal
                $('#ten-edit').val(rowData.ten);
                $('#soluong-edit').val(rowData.soluong);
                $('#dvt_id-edit').val(rowData.dvt_id);
                $('#dongia-edit').val(rowData.dongia);
                $('#khaitoan-edit').val(rowData.khaitoan);
                $('#dinhmuc-edit').val(rowData.dinhmuc);
                $('#mahieu-edit').val(rowData.mahieu);
                $('#nhanhieu-edit').val(rowData.nhanhieu);
                $('#namsx-edit').val(rowData.namsx);
                $('#xuatxu-edit').val(rowData.xuatxu);
                $('#hangsx-edit').val(rowData.hangsx);
                $('#cauhinh-edit').val(rowData.cauhinh);
                $('#hocky_id-edit').val(rowData.hocky_id);
                $('#hocphan_id-edit').val(rowData.hocphan_id);
            }
            var updUrl = '{{ route("quanlyvattu.update", ":id") }}'.replace(':id', id);
            $('#editForm').attr('action', updUrl);
            $('#editModal').modal('show');
        });

        //Xử lý nút xóa
        $('#dataTables-qlvt').on('click', '.delete-btn', function() {
            var id = $(this).data('id');
            var url = '{{ route("quanlyvattu.destroy", ":id") }}'.replace(':id', id);
            $('#deleteForm').attr('action', url);
            $('#deleteModal').modal('show');
        });
        $('#hocky_id, #hocphan_id').on('change', function() {
            if (table)
                table.ajax.reload();
        });
    });
</script>
@endsection