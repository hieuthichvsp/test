<?php

namespace App\Http\Controllers\quanlynoithat;

use App\Models\Donvi;
use App\Models\Loaidonvi;
use App\Models\PhongKho;
use App\Models\Thietbidogo;
use App\Http\Controllers\Controller;
use App\Models\Thongkedogo;
use App\Models\Donvitinh;
use Illuminate\Http\Request;
use App\Models\Tinhtrangthietbi;
use App\Models\Loaithietbidogo;

class KiemkeController extends Controller
{
    public function getByLoai($tochuc)
    {
        $donvi = Donvi::where('maloai', $tochuc)->get();
        if (request()->ajax()) {
            return response()->json($donvi);
        }
        return view('quanlynoithat.kiemke.index', compact('kiemke'));
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
        $dsThietBi = Thongkedogo::whereIn('maphongkho', $phongKhoIds)->get();

        return response()->json($dsThietBi);
    }
    // Hiển thị danh sách danh mục kiểm kê
    public function index(Request $request)
    {
        // Khai báo biến đúng tên
        $Kiemke = Thongkedogo::all();
        $tochucs = Loaidonvi::all();
        $donvis = Donvi::all();
        $tinhtrangs = Tinhtrangthietbi::all();
        $phongkhos = PhongKho::all();
        $loais = Loaithietbidogo::all();
        $thietbis = Thietbidogo::all();
        $donvitinhs = Donvitinh::all();
        $tinhtrangs = Tinhtrangthietbi::all();

        if ($request->has(['tochuc_id', 'donvi_id'])) {
            $donViId = $request->input('donvi_id');
            $phongKhoIds = PhongKho::where('madonvi', $donViId)->pluck('id');
            $Kiemke = Thongkedogo::whereIn('maphongkho', $phongKhoIds)->get();
        }

        // Sử dụng tên biến đúng: $Kiemke (chữ cái hoa)
        return view('quanlynoithat.kiemke.index', compact('Kiemke', 'tochucs', 'donvis', 'tinhtrangs', 'phongkhos', 'loais', 'thietbis', 'donvitinhs', 'tinhtrangs'));
    }


    // Chỉnh sửa danh mục kiểm kê
    public function edit($id)
    {
        $Kiemke = Thongkedogo::find($id);
        if (request()->ajax()) {
            return response()->json(['Kiemke' => $Kiemke]);
        }
        return view('quanlynoithat.kiemke.partials.modals');
    }

    // Thêm mới danh mục kiểm kê
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
            'idthietbi' => 'required|integer',
            'namthongke' => 'required|integer',

            // Các trường có thể để trống
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
        $kiemke = Thongkedogo::create($validated);

        // AJAX response
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Thêm thiết bị thành công!',
                'data' => $kiemke
            ]);
        }
        session()->flash('success', 'Thêm kiểm kê thành công!');
        session()->flash('title', 'Thông báo');

        // Nếu không phải AJAX
        return redirect()->route('kiemke.index')->with('success', 'Thêm thiết bị thành công!');
    }




    // Cập nhật danh mục kiểm kê
    public function update(Request $request, $id)
    {
        try {
            $noithat = Thongkedogo::findOrFail($id);
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
            $noithat->namthongke = $request->namthongke;
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
    // Xóa danh mục kiểm kê
    public function destroy($id)
    {
        try {
            $Kiemke = Thongkedogo::findOrFail($id);
            $Kiemke->delete();
            return redirect()->back()->with(['success' => 'Xóa danh mục kiểm kê thành công!', 'title' => 'Xóa danh mục kiểm kê']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Xóa danh mục kiểm kê không thành công!', 'title' => 'Xóa danh mục kiểm kê']);
        }
    }
}
