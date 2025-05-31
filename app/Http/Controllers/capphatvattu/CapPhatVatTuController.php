<?php

namespace App\Http\Controllers\capphatvattu;

use App\Http\Controllers\Controller;
use App\Models\Capphat;
use App\Models\HocKy;
use App\Models\HocPhan;
use App\Models\TaiKhoan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ThietBiExport;
use App\Models\Maymocthietbi;
use Illuminate\Support\Str;

class CapPhatVatTuController extends Controller
{
    /**
     * Hiển thị danh sách cấp phát vật tư
     */
    public function index(Request $request)
    {
        $hockys = HocKy::orderBy('id', 'desc')->get();
        $hocphans = HocPhan::all();
        $giangviens = Taikhoan::all();
        $hockyCurrent = Hocky::where('current', '1')->first();
        $thietbis = Maymocthietbi::where('matinhtrang', 1)
            ->get()
            ->unique('tentb')
            ->values();
        return view('capphatvattu.index', compact('hockys', 'hocphans', 'hockyCurrent', 'giangviens', 'thietbis'));
    }
    public function filter(Request $request)
    {
        $hocky_id = $request->input('hocky_id');
        $hocphan_id = $request->input('hocphan_id');
        $capphat = Capphat::with(['taikhoan', 'hocphan'])
            ->where('id_hocky', $hocky_id)
            ->where('hocphan_id', $hocphan_id)
            ->get();
        return response()->json(['data' => $capphat]);
    }
    /**
     * Thêm cấp phát vật tư và lưu file Excel vào public/filecapphatvattu
     */
    public function store(Request $request)
    {
        $request->validate([
            'hocky' => 'required|integer',
            'maHP' => 'required|integer',
            'maLop' => 'required|string|max:255',
            'siSo' => 'required|numeric|min:1',
            'id_gv' => 'required|integer',
            'thiet_bi' => 'required|array',
            'so_luong' => 'required|array',
        ]);

        $data = [];
        foreach ($request->thiet_bi as $index => $tenTB) {
            if (!empty($tenTB)) {
                $data[] = [
                    'Thiết bị' => $tenTB,
                    'Số lượng' => $request->so_luong[$index] ?? 0,
                ];
            }
        }

        // Tạo file Excel
        $timestamp = now()->format('Ymd');
        $fileName = $request->maLop . '_' . $timestamp . '.xlsx';
        $folderPath = public_path('filecapphatvattu');
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0755, true);
        }

        /* Lưu Excel vào public/filecapphatvattu*/
        // Excel::store(new ThietBiExport($data), $fileName, 'local');
        // rename(storage_path("app/{$fileName}"), $folderPath . '/' . $fileName);

        // Lưu vào CSDL
        Capphat::create([
            'hocphan_id' => $request->maHP,
            'maLop' => $request->maLop,
            'siSo' => $request->siSo,
            'id_gv' => $request->id_gv,
            'id_hocky' => $request->hocky,
            'file_cap' => $fileName,
            'file_xacnhan' => null,
        ]);

        return redirect()->back()->with(['success' => 'Thêm cấp vật tư thành công', 'title' => 'Thêm cấp phát vật tư']);
    }
    /**
     * Tải file Excel đã lưu (file cấp phát và file xác nhận)
     */
    public function taiFile($filename)
    {
        $path = public_path('filecapphatvattu/' . $filename);
        if (!file_exists($path)) {
            abort(404);
        }
        return response()->download($path);
    }
    /**
     * Xử lý tải file xác nhận lên csdl và lưu vào public/filecapphatvattu
     */
    public function uploadXacNhanSubmit(Request $request, $id)
    {
        $request->validate([
            'file_xacnhan' => 'required|file|mimes:pdf,doc,docx,xlsx,xls,jpg,jpeg,png|max:5120'
        ]);

        $capphat = Capphat::findOrFail($id);

        $file = $request->file('file_xacnhan');
        $fileName = 'xacnhan_' . Str::slug($capphat->maLop . '_' . now()->format('Ymd_His')) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('filecapphatvattu'), $fileName);

        $capphat->file_xacnhan = $fileName;
        $capphat->save();

        return redirect()->route('capphatvattu.index')->with(['success' => 'Đã tải lên file xác nhận', 'title' => 'Tải file vật tư']);
    }
    /**
     * Xoá cấp phát vật tư
     */
    public function destroy($id)
    {
        $capphat = Capphat::findOrFail($id);
        // Xoá file trong thư mục public/filecapphatvattu
        $fileCapPath = public_path('filecapphatvattu/' . $capphat->file_cap);
        $fileXacNhanPath = public_path('filecapphatvattu/' . $capphat->file_xacnhan);

        if ($capphat->file_cap && file_exists($fileCapPath)) {
            unlink($fileCapPath);
        }

        if ($capphat->file_xacnhan && file_exists($fileXacNhanPath)) {
            unlink($fileXacNhanPath);
        }

        $capphat->delete();

        return redirect()->back()->with(['success' => 'Đã xóa cấp phát thành công', 'title' => 'Xóa cấp phát vật tư']);
    }
}
