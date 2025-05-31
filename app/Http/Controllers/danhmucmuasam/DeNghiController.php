<?php

namespace App\Http\Controllers\danhmucmuasam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DeNghi;
use App\Models\LuutruTaptin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use ZipArchive;

class DeNghiController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tendenghi' => 'required|string|max:255',
            'hocky' => 'required|numeric',
            'mota' => 'nullable|string',
            'danhmuc' => 'required|numeric',
            'file.*' => 'nullable|file|max:10240',
        ]);

        $denghi = DeNghi::create([
            'ten_de_nghi' => $validated['tendenghi'],
            'id_hocky' => $validated['hocky'],
            'mo_ta' => $validated['mota'] ?? '',
            'id_nguoitao' => session('id_tk'),
            'id_danhmuc' => $validated['danhmuc'],
        ]);

        $folderPath = public_path('de_nghi_files/' . $denghi->id);
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }

        foreach ($request->file('file') ?? [] as $key => $file) {
            $originalName = $file->getClientOriginalName();
            $safeName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME));
            $extension = $file->getClientOriginalExtension();
            $tenFile = $denghi->id . '_' . $key . '_' . $safeName . '.' . $extension;

            $file->move($folderPath, $tenFile);
            LuutruTaptin::create([
                'ten_file' => $tenFile,
                'loai_file' => $key,
                'id_nguoidung' => Auth::id(),
                'id_denghi' => $denghi->id,
            ]);
        }

        return redirect()->back()->with([
            'success' => 'Đề nghị đã được tạo thành công',
            'title' => 'Thêm đề nghị'
        ]);
    }

    public function destroy($id)
    {
        $denghi = DeNghi::findOrFail($id);
        $folderPath = public_path('de_nghi_files/' . $denghi->id);

        if (file_exists($folderPath)) {
            foreach (scandir($folderPath) as $file) {
                if ($file !== '.' && $file !== '..') {
                    unlink($folderPath . '/' . $file);
                }
            }
            rmdir($folderPath);
        }

        LuutruTaptin::where('id_denghi', $id)->delete();
        $denghi->delete();

        return redirect()->back()->with([
            'success' => 'Xóa đề nghị thành công!',
            'title' => 'Xóa đề nghị'
        ]);
    }

    public function getDeNghi($id)
    {
        $deNghi = DeNghi::find($id);

        if (!$deNghi) {
            return redirect()->back()->with([
                'error' => 'Không tìm thấy đề nghị này!',
                'title' => 'Lỗi'
            ]);
        }
        $files = LuutruTaptin::where('id_denghi', $id)->get();

        return response()->json([
            'id' => $deNghi->id,
            'ten_de_nghi' => $deNghi->ten_de_nghi,
            'mo_ta' => $deNghi->mo_ta,
            'id_hocky' => $deNghi->id_hocky,
            'id_danhmuc' => $deNghi->id_danhmuc,
            'files' => $files->map(function ($file) {
                return [
                    'ten_file' => $file->ten_file,
                    'loai_file' => $file->loai_file,
                    'url' => asset('storage/' . $file->path),
                ];
            }),
        ]);
    }

    public function updateDeNghi(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:de_nghi,id',
            'tendenghi' => 'required|string|max:255',
            'mota' => 'nullable|string|max:500',
            'hocky' => 'required|exists:hoc_ky,id_hk',
            'files' => 'nullable|array',
            'files.*.name' => 'required|string',
            'files.*.type' => 'required|string',
            'files.*.base64' => 'required|string',
        ]);

        $deNghi = DeNghi::find($validated['id']);
        $deNghi->ten_de_nghi = $validated['tendenghi'];
        $deNghi->mo_ta = $validated['mota'] ?? '';
        $deNghi->id_hocky = $validated['hocky'];

        if (isset($validated['files'])) {
            foreach ($validated['files'] as $fileData) {
                $allowedFileTypes = ['pdf', 'jpg', 'jpeg', 'png', 'docx', 'xlsx'];
                $fileType = $fileData['type'];
                $extension = explode('/', $fileType)[1];

                if (!in_array(strtolower($extension), $allowedFileTypes)) {
                    return redirect()->back()->with([
                        'error' => 'Định dạng của tệp không hợp lệ!',
                        'title' => 'Lỗi chọn tệp'
                    ]);
                }

                $fileDataDecoded = base64_decode($fileData['base64']);
                $fileName = time() . '_' . $fileData['name'];

                $path = public_path('de_nghi_files/' . $deNghi->id);
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }

                try {
                    file_put_contents($path . '/' . $fileName, $fileDataDecoded);

                    LuutruTaptin::create([
                        'ten_file' => $fileName,
                        'loai_file' => 'update',
                        'id_nguoidung' => Auth::id(),
                        'id_denghi' => $deNghi->id,
                    ]);
                } catch (\Exception $e) {
                    return redirect()->back()->with([
                        'error' => 'Có lỗi xảy ra lhi lưu tệp. Vui lòng thử lại sau!',
                        'title' => 'Lỗi lưu tệp'
                    ]);
                }
            }
        }
        $deNghi->save();
        return redirect()->back()->with([
            'success' => 'Đề nghị đã được lưu thành công!',
            'title' => 'Lưu đề nghị'
        ]);;
    }

    public function getFilesByDeNghiId($id)
    {
        $deNghi = DeNghi::findOrFail($id);
        $allFiles = LuutruTaptin::where('id_denghi', $id)->get();

        $fileData = [];

        for ($i = 1; $i <= 12; $i++) {
            $filesOfType = $allFiles->where('loai_file', $i);

            if ($filesOfType->count() > 0) {
                $fileData[$i] = $filesOfType->map(function ($file) {
                    return [
                        'name' => $file->ten_file,
                        'url' => asset('de_nghi_files/' . $file->id_denghi . '/' . $file->ten_file)
                    ];
                })->values();
            } else {
                $fileData[$i] = 'Không xác định';
            }
        }

        return response()->json([
            'id_denghi' => $deNghi->id,
            'ten_de_nghi' => $deNghi->ten_de_nghi,
            'id_danhmuc' => $deNghi->id_danhmuc,
            'files' => $fileData
        ]);
    }

    public function downloadZip($idDeNghi)
    {
        $deNghi = DeNghi::findOrFail($idDeNghi);
        $files = LuutruTaptin::where('id_denghi', $idDeNghi)->get();

        $zip = new ZipArchive();
        $fileName = $deNghi->ten_de_nghi . '_' . now()->format('dmY_His') . '.zip';
        $zipPath = storage_path('app/public/temp/' . $fileName);

        if (!file_exists(storage_path('app/public/temp'))) {
            mkdir(storage_path('app/public/temp'), 0777, true);
        }

        if ($zip->open($zipPath, ZipArchive::CREATE) === true) {
            foreach ($files as $file) {
                $filePath = public_path('de_nghi_files/' . $deNghi->id . '/' . $file->ten_file);
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, $file->ten_file);
                }
            }
            $zip->close();

            return response()->json([
                'success' => true,
                'file_url' => asset('storage/temp/' . $fileName)
            ]);
        }

        return redirect()->back()->with([
            'error' => 'Không thể tạo file .zip!',
            'title' => 'Lỗi tải xuống'
        ]);
    }
}
