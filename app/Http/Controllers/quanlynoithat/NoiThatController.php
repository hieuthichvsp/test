<?php

namespace App\Http\Controllers\quanlynoithat;

use App\Http\Controllers\Controller;
use App\Models\Donvi;
use App\Models\Loaidonvi;
use App\Models\Thietbidogo;
use App\Models\PhongKho;
use App\Models\Loaithietbidogo;
use App\Models\Tinhtrangthietbi;
use App\Models\Donvitinh;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;



class NoiThatController extends Controller
{
    public function getByLoai($tochuc)
    {
        $donvi = Donvi::where('maloai', $tochuc)->get();
        if (request()->ajax()) {
            return response()->json($donvi);
        }
        return view('quanlynoithat.noithat.index', compact('donvi'));
    }

    public function locTheoDonVi(Request $request)
    {
        $toChucId = $request->input('tochuc_id');
        $donViId = $request->input('donvi_id');
        $perPage = $request->input('per_page', 10);

        $validDonVi = Donvi::where('id', $donViId)
            ->where('maloai', $toChucId)
            ->exists();

        if (!$validDonVi) {
            return response()->json([], 200); // Trả về rỗng nếu không khớp
        }

        $phongKhoIds = PhongKho::where('madonvi', $donViId)->pluck('id');
        $dsThietBi = Thietbidogo::whereIn('maphongkho', $phongKhoIds)->get();

        return response()->json($dsThietBi);
    }
    public function updateTinhTrang(Request $request)
    {
        $ids = $request->ids; // danh sách id thiết bị
        $matinhtrang = $request->matinhtrang; // mã tình trạng (ví dụ: 1, 2, 3...)

        if (!$ids || !$matinhtrang) {
            return response()->json(['success' => false, 'message' => 'Thiếu thông tin']);
        }

        // Lấy tên tình trạng tương ứng từ bảng tình trạng thiết bị
        $tinhTrang = TinhTrangThietBi::find($matinhtrang);

        if (!$tinhTrang) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy tình trạng tương ứng!']);
        }

        // Cập nhật tình trạng cho các thiết bị
        Thietbidogo::whereIn('id', $ids)->update([
            'tinhtrang' => $tinhTrang->tinhtrang
        ]);

        return response()->json([
            'success' => true,
            'tentinhtrang' => $tinhTrang->tinhtrang // Gửi tên tình trạng về client
        ]);
    }



    public function index(Request $request)
    {
        $tochucs = Loaidonvi::all();
        $donvis = Donvi::all();

        $noithats = collect();
        $phongkhos = PhongKho::all();
        $loais = Loaithietbidogo::all();
        $thietbis = Thietbidogo::all();
        $donvitinhs = Donvitinh::all();
        $tinhtrangs = Tinhtrangthietbi::all();

        if ($request->has(['tochuc_id', 'donvi_id'])) {
            $donViId = $request->input('donvi_id');

            $phongKhoIds = PhongKho::where('madonvi', $donViId)->pluck('id');
            $noithats = Thietbidogo::whereIn('maphongkho', $phongKhoIds)->get();
        }
        return view('quanlynoithat.noithat.index', compact('noithats', 'tochucs', 'donvis', 'phongkhos', 'loais', 'thietbis', 'donvitinhs', 'tinhtrangs'));
    }

    public function edit($id)
    {
        $noithat = Thietbidogo::find($id);
        if (request()->ajax()) {
            return response()->json(['noithat' => $noithat]);
        }
        return view('quanlynoithat.noithat.partials.modals');
    }

    public function store(Request $request)
    {
        // Validate tất cả các trường cần thiết
        $validated = $request->validate([
            'tentb' => 'required|string|max:255',
            'maso' => 'required|string|max:255',
            'donvitinh' => 'required|string|max:50',
            'tinhtrang' => 'required|string|max:100',
            'maphongkho' => 'required|integer',
            'maloai' => 'required|integer',
            'mota' => 'nullable|string',
            'namsd' => 'nullable|integer',
            'nguongoc' => 'nullable|string|max:255',
            'soluong' => 'nullable|integer',
            'gia' => 'nullable|numeric',
            'chatluong' => 'nullable|string|max:100',
            'ghichu' => 'nullable|string',
            'tontai' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:255',
            'matinhtrang' => 'nullable|string|max:50',
        ]);

        // Lưu vào cơ sở dữ liệu
        $kiemke = Thietbidogo::create($validated);

        // AJAX response
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Thêm thiết bị đồ gỗ thành công!',
                'data' => $kiemke
            ]);
        }

        // Nếu không phải AJAX
        return redirect()->back()->with([
            'success' => 'Thiết bị đã được thêm thành công.',
            'title' => 'Thành công'
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $noithat = Thietbidogo::findOrFail($id);
            $noithat->tentb = $request->tentb;
            $noithat->mota = $request->mota;
            $noithat->maso = $request->maso;
            $noithat->namsd = $request->namsd;
            $noithat->nguongoc = $request->nguongoc;
            $noithat->donvitinh = $request->donvitinh;
            $noithat->soluong = $request->soluong;
            $noithat->gia = $request->gia;
            $noithat->chatluong = $request->chatluong;
            $noithat->tinhtrang = $request->tinhtrang;
            $noithat->save();

            return redirect()->back()->with([
                'success' => 'Cập nhật thiết bị nội thất thành công!',
                'title' => 'Cập nhật thiết bị nội thất'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'error' => 'Cập nhật thiết bị nội thất không thành công!',
                'title' => 'Cập nhật thiết bị nội thất'
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $noithat = Thietbidogo::findOrFail($id);
            $noithat->delete();
            return redirect()->back()->with(['success' => 'Xóa nội thất thành công!', 'title' => 'Xóa nội thất']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Xóa nội thất không thành công!', 'title' => 'Xóa nội thất']);
        }
    }
    public function xoanhieu(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return response()->json(['message' => 'Không có thiết bị nào được chọn.'], 400);
        }

        Thietbidogo::whereIn('id', $ids)->delete();

        return response()->json(['message' => 'Đã xóa các thiết bị thành công.']);
    }
    public function getPhongTheoDonVi($madonvi)
    {
        $phongs = PhongKho::where('madonvi', $madonvi)
            ->get(['id', 'tenphong']);
        return response()->json($phongs);
    }
    // Lấy thiết bị theo mã phòng
    public function getThietBiTheoPhong($maphong)
    {
        $thietbis = Thietbidogo::where('maphongkho', $maphong)->get();
        return response()->json($thietbis);
    }
}
