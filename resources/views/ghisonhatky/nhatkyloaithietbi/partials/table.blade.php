<div class="ibox-content">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover dataTables-nkphongmay">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Thiết bị</th>
                    <th>Ngày</th>
                    <th>Giờ vào</th>
                    <th>Giờ ra</th>
                    <th>Mục đích sử dụng</th>
                    <th>Tình trạng trước khi sử dụng</th>
                    <th>Tình trạng sau khi sử dụng</th>
                    <th>Giáo viên sử dụng</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach($nhatkys as $key => $nhatky)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $nhatky->maymocthietbi->tentb }}</td>
                    <td>{{ date('d/m/Y', strtotime($nhatky->ngay)) }}</td>
                    <td>{{ $nhatky->giovao }}</td>
                    <td>{{ $nhatky->giora }}</td>
                    <td>{{ $nhatky->mucdichsd }}</td>
                    <td>{{ $nhatky->tinhtrangtruoc }}</td>
                    <td>{{ $nhatky->tinhtrangsau }}</td>
                    <td>{{ $nhatky->taikhoan->hoten ?? '---' }}</td>
                    <td>
                        <!-- Nút Sửa -->
                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editPMModal"
                            data-id="{{ $nhatky->id }}" data-phong="{{ $nhatky->maphong }}"
                            data-giovao="{{ $nhatky->giovao }}" data-giora="{{ $nhatky->giora }}"
                            data-mucdichsd="{{ $nhatky->mucdichsd }}"
                            data-tinhtrangtruoc="{{ $nhatky->tinhtrangtruoc }}"
                            data-tinhtrangsau="{{ $nhatky->tinhtrangsau }}"
                            data-phongname="{{ $nhatky->phong_kho->maphong }}" data-thietbi="{{ $nhatky->idtb }}"
                            class="edit-btn">Sửa</button>
                        <!-- Nút Xoá -->
                        <form action="{{ route('nhatkyloaithietbi.destroy', $nhatky->id) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Bạn có chắc chắn muốn xoá?')"
                                class="btn btn-danger btn-sm">Xoá</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>