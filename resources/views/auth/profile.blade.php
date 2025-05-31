@extends('layouts.app')
@section('title',"Thông tin người dùng")
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row" style="margin-top: 10px;">
        <div class="col-md-6">
            <div class="ibox-title title-profile" style="background-color: hsla(194, 80%, 56%, 0.932);">
                <h2 class="ml-20">Thông tin tài khoản</h2>
            </div>
            <div class="ibox-content text-lg">
                <!-- Thông tin user -->
                <div class="row mb-3 m-20">
                    <div class="form-group">
                        <label class="form-label label-profile fw-bold">Họ tên:</label>
                        <input type="text" class="form-control" readonly value="{{$user->hoten }}">
                    </div>
                </div>
                <div class=" row mb-3 m-20">
                    <div class="form-group">
                        <label class="form-label label-profile fw-bold">CMND:</label>
                        <input type="text" class="form-control" readonly value="{{ $user->cmnd }}">
                    </div>
                </div>

                <div class="row mb-3 m-20">
                    <div class="form-group">
                        <label class="form-label label-profile fw-bold">Email:</label>
                        <input type="text" class="form-control" readonly value="{{ $user->email }}">
                    </div>
                </div>
                <div class="row mb-3 m-20">
                    <div class="form-group">
                        <label class="form-label label-profile fw-bold">Chức vụ:</label>
                        <input type="text" class="form-control" readonly value="{{ $user->chucvu }}">
                    </div>
                </div>
                <div class="row mb-3 m-20">
                    <div class="form-group">
                        <label class="form-label label-profile fw-bold">Đơn vị công tác:</label>
                        <input type="text" class="form-control" readonly value="{{ $user->donvi->tendonvi }}">
                    </div>
                </div>
                <div class="row mb-3 m-20">
                    <div class="form-group">
                        <a href="#" class="btn btn-primary btn-edit-profile" data-id="{{ $user->id }}">
                            <i class="fa fa-pencil"></i> Cập nhật thông tin
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="ibox-title title-profile" style="background-color: hsla(194, 80%, 56%, 0.932) ;">
                <h2>Ảnh đại diện</h2>
            </div>
            <div class="ibox-content">
                <!-- Avatar -->
                <div class="form-group text-center">
                    @php
                    $imagePath = 'avatar/taikhoan/'. Auth::user()->email . '_' . Auth::user()->id.'/'. Auth::user()->hinhanh;
                    @endphp

                    @if (file_exists(public_path($imagePath)))
                    <img src="{{ asset($imagePath) }}" class="img-circle" alt="Avatar" width="200" height="200">
                    @else
                    <img src="{{ asset('images/avatar.jpg') }}" class="img-circle" alt="Avatar" width="200" height="200">
                    @endif
                </div>
                <div class="text-center" style="margin-top: 20px; padding-bottom: 20px;">
                    <!-- Nút cập nhật ảnh đại diện -->
                    <a href="#" class="btn btn-primary btn-update-avt" data-id="{{ $user->id }}">
                        <i class="fa fa-pencil"></i> Đổi ảnh đại diện </a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Sửa -->
<div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" style="font-size: 20px;" id="editModalLabel">Cập nhật thông tin người dùng</h3>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label">Họ tên</label>
                        <input type="text" name="hoten-edit" id="hoten-edit" class="form-control" value="{{Auth::user()->hoten}}" required>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Chứng minh nhân dân</label>
                        <input type="text" name="cmnd-edit" id="cmnd-edit" class="form-control" value="{{Auth::user()->cmnd}}" required>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Email</label>
                        <input type="text" name="email-edit" id="email-edit" class="form-control" value="{{Auth::user()->email}}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="updateAvtModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" style="font-size: 20px;">Đổi ảnh đại diện</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="updateAvtForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label">Chọn ảnh đại diện</label>
                        <input type="file" name="hinhanh-edit" id="hinhanh-edit" class="form-control" accept=".jpg, .jpeg, .png, .gif">
                    </div>
                    <!-- <div class="dropzone" id="imgDropzone"></div>
                <small class="text-muted">Chỉ chấp nhận file ảnh (JPEG, JPG, PNG, GIF)</small> -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    $(document).ready(function() {
        // Hiển thị modal cập nhật thông tin người dùng

        $('.btn-edit-profile').on('click', function(e) {
            let id = $(this).data('id');
            e.preventDefault();
            $('#editProfileModal').modal('show');
            $('#editProfileModal #editForm').attr('action', "{{ route('profile.update', ':id') }}".replace(':id', id));
        });
        // Hiển thị modal đổi ảnh đại diện
        $('.btn-update-avt').on('click', function(e) {
            let id = $(this).data('id');
            e.preventDefault();
            $('#updateAvtModal').modal('show');
            $('#updateAvtModal #updateAvtForm').attr('action', "{{ route('profile.updateAvatar', ':id') }}".replace(':id', id));

        });
    });
</script>
@endsection