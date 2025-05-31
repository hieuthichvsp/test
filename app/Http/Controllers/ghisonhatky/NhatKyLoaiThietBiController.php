<?php

namespace App\Http\Controllers\ghisonhatky;

use App\Http\Controllers\Controller;
use App\Models\Maymocthietbi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\Nhatkytungtb;
use App\Models\TaiKhoan;
use App\Models\Hocky;
use App\Models\PhongKho;
use App\Models\NhatkytungtbNamsd;



class NhatKyLoaiThietBiController extends Controller
{
    public function index()
    {
        // Lấy thông tin nhatkytungtb và liên kết các bảng với nó
        $nhatkys = Nhatkytungtb::with([
            'phong_kho',
            'taikhoan',
            'nhatkytungtb_namsd',
            'maymocthietbi'
        ])->get();
        $phongmays = PhongKho::all();
        $taikhoans = Taikhoan::all();
        $hockys = NhatkytungtbNamsd::orderByDesc('id')->get();
        $thietbis = Maymocthietbi::select('id', 'tentb', 'mota')->distinct()->get();

        return view('ghisonhatky.nhatkyloaithietbi.index', compact(
            'nhatkys',
            'phongmays',
            'taikhoans',
            'hockys',
            'thietbis'
        ));
    }
    public function storeNew(Request $request)
    {
        // Validate dữ liệu
        $validated = $request->validate([
            'idhocky' => 'required|integer|exists:nhatkytungtb_namsd,id',
            'phongSearch' => 'required|string|max:255',
            'magv' => 'required|integer|exists:taikhoan,id',
            'ngay' => 'required|date',
            'giovao' => 'required',
            'giora' => 'required',
            'thietbisd' => 'required|integer|exists:maymocthietbi,id',
            'mucdichsd' => 'nullable|string',
            'tinhtrangtruoc' => 'nullable|string',
            'tinhtrangsau' => 'nullable|string',
        ]);

        // Kiểm tra phòng
        $phong = PhongKho::where('maphong', $validated['phongSearch'])->first();
        if (!$phong) {
            return redirect()->back()->withErrors(['phongSearch' => 'Phòng không tồn tại.']);
        }

        // Kiểm tra nếu nhật ký đã tồn tại
        $existingRecord = Nhatkytungtb::where('ma_namsd', $validated['idhocky'])
            ->where('maphong', $phong->id)
            ->where('matk', $validated['magv'])
            ->where('ngay', $validated['ngay'])
            ->where('giovao', $validated['giovao'])
            ->where('giora', $validated['giora'])
            ->where('idtb', $validated['thietbisd'])
            ->first();

        if ($existingRecord) {
            return redirect()->back()->withErrors(['phongSearch' => 'Nhật ký này đã tồn tại.']);
        }

        // Tạo bản ghi nhật ký
        $nhatky = new Nhatkytungtb();
        $nhatky->ma_namsd = $validated['idhocky'];
        $nhatky->maphong = $phong->id;
        $nhatky->matk = $validated['magv'];
        $nhatky->ngay = $validated['ngay'];
        $nhatky->giovao = $validated['giovao'];
        $nhatky->giora = $validated['giora'];
        $nhatky->idtb = $validated['thietbisd'];
        $nhatky->mucdichsd = $validated['mucdichsd'];
        $nhatky->tinhtrangtruoc = $validated['tinhtrangtruoc'];
        $nhatky->tinhtrangsau = $validated['tinhtrangsau'];
        $nhatky->ngaytao = time();  // Lưu thời gian hiện tại (Unix timestamp)

        $nhatky->save();

        return redirect()->route('nhatkyloaithietbi.index')->with('success', 'Thêm nhật ký thành công.');
    }
    // Hiển thị form sửa nhật ký
    public function edit($id)
    {
        $nhatky = Nhatkytungtb::findOrFail($id);
        $devices = Maymocthietbi::all(); // Lấy tất cả thiết bị
        return view('nhatkyloaithietbi.edit', compact('nhatky', 'devices'));
    }

    // Cập nhật nhật ký
    public function update(Request $request, $id)
    {
        // Validate dữ liệu
        $validated = $request->validate([
            'phongSearch' => 'required|string|max:255',
            'giovao' => 'required',
            'giora' => 'required',
            'thietbisd' => 'required|integer|exists:maymocthietbi,id',
            'mucdichsd' => 'nullable|string',
            'tinhtrangtruoc' => 'nullable|string',
            'tinhtrangsau' => 'nullable|string',
        ]);

        // Tìm bản ghi nhật ký
        $nhatky = Nhatkytungtb::findOrFail($id);
        $phong = PhongKho::where('maphong', $validated['phongSearch'])->first();

        // Cập nhật thông tin nhật ký
        $nhatky->maphong = $phong->id;
        $nhatky->giovao = $validated['giovao'];
        $nhatky->giora = $validated['giora'];
        $nhatky->idtb = $validated['thietbisd'];
        $nhatky->mucdichsd = $validated['mucdichsd'];
        $nhatky->tinhtrangtruoc = $validated['tinhtrangtruoc'];
        $nhatky->tinhtrangsau = $validated['tinhtrangsau'];

        $nhatky->save();

        return redirect()->route('nhatkyloaithietbi.index')->with('success', 'Cập nhật nhật ký thành công.');
    }

    // Xoá nhật ký
    public function destroy($id)
    {
        $nhatky = Nhatkytungtb::findOrFail($id);
        $nhatky->delete();

        return redirect()->route('nhatkyloaithietbi.index')->with('success', 'Xoá nhật ký thành công.');
    }
}
