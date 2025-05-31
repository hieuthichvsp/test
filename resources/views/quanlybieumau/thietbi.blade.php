@extends('layouts.app')
@section('title', 'Biểu mẫu thiết bị')
@section('css')
<style>
    .bieumau-link {
        color: #1ab394;
        text-decoration: none;
        transition: color 0.3s;
        cursor: pointer;
    }

    .bieumau-link:hover {
        color: #18a689;
        text-decoration: underline;
    }
</style>
@endsection
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="ibox-title my-ibox-title">
            <h2 class="h2-title">DANH SÁCH BIỂU MẪU</h2>
            @canany('hasRole_A_M_L')
            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#uploadModal">
                <i class="fa fa-plus"></i> Thêm mới
            </a>
            @endcan
        </div>
        <div class="ibox-content">
            <div class="table-responsive">
                <table id="dataTables-bieumau" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên biểu mẫu</th>
                            <th>Tên tập tin</th>
                            @can('hasRole_A_M_L')
                            <th>Thao tác</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bieumaus as $bm) <tr class="gradeX">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $bm->tenbieumau }}</td>
                            <td>
                                <a href="{{ route('bieumau.thietbi.download', $bm->id) }}" class="bieumau-link" data-tooltip="Nhấp vào để tải xuống">
                                    {{ $bm->tentaptin }}
                                </a>
                            </td>
                            @canany('hasRole_A_M_L')
                            <td class="btn-action">
                                <!-- Nút chỉnh sửa -->
                                <a href="{{ route('bieumau.thietbi.edit', $bm->id) }}" class="btn btn-warning btn-xs" data-tooltip="Cập nhật">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <!-- Nút xóa -->
                                @can('hasRole_Admin_Manager')
                                <button class="btn btn-danger btn-xs delete-btn"
                                    data-id="{{ $bm->id }}"
                                    data-tooltip="Xóa">
                                    <i class="fa fa-trash"></i>
                                </button>
                                @endcan
                            </td>
                            @endcan
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Modal Xóa Biểu Mẫu -->
            <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title text-end" id="deleteModalLabel">
                                <i class="fa fa-exclamation-triangle text-danger"></i> Xóa biễu mẫu
                            </h4>
                            <button type=" button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="deleteForm" action="" method="POST">
                            @csrf
                            @method('DELETE')
                            <div class="modal-body">
                                <p>Bạn có chắc chắn muốn xóa biểu mẫu này không?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                                <button type="submit" class="btn btn-danger">Xóa</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Upload -->
<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Tải lên biểu mẫu mới</h4>
            </div>
            <form action="{{ route('bieumau.thietbi.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tenbieumau">Tên biểu mẫu</label>
                        <input type="text" class="form-control" id="tenbieumau" name="tenbieumau" required>
                    </div>
                    <div class="form-group">
                        <label for="file">Tập tin</label>
                        <input type="file" class="form-control" id="file" name="file" required>
                        <p class="help-block">Chỉ hỗ trợ file PDF, DOC, DOCX</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Tải lên</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() { // Xử lý nút xóa - sử dụng event delegation để bắt sự kiện cho các phần tử mới được tạo bởi DataTables
        $(document).on('click', '.delete-btn', function() {
            var id = $(this).data('id');
            var url = '{{ route("bieumau.thietbi.destroy", ":id") }}'.replace(':id', id);
            $('#deleteForm').attr('action', url);
            $('#deleteModal').modal('show');
        });

        // Xử lý nút chỉnh sửa
        $(document).on('click', '.edit-btn', function(e) {
            console.log("Đã nhấn nút chỉnh sửa");
        });
        const hasPermission = "{{auth()->user()->can('hasRole_A_M_L')}}";
        const targets = hasPermission ? [0, 3] : [0];
        var table = $('#dataTables-bieumau').DataTable({
            pageLength: 10,
            responsive: true,
            processing: true, // Hiển thị thông báo khi xử lý
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "Tất cả"]
            ],
            order: [], // Không sắp xếp mặc định
            columnDefs: [{
                orderable: false,
                targets: targets, // Không sắp xếp cột STT và Thao tác
                className: 'text-center' // Căn giữa cho cột STT và Thao tác
            }],
            createdRow: function(row, data, index) {
                $('td:eq(3)', row).addClass('action-btns');
            },
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
            fixedHeader: true,
            dom: "<'row mb-3'" +
                "<'col-md-4'l>" + // show entries
                "<'col-md-4 text-center'B>" + // buttons
                "<'col-md-4 d-flex justify-content-end'f>" + // search về phải
                ">" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            buttons: [{
                    extend: 'copy',
                    text: 'Copy'
                },
                {
                    extend: 'csv',
                    text: 'CSV',
                    charset: 'utf-8',
                    bom: true // Thêm BOM để fix lỗi font tiếng Việt
                },
                {
                    extend: 'excelHtml5',
                    text: 'Excel',
                    filename: 'Danh sách biểu mẫu thiết bị',
                    exportOptions: {
                        columns: [0, 1, 2] // Chỉ xuất 3 cột đầu: STT, Tên biểu mẫu, Tên tập tin
                    },
                    customize: function(xlsx) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        var styles = xlsx.xl['styles.xml'];

                        // Đẩy toàn bộ các row xuống 1 hàng (dòng 2 trở đi)
                        $('row', sheet).each(function() {
                            var r = parseInt($(this).attr('r'));
                            $(this).attr('r', r + 1);
                            $(this).find('c').each(function() {
                                var cellRef = $(this).attr('r');
                                var col = cellRef.replace(/[0-9]/g, '');
                                var row = parseInt(cellRef.replace(/[A-Z]/g, '')) + 1;
                                $(this).attr('r', col + row);
                            });
                        }); // Thêm tiêu đề
                        var r1 = $('<row r="1"></row>');
                        var titleCell = $('<c r="A1" t="inlineStr" s="51"><is><t>DANH SÁCH BIỂU MẪU THIẾT BỊ</t></is></c>');
                        r1.append(titleCell);
                        $('worksheet sheetData', sheet).prepend(r1);

                        // Merge tiêu đề ra giữa
                        var mergeCells = $('mergeCells', sheet);
                        if (mergeCells.length === 0) {
                            mergeCells = $('<mergeCells count="1"></mergeCells>');
                            $('worksheet', sheet).append(mergeCells);
                        }
                        mergeCells.append('<mergeCell ref="A1:C1"/>');
                    }
                },
                {
                    extend: 'print',
                    text: 'Print',
                    exportOptions: {
                        columns: [0, 1, 2]
                    },
                    customize: function(win) {
                        $(win.document.body).prepend('<h4 style="text-align:center;margin-bottom:20px;">DANH SÁCH BIỂU MẪU THIẾT BỊ</h4>');
                    }
                }
            ]
        }); // Xử lý form thêm biểu mẫu
        $('#uploadModal form').submit(function() {
            // Kiểm tra dữ liệu nhập
            var tenbieumau = $('#tenbieumau').val();
            var file = $('#file').val();

            if (!tenbieumau || !file) {
                alert('Vui lòng nhập đầy đủ thông tin');
                return false;
            }

            return true;
        });

        // Ẩn thông báo sau 5 giây
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
        // Cập nhật đường dẫn cho các mục biểu mẫu
        $('.nav-second-level a').each(function() {
            var href = $(this).attr('href');
            if (href == '#') {
                if ($(this).text().trim() == "Biểu mẫu thiết bị") {
                    $(this).attr('href', "{{ route('bieumau.thietbi') }}");
                }

                if ($(this).text().trim() == "Sổ quản lý kho") {
                    $(this).attr('href', "{{ route('bieumau.sokho') }}");
                }
                if ($(this).text().trim() == "Nhật ký phòng máy") {
                    $(this).attr('href', "{{ route('bieumau.nhatky') }}");
                }
            }
        });

        // Xử lý sự kiện click vào liên kết tải xuống
        $(document).on('click', '.bieumau-link, a[href*="thietbi/download"]', function(e) {
            var linkElement = $(this);
            var downloadUrl = linkElement.attr('href');

            toastr.info('Đang tải xuống tập tin...', 'Thông báo');

            var fileName = linkElement.text().trim();
            if (!fileName || fileName === "") {
                fileName = linkElement.attr('title') || "Tập tin";
            }

            var downloadSuccess = true;

            setTimeout(function() {
                if ($('.alert-danger').length > 0 && $('.alert-danger').is(':visible')) {
                    toastr.error('Không thể tải xuống tập tin "' + fileName + '". ' + $('.alert-danger').text(), 'Lỗi');
                } else if (downloadSuccess) {
                    toastr.success('Tập tin "' + fileName + '" đã được tải xuống thành công', 'Hoàn tất');
                }
            }, 1500);
        });
    });
</script>
@endsection