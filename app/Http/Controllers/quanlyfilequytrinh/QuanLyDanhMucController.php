<?php

namespace App\Http\Controllers\quanlyfilequytrinh;

use App\Http\Controllers\Controller;
use App\Models\ThongTinLuuTru;
use App\Models\DanhMucMuaSam;
use App\Models\DeNghi;
use App\Models\HocKy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use App\Models\FileLuuTru;
use App\Models\LoaiFileLuuTru;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use ZipArchive;
use Illuminate\Support\Str;

class QuanLyDanhMucController extends Controller
{
    // Global variables for commonly used models and paths
    public function index()
    {
        try {
            $thongtinluutrus = ThongTinLuuTru::query()
                ->orderBy('created_at', 'desc')
                ->get();

            $danhmucs = DanhMucMuaSam::all();
            $denghis = DeNghi::all();
            $hockys = HocKy::all();
            $hockyCurrent = HocKy::where('current', 1)->first();
            $loaifile = LoaiFileLuuTru::all();

            $trangthai = 0;
            $fileluutrus = FileLuuTru::all();
            $tenmuasam = '';
            $tendanhmuc = '';
            $tendenghi = '';
            $tenhocky = '';
            $tenmota = '';
            $id = '';

            if (env('ID_CALL') > 0) {
                $trangthai = 1;
                $fileluutrus = FileLuuTru::where('id_thongtinluutru', env('ID_CALL'))->get();
                $ms = ThongTinLuuTru::where('id', env('ID_CALL'))->first();
                $tenmuasam = $ms->tenthongtin;
                $tendanhmuc = DanhMucMuaSam::find($ms->id_danhmuc)->ten_danhmuc;
                $tendenghi = DeNghi::find($ms->id_denghi)->ten_de_nghi;
                $tenhocky = HocKy::find($ms->id_hocky)->hocky;
                $tenmota = $ms->mota;
                $id = env('ID_CALL');
            }

            return view('Quanlyfilequytrinh.index', compact(
                'id',
                'thongtinluutrus',
                'danhmucs',
                'denghis',
                'hockys',
                'hockyCurrent',
                'fileluutrus',
                'loaifile',
                'trangthai',
                'tenmuasam',
                'tendanhmuc',
                'tendenghi',
                'tenhocky',
                'tenmota'
            ));
        } catch (\Exception $e) {
            Log::error('Lỗi hiển thị danh sách thông tin lưu trữ: ' . $e->getMessage());
            return redirect()->back()->with([
                'error' => 'Đã xảy ra lỗi khi tải dữ liệu' . $e->getMessage(),
                'title' => 'Lỗi hệ thống'
            ]);
        }
    }

    public function reset()
    {
        $envFile = base_path('.env');
        if (file_exists($envFile)) {
            $envContents = file_get_contents($envFile);
            $envContents = preg_replace(
                '/ID_CALL=\d+/',
                'ID_CALL=' . 0,
                $envContents
            );
            file_put_contents($envFile, $envContents);
        }
        // Redirect back to index with updated data
        return redirect()->route('quanlydanhmuc.index')->with([
            'success' => 'Đã cập nhật thông tin lưu trữ',
            'title' => 'Thành công'
        ]);
    }

    public function store(Request $request)
    {
        try {
            // Validate request data
            $validator = Validator::make($request->all(), [
                'tenthongtin' => 'required|string|max:255',
                'mota' => 'nullable|string',
                'id_danhmuc' => 'required|integer|exists:danhmuc_muasam,id',
                'id_denghi' => 'required|integer|exists:de_nghi,id'
            ], [
                'tenthongtin.required' => 'Vui lòng nhập tên thông tin',
                'tenthongtin.max' => 'Tên thông tin không được vượt quá 255 ký tự',
                'id_danhmuc.required' => 'Vui lòng chọn danh mục',
                'id_denghi.required' => 'Vui lòng chọn đề nghị'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Get current semester
            $hocky = HocKy::where('current', true)->firstOrFail();

            // Create storage info record
            $thongtinluutru = ThongTinLuuTru::create([
                'tenthongtin' => $request->tenthongtin,
                'mota' => $request->mota,
                'id_danhmuc' => $request->id_danhmuc,
                'id_denghi' => $request->id_denghi,
                'id_hocky' => $hocky->id,
                'id_user' => Auth::id()
            ]);

            // Create file records for each file type
            $loaiFiles = LoaiFileLuuTru::all();
            foreach ($loaiFiles as $loaiFile) {
                FileLuuTru::create([
                    'tenfile' => 'Trống',
                    'duongdan' => 'Trống',
                    'id_loaifile' => $loaiFile->id,
                    'id_thongtinluutru' => $thongtinluutru->id,
                    'id_user' => Auth::id()
                ]);
            }

            // Create directory structure
            $rootPath = storage_path('app/public/HeThongLuuTru');
            $danhmuc = DanhMucMuaSam::findOrFail($request->id_danhmuc);

            $paths = [
                $rootPath,
                $rootPath . '/' . $danhmuc->ten_danhmuc,
                $rootPath . '/' . $danhmuc->ten_danhmuc . '/' . $hocky->hocky,
                $rootPath . '/' . $danhmuc->ten_danhmuc . '/' . $hocky->hocky . '/' . $thongtinluutru->id . '_' . $thongtinluutru->tenthongtin
            ];

            foreach ($paths as $path) {
                if (!File::exists($path)) {
                    File::makeDirectory($path, 0755, true);
                }
            }

            return redirect()->route('quanlydanhmuc.index')->with([
                'success' => 'Thêm thông tin lưu trữ thành công',
                'title' => 'Thành công'
            ]);
        } catch (\Exception $e) {
            Log::error('Lỗi thêm thông tin lưu trữ: ' . $e->getMessage());
            return redirect()->back()->with([
                'error' => 'Đã xảy ra lỗi khi thêm thông tin lưu trữ: ' . $e->getMessage(),
                'title' => 'Lỗi'
            ]);
        }
    }

    public function edit($id)
    {
        try {
            $thongtinluutru = ThongTinLuuTru::findOrFail($id);
            $danhmucs = DanhMucMuaSam::all();
            $denghis = DeNghi::all();
            if (request()->ajax()) {
                return response()->json([
                    'file' => $thongtinluutru,
                    'danhmucs' => $danhmucs,
                    'denghis' => $denghis,
                ]);
            }

            return view('Quanlyfilequytrinh.edit', compact('thongtinluutru', 'danhmucs', 'denghis'));
        } catch (\Exception $e) {
            Log::error('Lỗi hiển thị form chỉnh sửa: ' . $e->getMessage());
            return redirect()->back()->with([
                'error' => 'Không tìm thấy thông tin lưu trữ',
                'title' => 'Lỗi'
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validate = $request->validate([
                'tenthongtin' => 'required|string|max:255',
                'mota' => 'nullable|string',
                'id_danhmuc' => 'required|integer|exists:danhmuc_muasam,id',
                'id_denghi' => 'required|integer|exists:de_nghi,id'
            ], [
                'tenthongtin.required' => 'Vui lòng nhập tên thông tin',
                'tenthongtin.max' => 'Tên thông tin không được vượt quá 255 ký tự',
                'id_danhmuc.required' => 'Vui lòng chọn danh mục',
                'id_denghi.required' => 'Vui lòng chọn đề nghị'
            ]);

            // Get old info before updating
            $thongtinluutru = ThongTinLuuTru::findOrFail($id);
            $oldTenthongtin = $thongtinluutru->id . "_" . $thongtinluutru->tenthongtin;
            $oldDanhmucId = $thongtinluutru->id_danhmuc;

            // Update database info
            $thongtinluutru->update([
                'tenthongtin' => $validate['tenthongtin'],
                'mota' => $validate['mota'],
                'id_danhmuc' => $validate['id_danhmuc'],
                'id_denghi' => $validate['id_denghi'],
                'updated_at' => now()
            ]);

            // Handle directory updates
            $rootPath = storage_path('app/public/HeThongLuuTru');

            // Get category and semester info
            $oldDanhmuc = DanhMucMuaSam::find($oldDanhmucId);
            $newDanhmuc = DanhMucMuaSam::find($validate['id_danhmuc']);
            $hocky = HocKy::find($thongtinluutru->id_hocky);

            // Build old and new paths
            $oldPath = $rootPath . '/' . $oldDanhmuc->ten_danhmuc . '/' . $hocky->hocky . '/' . $oldTenthongtin;
            $newPath = $rootPath . '/' . $newDanhmuc->ten_danhmuc . '/' . $hocky->hocky . '/' . $id . '_' . $validate['tenthongtin'];

            // Create new directory structure if needed
            if (!File::exists(dirname($newPath))) {
                File::makeDirectory(dirname($newPath), 0755, true);
            }

            // Move files if path changed
            if ($oldPath !== $newPath) {
                if (File::exists($oldPath)) {
                    // Copy all files to new location
                    foreach (File::allFiles($oldPath) as $file) {
                        $relativePath = str_replace($oldPath, '', $file->getPathname());
                        $newFilePath = $newPath . $relativePath;

                        if (!File::exists(dirname($newFilePath))) {
                            File::makeDirectory(dirname($newFilePath), 0755, true);
                        }

                        File::copy($file->getPathname(), $newFilePath);
                    }

                    // Delete old directory after successful copy
                    File::deleteDirectory($oldPath);

                    // Clean up empty parent directories
                    $oldParentPath = dirname($oldPath);
                    while ($oldParentPath !== $rootPath) {
                        if (count(File::files($oldParentPath)) === 0 && count(File::directories($oldParentPath)) === 0) {
                            File::deleteDirectory($oldParentPath);
                            $oldParentPath = dirname($oldParentPath);
                        } else {
                            break;
                        }
                    }
                }
            }

            // Update file paths in database
            $fileluutrus = FileLuuTru::where('id_thongtinluutru', $id)->get();
            foreach ($fileluutrus as $file) {
                if ($file->duongdan !== 'Trống') {
                    $fileName = basename($file->duongdan);
                    $newFilePath = 'public/HeThongLuuTru/' . $newDanhmuc->ten_danhmuc . '/' . $hocky->hocky . '/' . $id . '_' . $validate['tenthongtin'] . '/' . $fileName;

                    // Ensure file exists in new location
                    $fullNewPath = storage_path('app/' . $newFilePath);
                    if (!File::exists($fullNewPath)) {
                        $oldFullPath = storage_path('app/' . $file->duongdan);
                        if (File::exists($oldFullPath)) {
                            File::copy($oldFullPath, $fullNewPath);
                        }
                    }

                    $file->update([
                        'duongdan' => $newFilePath
                    ]);
                }
            }

            return redirect()->route('quanlydanhmuc.index')->with([
                'success' => 'Cập nhật thông tin lưu trữ thành công',
                'title' => 'Thành công'
            ]);
        } catch (\Exception $e) {
            Log::error('Lỗi cập nhật thông tin lưu trữ: ' . $e->getMessage());
            return redirect()->back()->with([
                'error' => 'Đã xảy ra lỗi khi cập nhật thông tin lưu trữ',
                'title' => 'Lỗi'
            ]);
        }
    }
    public function destroy($id)
    {
        try {
            $thongtinluutru = ThongTinLuuTru::findOrFail($id);
            $danhmuc = DanhMucMuaSam::find($thongtinluutru->id_danhmuc);
            $hocky = HocKy::find($thongtinluutru->id_hocky);

            if ($danhmuc && $hocky) {
                $storagePath = storage_path('app/public/HeThongLuuTru/' .
                    $danhmuc->ten_danhmuc . '/' .
                    $hocky->hocky . '/' .
                    $thongtinluutru->id . '_' .
                    $thongtinluutru->tenthongtin);

                if (File::exists($storagePath)) {
                    File::deleteDirectory($storagePath);

                    // Clean up empty parent directories
                    $parentPath = dirname($storagePath);
                    while ($parentPath !== storage_path('app/public/HeThongLuuTru')) {
                        if (
                            count(File::files($parentPath)) === 0 &&
                            count(File::directories($parentPath)) === 0
                        ) {
                            File::deleteDirectory($parentPath);
                            $parentPath = dirname($parentPath);
                        } else {
                            break;
                        }
                    }
                }
            }

            // Delete all associated file storage records
            $fileluutrus = FileLuuTru::where('id_thongtinluutru', $thongtinluutru->id)->get();
            foreach ($fileluutrus as $fileluutru) {
                if ($fileluutru->duongdan !== 'Trống' && Storage::exists($fileluutru->duongdan)) {
                    Storage::delete($fileluutru->duongdan);
                }
                $fileluutru->delete();
            }

            $thongtinluutru->delete();

            return redirect()->route('quanlydanhmuc.index')->with([
                'success' => 'Xóa thông tin lưu trữ thành công',
                'title' => 'Thành công'
            ]);
        } catch (\Exception $e) {
            Log::error('Lỗi xóa thông tin lưu trữ: ' . $e->getMessage());
            return redirect()->back()->with([
                'error' => 'Đã xảy ra lỗi khi xóa thông tin lưu trữ' . $e->getMessage(),
                'title' => 'Lỗi'
            ]);
        }
    }

    public function download($id)
    {
        try {
            // Get required data
            $thongtinluutru = ThongTinLuuTru::findOrFail($id);
            $danhmuc = DanhMucMuaSam::find($thongtinluutru->id_danhmuc);
            $hocky = HocKy::find($thongtinluutru->id_hocky);

            // Build storage path
            $storagePath = storage_path('app/public/HeThongLuuTru/' .
                $danhmuc->ten_danhmuc . '/' .
                $hocky->hocky . '/' .
                $thongtinluutru->id . '_' .
                $thongtinluutru->tenthongtin);
            // Check if directory exists
            if (!File::exists($storagePath)) {
                Log::channel('query')->error("Path not exists: " . $storagePath);
                return back()->with([
                    'error' => 'Thư mục không tồn tại.',
                    'title' => 'Lỗi'
                ]);
            }

            // Get all files in directory
            $files = File::files($storagePath);

            // Check if directory has files
            if (empty($files)) {
                return back()->with([
                    'error' => 'Thư mục trống.',
                    'title' => 'Lỗi'
                ]);
            }

            // Create zip filename
            $timestamp = now()->format('Y-m-d_H-i-s');
            $zipFileName = $thongtinluutru->tenthongtin . '_' . $timestamp . '.zip';
            $zipPath = storage_path('app/temp/' . $zipFileName);

            // Ensure temp directory exists
            if (!File::exists(storage_path('app/temp'))) {
                File::makeDirectory(storage_path('app/temp'), 0755, true);
            }

            // Create new zip archive
            $zip = new \ZipArchive();
            if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
                // Add files to zip
                foreach ($files as $file) {
                    $relativePath = basename($file);
                    $zip->addFile($file, $relativePath);
                }
                $zip->close();

                // Download zip file
                return response()->download($zipPath)->deleteFileAfterSend(true);
            }

            return back()->with([
                'error' => 'Không thể tạo file ZIP.',
                'title' => 'Lỗi'
            ]);
        } catch (\Exception $e) {
            Log::error('Lỗi khi tải xuống folder: ' . $e->getMessage());
            return back()->with([
                'error' => 'Đã xảy ra lỗi khi tải xuống folder: ' . $e->getMessage(),
                'title' => 'Lỗi'
            ]);
        }
    }

    function show($id)
    {
        $envFile = base_path('.env');
        if (file_exists($envFile)) {
            $envContents = file_get_contents($envFile);
            $envContents = preg_replace(
                '/ID_CALL=\d+/',
                'ID_CALL=' . $id,
                $envContents
            );
            file_put_contents($envFile, $envContents);
        }
        // Redirect back to index with updated data
        return redirect()->route('quanlydanhmuc.index')->with([
            'success' => 'Đã cập nhật thông tin lưu trữ',
            'title' => 'Thành công'
        ]);
    }



    public function getFileInfo($id)
    {
        try {
            $file = FileLuuTru::find($id);

            if (!$file) {
                return response()->json(['success' => false, 'message' => 'Không tìm thấy file'], 404);
            }

            return response()->json([
                'success' => true,
                'tenthongtin' => $file->thongtinluutru->tenthongtin,
                'danhmuc' => $file->thongtinluutru->danhmuc->ten_danhmuc ?? '',
                'denghi' => $file->thongtinluutru->denghi->ten_de_nghi ?? '',
                'loaifile' => $file->loaifile->tenloai ?? ''
            ]);
        } catch (\Exception $e) {
            // Trả về lỗi nếu có
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông tin file: ' . $e->getMessage()
            ], 404);
        }
    }

    public function upload(Request $request, $id)
    {
        try {
            // Validate the request
            $request->validate([
                'tenfile' => 'nullable|string|max:255', // Made optional
                'file' => 'required|file|max:10240' // Max 10MB
            ], [
                'tenfile.max' => 'Tên file không được vượt quá 255 ký tự',
                'file.required' => 'Vui lòng chọn file để tải lên',
                'file.max' => 'Kích thước file không được vượt quá 10MB'
            ]);

            if ($request->hasFile('file')) {
                $uploadedFile = $request->file('file');
                $originalFileName = $uploadedFile->getClientOriginalName();
                $fileExtension = $uploadedFile->getClientOriginalExtension();

                // Use original filename if no name provided
                $fileName = $request->tenfile ?
                    $request->tenfile . '.' . $fileExtension :
                    $originalFileName;

                // Get file storage record
                $fileStorage = FileLuuTru::findOrFail($id);

                // Get related info
                $thongtinluutru = ThongTinLuuTru::findOrFail($fileStorage->id_thongtinluutru);
                $danhmuc = DanhMucMuaSam::findOrFail($thongtinluutru->id_danhmuc);
                $hocky = HocKy::findOrFail($thongtinluutru->id_hocky);

                // Build storage path
                $storagePath = storage_path('app/public/HeThongLuuTru/' .
                    $danhmuc->ten_danhmuc . '/' .
                    $hocky->hocky . '/' .
                    $thongtinluutru->id . '_' .
                    $thongtinluutru->tenthongtin);

                // Create directory if it doesn't exist
                if (!File::exists($storagePath)) {
                    File::makeDirectory($storagePath, 0755, true);
                }

                // Generate relative path for database
                $relativePath = 'public/HeThongLuuTru/' .
                    $danhmuc->ten_danhmuc . '/' .
                    $hocky->hocky . '/' .
                    $thongtinluutru->id . '_' .
                    $thongtinluutru->tenthongtin . '/' .
                    $fileName;

                // If current path is not empty, delete old file
                if ($fileStorage->duongdan !== 'Trống') {
                    $oldFilePath = storage_path('app/' . $fileStorage->duongdan);
                    if (File::exists($oldFilePath)) {
                        File::delete($oldFilePath);
                    }
                }

                // Move uploaded file to storage location
                $uploadedFile->move($storagePath, $fileName);

                // Update file record
                $fileStorage->update([
                    'tenfile' => $fileName,
                    'duongdan' => $relativePath,
                    'updated_at' => now()
                ]);

                return redirect()->route('quanlydanhmuc.index')->with([
                    'success' => 'Tải lên file thành công',
                    'title' => 'Thành công'
                ]);
            }

            return redirect()->route('quanlydanhmuc.index')->with([
                'error' => 'Vui lòng chọn file để tải lên',
                'title' => 'Lỗi'
            ]);
        } catch (\Exception $e) {
            Log::error('Lỗi khi tải lên file: ' . $e->getMessage());
            return redirect()->route('quanlydanhmuc.index')->with([
                'error' => 'Đã xảy ra lỗi khi tải lên file: ' . $e->getMessage(),
                'title' => 'Lỗi'
            ]);
        }
    }

    public function readfile($id)
    {
        try {
            // Get file storage record
            $fileStorage = FileLuuTru::findOrFail($id);

            // Check if file exists and is not empty
            if ($fileStorage->duongdan === 'Trống') {
                return response()->json([
                    'success' => false,
                    'message' => 'File không tồn tại'
                ], 404);
            }

            // Get full file path
            $filePath = storage_path('app/' . $fileStorage->duongdan);

            // Check if file exists in storage
            if (!File::exists($filePath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File không tồn tại trong hệ thống'
                ], 404);
            }

            // Get file extension
            $extension = File::extension($filePath);

            // Get file mime type
            $mimeType = File::mimeType($filePath);

            // For PDF files, display inline
            if ($extension === 'pdf') {
                $headers = [
                    'Content-Type' => $mimeType,
                    'Content-Disposition' => 'inline; filename="' . $fileStorage->tenfile . '"'
                ];
                return response()->file($filePath, $headers);
            }

            // For all other files, create zip and download
            $tempDir = storage_path('app/temp');
            if (!File::exists($tempDir)) {
                File::makeDirectory($tempDir, 0755, true);
            }

            // Generate zip filename with timestamp
            $timestamp = now()->format('Y-m-d_H-i-s');
            $zipFileName = pathinfo($fileStorage->tenfile, PATHINFO_FILENAME) . '_' . $timestamp . '.zip';
            $zipPath = $tempDir . '/' . $zipFileName;

            // Create zip archive
            $zip = new \ZipArchive();
            if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
                $zip->addFile($filePath, $fileStorage->tenfile);
                $zip->close();

                return response()->download($zipPath)->deleteFileAfterSend(true);
            }

            return response()->json([
                'success' => false,
                'message' => 'Không thể tạo file ZIP'
            ], 500);
        } catch (\Exception $e) {
            Log::error('Lỗi khi đọc file: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi đọc file: ' . $e->getMessage()
            ], 500);
        }
    }
    public function getFileDetail($id)
    {
        $file = FileLuuTru::find($id); // Thay FileModel bằng tên model thực tế của bạn

        if (!$file) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy file'], 404);
        }



        // Tạo đường dẫn đến file để tải xuống
        $file_path = asset('storage/files/' . $file->tenfile);

        return response()->json([
            'success' => true,
            'id' => $file->id,
            'tenthongtin' => $file->thongtinluutru->tenthongtin ?? '',
            'danhmuc' => $file->thongtinluutru->danhmuc->ten_danhmuc ?? '',
            'denghi' => $file->thongtinluutru->denghi->ten_de_nghi ?? '',
            'loaifile' => $file->loaifile->tenloai ?? '',
            'tenfile' => $file->tenfile,
            'file_path' => $file_path
        ]);
    }


    public function downloadfile($id)
    {
        try {
            // Find the file record
            $fileStorage = FileLuuTru::findOrFail($id);

            // Check if file exists and is not empty
            if ($fileStorage->duongdan === 'Trống') {
                return back()->with([
                    'error' => 'File không tồn tại.',
                    'title' => 'Lỗi'
                ]);
            }

            // Get the full file path
            $filePath = storage_path('app/' . $fileStorage->duongdan);

            // Check if file exists in storage
            if (!File::exists($filePath)) {
                return back()->with([
                    'error' => 'File không tồn tại trong hệ thống.',
                    'title' => 'Lỗi'
                ]);
            }

            // Create temp directory if not exists
            $tempDir = storage_path('app/temp');
            if (!File::exists($tempDir)) {
                File::makeDirectory($tempDir, 0755, true);
            }

            // Generate zip filename with timestamp
            $timestamp = now()->format('Y-m-d_H-i-s');
            $zipFileName = pathinfo($fileStorage->tenfile, PATHINFO_FILENAME) . '_' . $timestamp . '.zip';
            $zipPath = $tempDir . '/' . $zipFileName;

            // Create new zip archive
            $zip = new \ZipArchive();
            if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
                // Add file to zip
                $zip->addFile($filePath, $fileStorage->tenfile);
                $zip->close();

                // Download zip file and delete after sending
                return response()->download($zipPath)->deleteFileAfterSend(true);
            }

            return back()->with([
                'error' => 'Không thể tạo file ZIP.',
                'title' => 'Lỗi'
            ]);
        } catch (\Exception $e) {
            Log::error('Lỗi khi tải xuống file: ' . $e->getMessage());
            return back()->with([
                'error' => 'Đã xảy ra lỗi khi tải xuống file: ' . $e->getMessage(),
                'title' => 'Lỗi'
            ]);
        }
    }

    public function downloadByCategory(Request $request)

    {
        try {
            $danhmuc_id = $request->input('danhmuc_id');
            $hocky_id = $request->input('hocky_id');

            // Tạo thư mục tạm để lưu trữ các file
            $tempDir = storage_path('app/temp');
            if (!File::exists($tempDir)) {
                File::makeDirectory($tempDir, 0755, true);
            }

            // Tạo file zip
            $timestamp = now()->format('Y-m-d_H-i-s');
            $zipFileName = 'files_' . $timestamp . '.zip';
            $zipFilePath = $tempDir . '/' . $zipFileName;

            $zip = new ZipArchive();
            if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
                return back()->with([
                    'error' => 'Không thể tạo file zip!',
                    'title' => 'Lỗi'
                ]);
            }

            $rootPath = storage_path('app/public/HeThongLuuTru');
            $hasFiles = false;

            // Trường hợp 1: Tải tất cả danh mục
            if ($danhmuc_id === 'all') {
                $danhmucs = DanhMucMuaSam::all();
                foreach ($danhmucs as $danhmuc) {
                    $danhmucPath = $rootPath . '/' . $danhmuc->ten_danhmuc;
                    if (File::exists($danhmucPath)) {
                        $this->addDirectoryToZip($zip, $danhmucPath, $danhmuc->ten_danhmuc, $hasFiles);
                    }
                }
            }
            // Trường hợp 2: Tải một danh mục cụ thể
            else {
                $danhmuc = DanhMucMuaSam::findOrFail($danhmuc_id);
                $danhmucPath = $rootPath . '/' . $danhmuc->ten_danhmuc;

                // Trường hợp 2.1: Tải tất cả học kỳ trong danh mục
                if ($hocky_id === 'all') {
                    if (File::exists($danhmucPath)) {
                        $this->addDirectoryToZip($zip, $danhmucPath, $danhmuc->ten_danhmuc, $hasFiles);
                    }
                }
                // Trường hợp 2.2: Tải một học kỳ cụ thể trong danh mục
                else {
                    $hocky = HocKy::findOrFail($hocky_id);
                    $hockyPath = $danhmucPath . '/' . $hocky->hocky;

                    if (File::exists($hockyPath)) {
                        $zipFolderPath = $danhmuc->ten_danhmuc . '/' . $hocky->hocky;
                        $this->addDirectoryToZip($zip, $hockyPath, $zipFolderPath, $hasFiles);
                    }
                }
            }

            $zip->close();

            // Kiểm tra xem có file nào được thêm vào zip không
            if (!$hasFiles) {
                File::delete($zipFilePath);
                return back()->with([
                    'error' => 'Không có file nào để tải xuống!',
                    'title' => 'Lỗi'
                ]);
            }

            // Trả về file zip để download
            return response()->download($zipFilePath)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('Lỗi khi tải xuống danh mục: ' . $e->getMessage());
            return back()->with([
                'error' => 'Đã xảy ra lỗi khi tải xuống danh mục: ' . $e->getMessage(),
                'title' => 'Lỗi'
            ]);
        }
    }

    /**
     * Thêm thư mục và tất cả nội dung của nó vào file zip
     *
     * @param ZipArchive $zip
     * @param string $directoryPath
     * @param string $zipFolderPath
     * @param bool &$hasFiles
     * @return void
     */
    private function addDirectoryToZip(ZipArchive $zip, $directoryPath, $zipFolderPath, &$hasFiles)
    {
        // Thêm tất cả các file trong thư mục
        $files = File::allFiles($directoryPath);
        foreach ($files as $file) {
            $relativePath = substr($file->getPathname(), strlen(storage_path('app/public/HeThongLuuTru')) + 1);
            $zip->addFile($file->getPathname(), $relativePath);
            $hasFiles = true;
        }

        // Thêm tất cả các thư mục con
        $directories = File::directories($directoryPath);
        foreach ($directories as $directory) {
            $subDirName = basename($directory);
            $newZipFolderPath = $zipFolderPath . '/' . $subDirName;
            $this->addDirectoryToZip($zip, $directory, $newZipFolderPath, $hasFiles);
        }
    }
}
