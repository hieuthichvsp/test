@extends('layouts.app')
@section('title', 'Quản lý quy trình lưu trữ')
@section('content')
@if ($trangthai==0)
<div class="wrapper wrapper-content animated fadeInRight" id="form1">
    <div class="row">
        <div class="ibox float-e-margins">
            <div class="ibox-title my-ibox-title">
                <h2 class='h2-title'>Danh sách file quy trình</h2>
                @can('hasRole_A_M_L')
                <div class="btn-action">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#addModal">
                        <i class="fa fa-plus" title="Thêm quy trình"></i><span class="hidden-xs"> Thêm quy trình</span>
                    </button>
                    <a class="btn btn-info" data-toggle="modal" data-target="#downloadFilterModal">
                        <i class="fa fa-download" title="Tải file"></i><span class="hidden-xs"> Tải file</span>
                    </a>
                </div>
                @endcan
            </div>
            <div class="ibox-content">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover dataTables-quytrinh">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên thông tin</th>
                                <th>Mô tả</th>
                                <th>Danh mục</th>
                                <th>Đề nghị</th>
                                <th>Học kỳ</th>
                                <th>Upload</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($thongtinluutrus as $thongtinluutru)
                            <tr class="gradeX">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $thongtinluutru->tenthongtin }}</td>
                                <td>{{ $thongtinluutru->mota }}</td>
                                <td>{{ $thongtinluutru->danhmuc->ten_danhmuc }}</td>
                                <td>{{ $thongtinluutru->denghi->ten_de_nghi }}</td>
                                <td>{{ $thongtinluutru->hocky->hocky }}</td>
                                @php
                                $count = 0;
                                if (isset($thongtinluutru->fileluutru) && is_countable($thongtinluutru->fileluutru)) {
                                foreach ($thongtinluutru->fileluutru as $file) {
                                if ($file->tenfile != 'Trống') {
                                $count++;
                                }
                                }
                                }
                                @endphp

                                <td>{{ $count }}</td>
                                @if($count == 12)
                                <td><span class="label label-success">Hoàn thành</span></td>
                                @elseif(count($thongtinluutru->fileluutru) > 0)
                                <td><span class="label label-success">Đã tạo</span></td>
                                @else
                                <td><span class="label label-warning">Chưa tạo</span></td>
                                @endif

                                <td class="text-center" style="display: flex; justify-content: center; align-items: center; gap: 20px;">
                                    <a href="{{ route('quanlydanhmuc.show', $thongtinluutru->id) }}"
                                        class="btn btn-info btn-xs"
                                        data-tooltip="Xem file">
                                        <i class="fa fa-file-text"></i>
                                    </a>
                                    @canany('hasRole_A_M_L')
                                    <button class="btn btn-warning btn-xs edit-btn"
                                        data-tooltip="Cập nhật"
                                        data-id="{{ $thongtinluutru->id }}"
                                        data-toggle="modal"
                                        data-target="#editModal">
                                        <i class="fa fa-edit"></i>
                                    </button>

                                    <a href="{{ route('quanlydanhmuc.download', $thongtinluutru->id) }}"
                                        class="btn btn-primary btn-xs"
                                        data-tooltip="Tải xuống"
                                        data-toggle="tooltip"
                                        title="Tải xuống file">
                                        <i class="fa fa-download"></i>
                                    </a>
                                    @can('isAdmin')
                                    <button class="btn btn-danger btn-xs delete-btn"
                                        data-tooltip="Xóa"
                                        data-id="{{ $thongtinluutru->id }}"
                                        data-toggle="modal"
                                        data-target="#deleteModal">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    @endcan
                                    @endcan
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
@else
<div class="wrapper wrapper-content animated fadeInRight" id="form2">
    <div class="row justify-content-center">
        <div class="row">
            <div class="col-md-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title my-ibox-title">
                        <h2><i class="fa fa-info-circle"></i> Thông tin quy trình</h2>
                    </div>
                    <div class="ibox-content">
                        <div class="form-group">
                            <label>Tên thông tin:</label>
                            <p>{{ $tenmuasam }}</p>
                        </div>
                        <div class="form-group">
                            <label>Mô tả:</label>
                            <p>{{ $tenmota }}</p>
                        </div>
                        <div class="form-group">
                            <label>Danh mục:</label>
                            <p>{{ $tendanhmuc }}</p>
                        </div>
                        <div class="form-group">
                            <label>Đề nghị:</label>
                            <p>{{ $tendenghi }}</p>
                        </div>
                        <div class="form-group">
                            <label>Học kỳ:</label>
                            <p>{{ $tenhocky }}</p>
                        </div>
                        <div class="form-group">
                            <label>Tổng file:</label>
                            <p>{{ count($fileluutrus) }} file</p>
                        </div>

                        @php
                        $count = 0;
                        if (isset($fileluutrus)) {
                        foreach ($fileluutrus as $file) {
                        if ($file->tenfile != 'Trống') {
                        $count++;
                        }
                        }
                        }
                        @endphp
                        <div class="form-group">
                            <label>Đã upload:</label>
                            <p>{{ $count }} file</p>
                        </div>
                        <div class="form-group">
                            <label>Chưa upload:</label>
                            <p>{{ count($fileluutrus)-$count }} file</p>
                        </div>
                        <div class="form-group">
                            <label>Trạng thái:</label>
                            @if($count == count($fileluutrus) )
                            <p><span class="label label-success">Đã hoàn tất upload file</span></p>
                            @else
                            <p><span class="label label-warning">Chưa hoàn tất upload file</span></p>
                            @endif
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="ibox float-e-margins">
                    <div class="ibox-title my-ibox-title d-flex justify-content-between align-items-center">
                        <h2><i class="fa fa-file-pdf-o mr-2"></i> Danh sách file quy trình</h2>
                        <div>
                            <a href="{{ route('quanlydanhmuc.download', $id ) }}"
                                class="btn btn-primary"
                                data-tooltip="Tải xuống"
                                data-toggle="tooltip"
                                title="Tải xuống file">
                                <i class="fa fa-download"></i> Tải xuống tất cả
                            </a>
                            <a href="{{ route('quanlydanhmuc.reset') }}" class="btn btn-success">
                                <i class="fa fa-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="row justify-content-center">
                            @if ($trangthai > 0)
                            @foreach ($fileluutrus as $file)
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-4">
                                <div class="card file-card text-center shadow-sm border-0">
                                    <div class="card-body d-flex flex-column align-items-center justify-content-center p-2">
                                        <h5 class="file-name text-center mb-2">{{ $file->loaifile->tenloai }}</h5>
                                        <div class="file-icon-wrapper">
                                            <i class="fa fa-file-pdf-o"></i>
                                        </div>
                                        @if($file->tenfile != 'Trống')
                                        <div class="status status-downloaded" id="readfile" onclick="readfile('{{ $file->id }}')">Đọc file</div>
                                        @else
                                        <div class="status status-not-downloaded">Chưa tải</div>
                                        @endif
                                        <div class="file-actions">
                                            <a href="#" class="btn-action btn-detail"
                                                data-id="{{ $file->id }}"
                                                data-tooltip="Xem thông tin file"
                                                data-toggle="modal" data-target="#showModal">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('quanlydanhmuc.downloadfile', ['id' => $file->id]) }}"
                                                data-tooltip="Download file"
                                                class="btn-action btn-download"
                                                value="{{ $file->id }}">
                                                <i class="fa fa-download"></i>
                                            </a>
                                            <a href="#" class="btn-action btn-upload"
                                                value="{{ $file->id }}"
                                                data-toggle="modal"
                                                data-tooltip="Upload file"
                                                data-target="#uploadModal">
                                                <i class="fa fa-upload"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @else
                            <div class="col-12 text-center py-5">
                                <div class="empty-state">
                                    <i class="fa fa-folder-open-o fa-4x text-muted mb-3"></i>
                                    <h4 class="text-muted">Không có file quy trình nào</h4>
                                    <p class="text-muted">Vui lòng tạo quy trình mới để bắt đầu.</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<style>
    .file-card {
        background: #fff;
        width: 230px;
        height: 180px;
        border-radius: 18px;
        box-shadow: 0 4px 16px rgba(44, 62, 80, 0.13);
        transition: box-shadow 0.2s, transform 0.2s;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        justify-content: center;
        margin: 5px;
    }

    .file-card:hover {
        box-shadow: 0 8px 32px rgba(26, 179, 148, 0.18);
        transform: translateY(-4px) scale(1.03);
        border-color: rgb(225, 233, 232);
    }

    .file-icon-wrapper {
        width: 90px;
        height: 90px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
        border-radius: 16px;
        margin: 0 auto 10px auto;
        box-shadow: 0 2px 8px rgba(231, 76, 60, 0.08);
    }

    .file-icon-wrapper i {
        font-size: 4.8rem;
        color: #c97b3c;
        margin: 0;
    }

    .status {
        font-weight: 500;
        font-size: 10px;
        padding: 4px 22px;
        width: 85px;
        height:
            20px;
        border-radius:
            10px;
        display: inline-block;
        margin-bottom: 6px;
        box-shadow: 0 2px 8px rgba(44, 62, 80, 0.07);
        cursor: pointer;

    }

    .status-downloaded {
        color: #fff;
        background: #19b14a;
    }

    .status-not-downloaded {
        color: #fff;
        background: #f00;
    }

    .btn-detail {
        color: #19b14a;
        font-weight: 500;
        font-size: 15px;
        text-decoration: none;
        transition: color 0.2s;
    }

    .btn-detail:hover {
        color: #117a37;
        text-decoration: underline;
    }

    .btn-download {
        color: #f00;
        font-weight: 500;
        font-size: 15px;
        text-decoration: none;
        transition: color 0.2s;
    }

    .btn-download:hover {
        color: #b30000;
        text-decoration: underline;
    }

    /* CSS cho hiệu ứng hiển thị công cụ khi hover */
    .file-actions {
        display: flex;
        gap: 10px;
        justify-content: center;
        align-items: center;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease, transform 0.3s ease;
        transform: translateY(-5px);
    }

    .file-card:hover .file-actions {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .card-body {
        align-items: center !important;
        justify-content: center !important;
        display: flex;
        flex-direction: column;
        height: 100%;
        padding: 18px 0 0 0 !important;
    }

    @media (max-width: 1199.98px) {
        .col-xl-2 {
            flex: 0 0 20%;
            max-width: 20%;
        }
    }

    @media (max-width: 991.98px) {

        .col-lg-3,
        .col-xl-2 {
            flex: 0 0 33.3333%;
            max-width: 33.3333%;
        }
    }

    @media (max-width: 767.98px) {

        .col-md-4,
        .col-lg-3,
        .col-xl-2 {
            flex: 0 0 50%;
            max-width: 50%;
        }
    }

    @media (max-width: 575.98px) {

        .col-sm-6,
        .col-md-4,
        .col-lg-3,
        .col-xl-2 {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }
</style>
@include('Quanlyfilequytrinh.partials.modals')
@endsection
@section('js')
<script>
    function readfile(id) {
        window.location.href = "{{ route('quanlydanhmuc.readfile', ':id') }}".replace(':id', id);
    }

    $(document).ready(function() {
        $('.btn-detail').click(function() {
            var id = $(this).data('id');
            console.log('ID file chi tiết:', id); // Log để kiểm tra ID

            // Gọi AJAX để lấy thông tin chi tiết file
            $.ajax({
                url: '{{ route("quanlydanhmuc.getFileDetail", ":id") }}'.replace(':id', id),
                type: 'GET',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('Response chi tiết:', response); // Log để kiểm tra response
                    if (response && response.success) {
                        // Hiển thị thông tin file trong modal
                        $('#show_muasam').text(response.tenthongtin || 'Không có thông tin');
                        $('#show_danhmuc').text(response.danhmuc || 'Không có thông tin');
                        $('#show_denghi').text(response.denghi || 'Không có thông tin');
                        $('#show_loaifile').text(response.loaifile || 'Không có thông tin');
                        $('#show_tenfile').text(response.tenfile || 'Không có thông tin');
                        $('#download_file').attr('data-id', response.id);
                        $('#download_file').attr('href', '{{ route("quanlydanhmuc.downloadfile", ":id") }}'.replace(':id', id));
                        // Cập nhật link tải file
                        if (response.file_path) {
                            $('#show_file').attr('href', response.file_path);
                            $('#show_file').show();
                        } else {
                            $('#show_file').hide();
                        }
                    } else {
                        showToast('error', 'Không tìm thấy thông tin file');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Lỗi khi lấy thông tin chi tiết file:', xhr.responseText);
                    console.error('Status:', status);
                    console.error('Error:', error);
                    showToast('error', 'Đã xảy ra lỗi khi lấy thông tin chi tiết file.');
                }
            });
        });

        // Xử lý nút upload
        $('.btn-upload').click(function() {
            var id = $(this).attr('value');
            console.log('ID file upload:', id); // Thêm log để kiểm tra giá trị id
            $('#file_id').val(id);

            // Thiết lập action cho form
            var uploadUrl = '{{ route("quanlydanhmuc.upload", ":id") }}'.replace(':id', id);
            $('#uploadForm').attr('action', uploadUrl);

            // Lấy thông tin file nếu cần
            $.ajax({
                url: '{{ route("quanlydanhmuc.getFileInfo", ":id") }}'.replace(':id', id),
                type: 'GET',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('Response upload:', response); // Thêm log để kiểm tra response
                    if (response && response.success) {
                        $('#muasam').text(response.tenthongtin || '');
                        $('#danhmuc').text(response.danhmuc || '');
                        $('#denghi').text(response.denghi || '');
                        $('#loaifile').text(response.loaifile || '');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Lỗi khi lấy thông tin file:', xhr.responseText);
                    console.error('Status:', status);
                    console.error('Error:', error);
                }
            });
        });
        $('.edit-btn').click(function() {
            var id = $(this).data('id');
            $.ajax({
                url: '{{ route("quanlydanhmuc.edit", ":id") }}'.replace(':id', id),
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.file) {
                        $('#edit_tenthongtin').val(response.file.tenthongtin);
                        $('#edit_mota').val(response.file.mota);
                        $('#edit_id_danhmuc').val(response.file.id_danhmuc);
                        $('#edit_id_denghi').val(response.file.id_denghi);
                        $('#editForm').attr('action', '{{ route("quanlydanhmuc.update", ":id") }}'.replace(':id', id));
                    } else {
                        showToast('error', 'Không tìm thấy thông tin file');
                    }
                },
                error: function(xhr) {
                    showToast('error', 'Đã xảy ra lỗi khi lấy thông tin file.');
                }
            });
        });

        // Xử lý nút xóa
        $('.delete-btn').click(function() {
            var id = $(this).data('id');
            var url = '{{ route("quanlydanhmuc.destroy", ":id") }}'.replace(':id', id);
            $('#deleteForm').attr('action', url);
        });

        // Khởi tạo DataTables
        $('.dataTables-quytrinh').DataTable({
            pageLength: 25,
            responsive: true,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "Tất cả"]
            ],
            order: [],
            columnDefs: [{
                orderable: false,
                targets: [0, 6, 8],
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
                    extend: 'copy'
                },
                {
                    extend: 'excelHtml5',
                    text: 'Excel',
                    title: 'Danh sách file quy trình',
                    filename: 'danh_sach_file_quy_trinh',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }
                },
                {
                    extend: 'pdf',
                    title: 'Danh sách file quy trình',
                    filename: 'danh_sach_file_quy_trinh',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }
                },
                {
                    extend: 'print',
                    title: 'Danh sách file quy trình',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    },
                    customize: function(win) {
                        $(win.document.body).addClass('white-bg').css('font-size', '10px');
                        $(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
                    }
                }
            ]
        });
    });
</script>
@endsection