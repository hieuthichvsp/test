@extends('layouts.app')
@section('title', 'Cấp phát vật tư')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="ibox float-e-margins">
            <div class="ibox-title my-ibox-title">
                <h2 style="margin-left: 15px;">Cấp phát vật tư</h2>
                <div class="pull-right">
                    <div class="form-inline">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#addModal"
                            type="button" style="margin-right: 15px;">
                            <i class="fa fa-paper-plane"></i> Cấp phát
                        </button>
                    </div>
                </div>
            </div>
            <div class="ibox-content">
                <div class="form-group" style="margin: 0 20px 20px 20px;">
                    <label for="hocky">Học kỳ:</label>
                    <select id="hocky_id" class="form-control">
                        @foreach($hockys as $hk)
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
                @include('capphatvattu.partials.table')
            </div>
        </div>
    </div>
</div>
@include('capphatvattu.partials.modals')
@endsection
@section('js')
<!--Xử lý modal thêm-->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const section = document.querySelector('.thiet-bi-section');
        const btnThem = document.getElementById('themThietBi');
        const btnLamLai = document.getElementById('lamLai');
        const addModal = document.getElementById('addModal');
        const danhSachThietBi = @json($thietbis);

        // Hàm tạo dòng nhập thiết bị
        function taoDongThietBi() {
            let options = '<option value="">-- Chọn thiết bị --</option>';
            danhSachThietBi.forEach(tb => {
                options += `<option value="${tb.id}">${tb.tentb}</option>`;
            });

            const html = `
                <div class="form-group row thiet-bi-item">
                    <div class="col-sm-6">
                        <select name="thiet_bi[]" class="form-control">
                            ${options}
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <input type="number" name="so_luong[]" class="form-control" placeholder="Số lượng" value="0">
                    </div>
                    <div class="col-sm-2">
                        <button type="button" class="btn btn-danger btn-xs remove-thietbi">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>`;
            section.insertAdjacentHTML('beforeend', html);
        }

        // Thêm 3 dòng mặc định khi mở modal
        $('#addModal').on('shown.bs.modal', function() {
            section.querySelectorAll('.thiet-bi-item').forEach(e => e.remove());
            for (let i = 0; i < 3; i++) {
                taoDongThietBi();
            }
        });

        // Xử lý nút "Thêm thiết bị"
        btnThem.addEventListener('click', function() {
            taoDongThietBi();
        });

        // Xử lý khi nhấn X (xóa thiết bị)
        section.addEventListener('click', function(e) {
            if (e.target.closest('.remove-thietbi')) {
                e.target.closest('.thiet-bi-item').remove();
            }
        });

        // Làm lại toàn bộ thiết bị về 3 dòng
        btnLamLai.addEventListener('click', function() {
            section.querySelectorAll('.thiet-bi-item').forEach(e => e.remove());
            for (let i = 0; i < 3; i++) {
                taoDongThietBi();
            }
        });

        // Thêm 3 dòng mặc định khi mở modal
        $('#addModal').on('shown.bs.modal', function() {
            section.querySelectorAll('.thiet-bi-item').forEach(e => e.remove());
            for (let i = 0; i < 3; i++) {
                taoDongThietBi();
            }
        });

        // Xử lý nút "Thêm thiết bị"
        btnThem.addEventListener('click', function() {
            taoDongThietBi();
        });

        // Xử lý khi nhấn X (xóa thiết bị)
        section.addEventListener('click', function(e) {
            if (e.target.closest('.remove-thietbi')) {
                e.target.closest('.thiet-bi-item').remove();
            }
        });

        // Làm lại toàn bộ thiết bị về 3 dòng
        btnLamLai.addEventListener('click', function() {
            section.querySelectorAll('.thiet-bi-item').forEach(e => e.remove());
            for (let i = 0; i < 3; i++) {
                taoDongThietBi();
            }
        });

        $(document).ready(function() {
            $('#maHPSelect').on('change', function() {
                var selectedOption = $(this).find('option:selected');
                var tenHP = selectedOption.data('tenhp') || '';
                $('#tenHPInput').val(tenHP);
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        // Xử lý nút xóa
        $('.delete-btn').on('click', function() {
            var id = $(this).data('id');
            var url = '{{ route("capphatvattu.destroy", ":id") }}'.replace(':id', id);
            $('#deleteForm').attr('action', url);
        });
        // Khởi tạo DataTables
        let table = $('#dataTables-cpvt').DataTable({
            pageLength: 10,
            autoWidth: false,
            responsive: true,
            ajax: {
                url: "{{ route('capphatvattu.filter') }}",
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
                    data: 'hocphan.maHP'
                },
                {
                    data: 'hocphan.tenHP'
                },
                {
                    data: 'maLop'
                },
                {
                    data: 'siSo'
                },
                {
                    data: 'taikhoan.hoten'
                },
                {
                    data: 'file_cap',
                    render: function(data, type, row) {
                        let url = "{{route('capphatvattu.tai-file', ':filename')}}".replace(':filename', data);
                        if (data) {
                            return `<a href="${url}" class="btn btn-xs btn-success">
                                <i class="fa fa-download" style="margin-right: 5px;"></i> Tải file
                            </a>`;
                        } else {
                            return `<span class="text-muted"><i class="fa fa-times-circle" style="margin-right: 5px;"></i> Không có</span>`;
                        }
                    }
                },
                {
                    data: 'file_xacnhan',
                    render: function(data, type, row) {
                        if (data) {
                            let url = "{{route('capphatvattu.tai-file', ':filename')}}".replace(':filename', data);
                            return `<a href="${url}" class="btn btn-info btn-xs">
                                <i class="fa fa-info-circle" style="margin-right: 5px;"></i> Xem chi tiết
                            </a><br>
                            <small class="text-success">
                                <i class="fa fa-check-circle" style="margin-right: 5px;"></i> Đã xác nhận
                            </small>`;
                        } else {
                            let url = "{{route('capphatvattu.upload-xacnhan', ':id')}}".replace(':id', row.id);
                            return `<form action="${url}" method="POST" enctype="multipart/form-data" id="form-upload-${row.id}" style="display: none;">
                                @csrf
                                <input type="file" name="file_xacnhan"
                                       accept=".pdf,.doc,.docx,.xlsx,.xls,.jpg,.jpeg,.png"
                                       onchange="document.getElementById('form-upload-${row.id}').submit();">
                            </form>
                            <button class="btn btn-primary btn-xs" onclick="document.querySelector('#form-upload-${row.id} input[type=file]').click();">
                                <i class="fa fa-upload" style="margin-right: 5px;"></i> Upload xác nhận
                            </button>`;
                        }
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return `<div style="display: flex; justify-content: center; align-items: center;">
                            <button class="btn btn-danger btn-xs delete-btn" data-tooltip="Xóa"
                                data-id="${row.id}" data-toggle="modal" data-target="#deleteModal">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>`;
                    }
                }
            ],
            columnDefs: [{
                orderable: false,
                targets: [0, 8],
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
                    filename: 'Danh sách cấp phát vật tư',
                    exportOptions: {
                        columns: ':not(:eq(8))'

                    }
                },
                {
                    extend: 'pdf',
                    title: 'Danh sách cấp phát vật tư',
                    exportOptions: {
                        columns: ':not(:eq(8))'
                    }
                },
                {
                    extend: 'print',
                    title: 'Danh sách cấp phát vật tư',
                    exportOptions: {
                        columns: ':not(:eq(8))'
                    },
                    customize: function(win) {
                        $(win.document.body).addClass('white-bg').css('font-size', '10px');
                        $(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
                    }
                }
            ]
        });
        $('#hocky_id, #hocphan_id').on('change', function() {
            let hocky = $('#hocky').val();
            let hocphan = $('#hocphan').val();
            if (table)
                table.ajax.reload();
        });
    });
</script>
@endsection