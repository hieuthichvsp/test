@extends('layouts.app')
@section('title', 'Nhật ký phòng máy')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="ibox float-e-margins">
            <div class="ibox-title my-ibox-title">
                <h2 class="h2-title">Nhật ký phòng máy</h2>
            </div>
            <div class="ibox-content">
                <div class="form-group">
                    <label class="form-label">CHỌN KHOA</label>
                    <select class="form-control" id="select-khoa">
                        <option value="">-- Chọn khoa --</option>
                        @foreach($khoas ?? [] as $khoa)
                        <option value="{{ $khoa->id }}">{{ $khoa->tendonvi }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Chọn Phòng -->
                <div class="form-group">
                    <label class="form-label">CHỌN PHÒNG</label>
                    <select class="form-control" id="select-phong" disabled>
                        <option value="">-- Chọn phòng --</option>
                    </select>
                </div>
                <div style="display:flex; justify-content:start; align-items:center; gap:10px">
                    <button type="button" class="btn btn-primary" id="btn-in-nhatky">
                        <i class="fa fa-print"></i> IN SỔ NHẬT KÝ PHÒNG MÁY
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
                $('#select-phong').prop('disabled', true);
                // Console.log để debug
                console.log('Đang gọi API với khoa_id:', khoaId);

                // Load danh sách phòng theo khoa
                $.ajax({
                    url: "{{ route('bieumau.phongkho.get-by-khoa') }}",
                    type: 'GET',
                    data: {
                        khoa_id: khoaId
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log('Kiểu dữ liệu phản hồi:', typeof response);
                        console.log('Phản hồi từ API:', response);
                        console.log('Có thuộc tính status?', response.hasOwnProperty('status'));
                        console.log('Có thuộc tính data?', response.hasOwnProperty('data'));

                        var options = '<option value="">-- Chọn phòng --</option>';
                        var dataArray = [];

                        // Xử lý cả hai định dạng phản hồi
                        if (Array.isArray(response)) {
                            // Phản hồi trực tiếp là mảng (từ SoQuanLyKhoController)
                            dataArray = response;
                        } else if (response && response.status === 'success' && Array.isArray(response.data)) {
                            // Phản hồi dạng {status, data, count} (từ NhatKyPhongMay)
                            dataArray = response.data;
                        }

                        if (dataArray.length > 0) {
                            $.each(dataArray, function(index, phong) {
                                options += '<option value="' + phong.id + '">' + phong.tenphong + '</option>';
                            });
                            $('#select-phong').prop('disabled', false);
                        } else {
                            options += '<option value="" disabled>Không có phòng nào cho khoa này</option>';
                        }

                        $('#select-phong').html(options);
                        // Đảm bảo dropdown được enable
                        $('#select-phong').prop('disabled', false);
                    },
                    error: function(xhr, status, error) {
                        console.error("Lỗi AJAX:", xhr.responseText);
                        $('#select-phong').prop('disabled', true);
                        $('#select-phong').html('<option value="">-- Lỗi khi tải dữ liệu --</option>');
                        alert('Không thể tải danh sách phòng: ' + error);
                    }
                });

                // Thêm đoạn code này sau phần AJAX để đảm bảo dropdown không bị vô hiệu hóa
                setTimeout(function() {
                    console.log('Đang reset trạng thái của dropdown phòng');
                    $('#select-phong').prop('disabled', false);

                    // Kiểm tra trạng thái hiện tại
                    console.log('Trạng thái disabled của select-phong:', $('#select-phong').prop('disabled'));

                    // Kiểm tra CSS
                    console.log('CSS pointer-events:', $('#select-phong').css('pointer-events'));
                }, 1000);
            } else {
                $('#select-phong').prop('disabled', true);
                $('#select-phong').html('<option value="">-- Chọn phòng --</option>');
            }
        });

        // Xử lý nút in sổ nhật ký phòng máy
        $('#btn-in-nhatky').click(function() {
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
                alert('Vui lòng chọn phòng');
                Swal.fire({
                    icon: 'warning',
                    title: 'Thiếu thông tin',
                    text: 'Vui lòng chọn phòng trước khi tiếp tục.',
                    confirmButtonText: 'OK'
                });
                return;
            }

            window.location.href = "{{ url('bieumau/nhatky/export') }}/" + khoaId + "/" + phongId;
        });
    });
</script>
@endsection