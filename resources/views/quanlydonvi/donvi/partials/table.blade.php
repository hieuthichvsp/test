<div class="table-responsive">
    <table id="dataTables-donvi" class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên đơn vị</th>
                <th>Tên viết tắt</th>
                <th>Tổ chức</th>
                @can('isAdmin')
                <th>Thao tác</th>
                @endcan
            </tr>
        </thead>
        <tbody>
            @foreach($donvis as $donvi)
            <tr class="gradeX">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $donvi->tendonvi }}</td>
                <td>{{ $donvi->tenviettat}}</td>
                <td>{{ $donvi->loaidonvi->tenloai }}</td>
                <td class="btn-action {{ auth()->user()->cannot('isAdmin') ? 'hidden' : '' }}">
                    <button type="button" class="btn btn-warning btn-xs edit-btn"
                        data-tooltip="Cập nhật"
                        data-id="{{ $donvi->id }}"
                        data-tendonvi='{{ e($donvi->tendonvi) }}'
                        data-tenviettat='{{ e($donvi->tenviettat) }}'
                        data-maloai='{{ $donvi->maloai }}'>
                        <i class="fa fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-danger btn-xs delete-btn"
                        data-tooltip="Xóa"
                        data-id="{{ $donvi->id }}">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>