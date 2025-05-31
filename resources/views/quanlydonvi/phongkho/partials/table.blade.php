<div class="table-responsive">
    <table id="dataTables-phong" class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>STT</th>
                <th>Mã phòng</th>
                <th>Tên phòng</th>
                <th>Khu</th>
                <th>Lầu</th>
                <th>Số phòng</th>
                <th>Giáo viên quản lý</th>
                <th>Đơn vị</th>
                @can('isAdmin')
                <th>Thao tác</th>
                @endcan
            </tr>
        </thead>
        <tbody>
            @foreach($phongkhos as $phongkho)
            <tr class="gradeX">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $phongkho->maphong }}</td>
                <td>{{ $phongkho->tenphong }}</td>
                <td>{{ $phongkho->khu }}</td>
                <td>{{ $phongkho->lau}}</td>
                <td>{{ $phongkho->sophong}}</td>
                <td>{{ $phongkho->taikhoan->hoten ?? '' }}</td>
                <td>{{ $phongkho->donvi->tendonvi ?? '' }}</td>
                <td class="btn-action {{ auth()->user()->cannot('isAdmin') ? 'hidden' : '' }}">
                    <a href="#" class="btn btn-warning btn-xs edit-btn"
                        data-tooltip="Cập nhật"
                        data-id="{{ $phongkho->id }}">
                        <i class="fa fa-edit"></i>
                    </a>
                    <a href="#" class="btn btn-danger btn-xs delete-btn"
                        data-tooltip="Xóa"
                        data-id="{{ $phongkho->id }}">
                        <i class="fa fa-trash"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>