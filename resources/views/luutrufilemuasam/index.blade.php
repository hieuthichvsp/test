@extends('layouts.app')
@section('title', 'Quản lý file mua sắm')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12" style="padding-left: 0;">
            <div class="ibox float-e-margins">
                <div class="ibox-title my-ibox-title">
                    <h2>Danh sách file mua sắm</h2>
                    <div>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#addFileModal">
                            <i class="fa fa-plus"></i> Thêm file mua sắm
                        </button>
                        <button class="btn btn-warning" data-toggle="modal" data-target="#downloadFileModal">
                            <i class="fa fa-download"></i> Tải dữ liệu
                        </button>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-file">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên file</th>
                                    <th>Mô tả</th>
                                    <th>Danh mục mua sắm</th>
                                    <th>Đề nghị</th>
                                    <th>Ngày tạo</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($files as $file)
                                <tr class="gradeX">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $file->tenfile }}</td>
                                    <td>{{ $file->mota }}</td>
                                    <td>{{ $file->danhmucmuasam ? $file->danhmucmuasam->ten_danhmuc : 'Không có' }}</td>
                                    <td>{{ $file->denghi ? $file->denghi->ten_de_nghi : 'Không có' }}</td>
                                    <td>{{ $file->created_at }}</td>
                                    <td class="text-center" style="display: flex; justify-content: center; align-items: center; gap: 20px;">

                                        <a href="{{ route('dsfilemuasam.view', $file->id) }}"
                                            class="btn btn-info btn-sm"
                                            data-tooltip="Xem file"
                                            target="_blank">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="{{ route('dsfilemuasam.download', $file->id) }}"
                                            class="btn btn-primary btn-sm"
                                            data-tooltip="Tải xuống">
                                            <i class="fa fa-download"></i>
                                        </a>

                                        <button class="btn btn-warning btn-sm edit-btn"
                                            data-tooltip="Cập nhật"
                                            data-id="{{ $file->id }}"
                                            data-toggle="modal"
                                            data-target="#editFileModal">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm delete-btn"
                                            data-tooltip="Xóa"
                                            data-id="{{ $file->id }}"
                                            data-toggle="modal"
                                            data-target="#deleteFileModal">
                                            <i class="fa fa-trash"></i>
                                        </button>
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
</div>
@include('luutrufilemuasam.partials.modals')
@endsection

@section('js')
<script>
    $(document).ready(function() {
        // Handle edit button
        $('.edit-btn').click(function() {
            var id = $(this).data('id');
            $.ajax({
                url: '{{ route("dsfilemuasam.edit", ":id") }}'.replace(':id', id),
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.file) {
                        $('#edit_tenfile').val(response.file.tenfile);
                        $('#edit_mota').val(response.file.mota);
                        $('#edit_danhmucmuasam_id').val(response.file.id_danhmucmuasam);
                        $('#edit_denghi_id').val(response.file.id_denghi);
                        $('#edit_file').val(response.file.tenfile + ".pdf");
                        $('#editForm').attr('action', '{{ route("dsfilemuasam.update", ":id") }}'.replace(':id', id));
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: 'Không tìm thấy thông tin file'
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Không thể tải thông tin file. Vui lòng thử lại sau.'
                    });
                }
            });
        });

        // Handle delete button
        $('.delete-btn').click(function() {
            var id = $(this).data('id');
            $('#deleteForm').attr('action', '{{ route("dsfilemuasam.destroy", ":id") }}'.replace(':id', id));
        });

        // Xử lý form tải file
        $('#downloadForm').on('submit', function(e) {
            var danhmuc = $('#danhmuc_id').val();
            var hocky = $('#hocky_id').val();

            if (!danhmuc && !hocky) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Cảnh báo',
                    text: 'Vui lòng chọn ít nhất một điều kiện để tải xuống (Danh mục hoặc Học kỳ)',
                    confirmButtonText: 'Đồng ý'
                });
                return false;
            }

            // Hiển thị loading khi đang tải
            Swal.fire({
                title: 'Đang tải file...',
                html: 'Vui lòng đợi trong giây lát',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        });


        // Initialize DataTable
        $('.dataTables-file').DataTable({
            pageLength: 10,
            responsive: true,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            order: [],
            columnDefs: [{
                orderable: false,
                targets: [0, 5],
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
                "<'col-md-4 text-center'B>" +
                "<'col-md-4 d-flex justify-content-end'f>" +
                ">" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            buttons: [{
                    extend: 'copy'
                },
                {
                    extend: 'csv',
                    text: 'CSV',
                    charset: 'utf-8',
                    bom: true
                },
                {
                    extend: 'excelHtml5',
                    text: 'Excel',
                    filename: 'Danh sách file mua sắm',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    },
                    customize: function(xlsx) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        var styles = xlsx.xl['styles.xml'];

                        $('row', sheet).each(function() {
                            var r = parseInt($(this).attr('r'));
                            $(this).attr('r', r + 1);
                            $(this).find('c').each(function() {
                                var cellRef = $(this).attr('r');
                                var col = cellRef.replace(/[0-9]/g, '');
                                var row = parseInt(cellRef.replace(/[A-Z]/g, '')) + 1;
                                $(this).attr('r', col + row);
                            });
                        });

                        var title = `
                        <row r="1">
                            <c t="inlineStr" r="A1" s="50">
                                <is><t>Danh sách file mua sắm</t></is>
                            </c>
                        </row>
                    `;
                        sheet.getElementsByTagName('sheetData')[0].innerHTML = title + sheet.getElementsByTagName('sheetData')[0].innerHTML;

                        var mergeCells = sheet.getElementsByTagName('mergeCells')[0];
                        if (!mergeCells) {
                            mergeCells = sheet.createElement('mergeCells');
                            sheet.getElementsByTagName('worksheet')[0].appendChild(mergeCells);
                        }

                        var mergeCell = sheet.createElement('mergeCell');
                        mergeCell.setAttribute('ref', 'A1:E1');
                        mergeCells.appendChild(mergeCell);
                        mergeCells.setAttribute('count', mergeCells.getElementsByTagName('mergeCell').length);

                        var cellXfs = styles.getElementsByTagName('cellXfs')[0];
                        cellXfs.innerHTML += `
                        <xf xfId="0" applyAlignment="1" applyFont="1">
                            <alignment horizontal="center" vertical="center"/>
                            <font><sz val="22"/><b/></font>
                        </xf>
                        <xf xfId="0" applyAlignment="1" applyFont="1">
                            <alignment horizontal="center"/>
                            <font><sz val="16"/><b/></font>
                        </xf>
                    `;

                        $('row[r="2"] c', sheet).attr('s', '51');
                        $('row[r="1"] c[r="A1"]', sheet).attr('s', '50');
                    }
                },
                {
                    extend: 'pdf',
                    title: 'Danh sách file mua sắm',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'print',
                    title: 'Danh sách file mua sắm',
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
    });
</script>
@endsection