<?php

namespace App\Http\Controllers\Quanlyfilequytrinh;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DeNghi;
use App\Models\DanhmucMuasam;
use App\Models\Hocky;
use App\Models\LuutruTaptin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DeNghiController extends Controller
{
    public function index()
    {
        $denghis = DeNghi::with(['danhmuc_muasam', 'hocky', 'taikhoan', 'luutru_taptins'])->get();
        $danhmucs = DanhmucMuasam::all();
        $hockys = Hocky::all();

        return view('denghi.index', compact('denghis', 'danhmucs', 'hockys'));
    }

    public function store(Request $request)
    {
        try {
            $validate = $request->validate([
                'ten_de_nghi' => 'required|string|max:255',
                'mo_ta' => 'required|string',
                'id_hocky' => 'required|exists:hocky,id',
                'id_danhmuc' => 'required|exists:danhmuc_muasam,id',
            ]);

            $validate['id_nguoitao'] = Auth::id();

            $denghi = DeNghi::create($validate);

            if (!$denghi) {
                return redirect()->route('denghi.index')->with([
                    'error' => 'Đã xảy ra lỗi trong quá trình thêm đề nghị.',
                    'title' => 'Thêm đề nghị'
                ]);
            }

            // Xử lý upload file nếu có
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('denghi_files', $fileName, 'public');

                    LuutruTaptin::create([
                        'ten_file' => $fileName,
                        'loai_file' => $file->getClientOriginalExtension(),
                        'id_nguoidung' => Auth::id(),
                        'id_denghi' => $denghi->id
                    ]);
                }
            }

            return redirect()->route('denghi.index')->with([
                'success' => 'Thêm đề nghị thành công.',
                'title' => 'Thêm đề nghị'
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating DeNghi: ' . $e->getMessage());
            return redirect()->route('denghi.index')->with([
                'error' => 'Lỗi nhập liệu. Vui lòng kiểm tra lại thông tin.',
                'title' => 'Thêm đề nghị'
            ]);
        }
    }

    public function edit($id)
    {
        $denghi = DeNghi::with('luutru_taptins')->findOrFail($id);
        $danhmucs = DanhmucMuasam::all();
        $hockys = Hocky::all();

        if (request()->ajax()) {
            return response()->json([
                'denghi' => $denghi,
                'danhmucs' => $danhmucs,
                'hockys' => $hockys
            ]);
        }

        return view('quanlymuasam.denghi.edit', compact('denghi', 'danhmucs', 'hockys'));
    }

    public function update(Request $request, $id)
    {
        try {
            $validate = $request->validate([
                'ten_de_nghi' => 'required|string|max:255',
                'mo_ta' => 'required|string',
                'id_hocky' => 'required|exists:hocky,id',
                'id_danhmuc' => 'required|exists:danhmuc_muasam,id',
            ]);

            $denghi = DeNghi::findOrFail($id);

            $denghi->update($validate);

            // Xử lý upload file mới nếu có
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('denghi_files', $fileName, 'public');

                    LuutruTaptin::create([
                        'ten_file' => $fileName,
                        'loai_file' => $file->getClientOriginalExtension(),
                        'id_nguoidung' => Auth::id(),
                        'id_denghi' => $denghi->id
                    ]);
                }
            }

            return redirect()->route('denghi.index')->with([
                'success' => 'Cập nhật đề nghị thành công.',
                'title' => 'Cập nhật đề nghị'
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating DeNghi: ' . $e->getMessage());
            return redirect()->route('denghi.index')->with([
                'error' => 'Lỗi nhập liệu. Vui lòng kiểm tra lại thông tin.',
                'title' => 'Cập nhật đề nghị'
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $denghi = DeNghi::findOrFail($id);

            // Xóa các file liên quan
            foreach ($denghi->luutru_taptins as $file) {
                Storage::disk('public')->delete('denghi_files/' . $file->ten_file);
                $file->delete();
            }

            $denghi->delete();

            return redirect()->route('denghi.index')->with([
                'success' => 'Xóa đề nghị thành công.',
                'title' => 'Xóa đề nghị'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting DeNghi: ' . $e->getMessage());
            return redirect()->route('denghi.index')->with([
                'error' => 'Đã xảy ra lỗi trong quá trình xóa.' . $e->getMessage(),
                'title' => 'Xóa đề nghị'
            ]);
        }
    }

    public function download($id)
    {
        try {
            $file = LuutruTaptin::findOrFail($id);
            $path = storage_path('app/public/denghi_files/' . $file->ten_file);

            if (!file_exists($path)) {
                return redirect()->back()->with([
                    'error' => 'File không tồn tại.',
                    'title' => 'Tải file'
                ]);
            }

            return response()->download($path, $file->ten_file);
        } catch (\Exception $e) {
            Log::error('Error downloading file: ' . $e->getMessage());
            return redirect()->back()->with([
                'error' => 'Đã xảy ra lỗi trong quá trình tải file.',
                'title' => 'Tải file'
            ]);
        }
    }

    public function deleteFile($id)
    {
        try {
            $file = LuutruTaptin::findOrFail($id);

            Storage::disk('public')->delete('denghi_files/' . $file->ten_file);
            $file->delete();

            return redirect()->back()->with([
                'success' => 'Xóa file thành công.',
                'title' => 'Xóa file'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting file: ' . $e->getMessage());
            return redirect()->back()->with([
                'error' => 'Đã xảy ra lỗi trong quá trình xóa file.',
                'title' => 'Xóa file'
            ]);
        }
    }
}
