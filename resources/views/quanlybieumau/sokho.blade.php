@extends('layouts.app')

@section('title', 'Sổ quản lý kho')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="ibox float-e-margins">
            <div class="ibox-title my-ibox-title">
                <h2 class="h2-title">Sổ quản lý kho</h2>
            </div>
            <div class="ibox-content">
                <div class="form-group">
                    <label class="form-label">CHỌN KHOA</label>
                    <select class="form-control" id="select-khoa">
                        <option value="">--- Chọn khoa ---</option>
                        @foreach($khoas ?? [] as $khoa)
                        <option value="{{ $khoa->id }}">{{ $khoa->tendonvi }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Chọn Phòng -->
                <div class="form-group">
                    <label class="form-label">CHỌN PHÒNG</label>
                    <select class="form-control" id="select-phong" disabled>
                        <option value="">--- Chọn phòng ---</option>
                    </select>
                </div>
                <div class="btn-action" style="justify-content:start;">
                    <button type="button" class="btn btn-primary" id="btn-in-sokho">
                        <i class="fa fa-print"></i> IN SỔ QUẢN LÝ KHO
                    </button>
                    <button type="button" class="btn btn-primary" id="btn-in-lylich">
                        <i class="fa fa-print"></i> IN SỔ LÝ LỊCH THIẾT BỊ
                    </button>
                    <button type="button" class="btn btn-primary" id="btn-in-sanxuat">
                        <i class="fa fa-print"></i> IN SỔ THIẾT BỊ SẢN XUẤT
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        // Xử lý khi chọn khoa
        $('#select-khoa').change(function() {
            var khoaId = $(this).val();

            if (khoaId) {
                // Enable select phòng
                $('#select-phong').prop('disabled', false);
                // $('#select-phong').html('<option value="">-- Đang tải dữ liệu --</option>');

                // Debug log
                console.log('Đang gọi API lấy phòng với khoa_id = ' + khoaId);

                // Load danh sách phòng theo khoa
                $.ajax({
                    url: "{{ route('bieumau.phongkho.get-by-khoa') }}",
                    type: 'GET',
                    data: {
                        khoa_id: khoaId
                    },
                    dataType: 'json',
                    success: function(data) {
                        console.log('Dữ liệu phòng nhận được:', data);

                        var options = '<option value="">-- Chọn phòng --</option>';

                        if (data && data.length > 0) {
                            $.each(data, function(index, phong) {
                                options += '<option value="' + phong.id + '">' + phong.tenphong + '</option>';
                            });
                        } else {
                            options += '<option value="" disabled>Không có phòng nào cho khoa này</option>';
                        }

                        $('#select-phong').html(options);
                    },
                    error: function(xhr, status, error) {
                        console.error("Lỗi AJAX:", xhr.responseText);
                        $('#select-phong').prop('disabled', true);
                        $('#select-phong').html('<option value="">-- Lỗi khi tải dữ liệu --</option>');
                        alert('Không thể tải danh sách phòng: ' + error);
                    }
                });
            } else {
                $('#select-phong').prop('disabled', true);
                $('#select-phong').html('<option value="">-- Chọn phòng --</option>');
            }
        });

        // Xử lý nút in sổ quản lý kho
        $('#btn-in-sokho').click(function() {
            var khoaId = $('#select-khoa').val();
            var phongId = $('#select-phong').val();

            if (!khoaId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Thiếu thông tin',
                    text: 'Vui lòng chọn khoa trước khi tiếp tục.',
                    confirmButtonText: 'OK'
                });
                return;
            }

            if (!phongId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Thiếu thông tin',
                    text: 'Vui lòng chọn phòng trước khi tiếp tục.',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Thay vì chuyển đến URL trực tiếp, sử dụng Ajax để tải file
            $.ajax({
                url: "{{ url('bieumau/sokho/export') }}/" + khoaId + "/" + phongId,
                type: 'GET',
                xhrFields: {
                    responseType: 'blob' // Để xử lý dữ liệu nhị phân (file)
                },
                success: function(data, status, xhr) {
                    // Lấy tên file từ header Content-Disposition hoặc sử dụng tên mặc định
                    var fileName = 'soquanlykho_' + phongId + '.docx';

                    // Tạo một đối tượng URL cho blob
                    var blob = new Blob([data], {
                        type: xhr.getResponseHeader('content-type')
                    });
                    var url = window.URL.createObjectURL(blob);

                    // Tạo một thẻ a tạm thời để tải file
                    var a = document.createElement('a');
                    a.style.display = 'none';
                    a.href = url;
                    a.download = fileName;
                    document.body.appendChild(a);
                    a.click();

                    // Dọn dẹp
                    window.URL.revokeObjectURL(url);
                    document.body.removeChild(a);
                },
                error: function(xhr, status, error) {
                    alert('Có lỗi xảy ra khi tạo file: ' + error);
                }
            });
        });

        // Xử lý nút in sổ lý lịch thiết bị
        $('#btn-in-lylich').click(function() {
            var khoaId = $('#select-khoa').val();
            var phongId = $('#select-phong').val();

            if (!khoaId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Thiếu thông tin',
                    text: 'Vui lòng chọn khoa trước khi tiếp tục.',
                    confirmButtonText: 'OK'
                });
                return;
            }

            if (!phongId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Thiếu thông tin',
                    text: 'Vui lòng chọn phòng trước khi tiếp tục.',
                    confirmButtonText: 'OK'
                });
                return;
            }

            window.location.href = "{{ url('bieumau/lylich/export') }}/" + khoaId + "/" + phongId;
        });

        // Xử lý nút in sổ thiết bị sản xuất
        $('#btn-in-sanxuat').click(function() {
            var khoaId = $('#select-khoa').val();
            var phongId = $('#select-phong').val();

            if (!khoaId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Thiếu thông tin',
                    text: 'Vui lòng chọn khoa trước khi tiếp tục.',
                    confirmButtonText: 'OK'
                });
                return;
            }

            if (!phongId) {
                Swal.fire({
                    position: 'end',
                    icon: 'warning',
                    title: 'Thiếu thông tin',
                    text: 'Vui lòng chọn phòng trước khi tiếp tục.',
                    confirmButtonText: 'OK'
                });
                return;
            }

            window.location.href = "{{ url('bieumau/sanxuat/export') }}/" + khoaId + "/" + phongId;
        });
    });
</script>
@endsection