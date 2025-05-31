<?php

namespace App\Http\Controllers\quanlythietbi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Nhommaymocthietbi;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class NhomThietBiController extends Controller
{
    public function index()
    {
        $nhomthietbis = Nhommaymocthietbi::all();
        return view('quanlythietbi.nhomthietbi.index', compact('nhomthietbis'));
    }

    public function store(Request $request)
    {
        try {
            $validate = $request->validate([
                'tennhom' => 'required|string|max:255'
            ]);

            $nhomthietbi = Nhommaymocthietbi::create($validate);
            
            if (!$nhomthietbi) {
                return redirect()->route('nhomthietbi.index')->with([
                    'error' => 'Đã xảy ra lỗi trong quá trình thêm nhóm thiết bị.',
                    'title' => 'Thêm nhóm thiết bị'
                ]);
            }

            return redirect()->route('nhomthietbi.index')->with([
                'success' => 'Thêm nhóm thiết bị thành công.',
                'title' => 'Thêm nhóm thiết bị'
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating NhomThietBi: ' . $e->getMessage());
            return redirect()->route('nhomthietbi.index')->with([
                'error' => 'Lỗi nhập liệu. Vui lòng kiểm tra lại thông tin.',
                'title' => 'Thêm nhóm thiết bị'
            ]);
        }
    }

    public function edit($id)
    {
        $nhomthietbi = Nhommaymocthietbi::find($id);
        if (request()->ajax()) {
            return response()->json([
                'nhomthietbi' => $nhomthietbi
            ]);
        }
        return view('quanlythietbi.nhomthietbi.partials.modals', compact('nhomthietbi'));
    }

    public function update(Request $request, $id)
    {
        try {
            $validate = $request->validate([
                'tennhom' => 'required|string|max:255'
            ]);

            $nhomthietbi = Nhommaymocthietbi::findOrFail($id);
            if (!$nhomthietbi) {
                return redirect()->route('nhomthietbi.index')->with([
                    'error' => 'Nhóm thiết bị không tồn tại.',
                    'title' => 'Cập nhật nhóm thiết bị'
                ]);
            }

            $nhomthietbi->update($validate);
            return redirect()->route('nhomthietbi.index')->with([
                'success' => 'Cập nhật nhóm thiết bị thành công.',
                'title' => 'Cập nhật nhóm thiết bị'
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating NhomThietBi: ' . $e->getMessage());
            return redirect()->route('nhomthietbi.index')->with([
                'error' => 'Lỗi nhập liệu. Vui lòng kiểm tra lại thông tin.',
                'title' => 'Cập nhật nhóm thiết bị'
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $nhomthietbi = Nhommaymocthietbi::findOrFail($id);
            if (!$nhomthietbi) {
                return redirect()->route('nhomthietbi.index')->with([
                    'error' => 'Nhóm thiết bị không tồn tại.',
                    'title' => 'Xóa nhóm thiết bị'
                ]);
            }

            $nhomthietbi->delete();
            return redirect()->route('nhomthietbi.index')->with([
                'success' => 'Xóa nhóm thiết bị thành công.',
                'title' => 'Xóa nhóm thiết bị'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting NhomThietBi: ' . $e->getMessage());
            return redirect()->route('nhomthietbi.index')->with([
                'error' => 'Đã xảy ra lỗi trong quá trình xóa.',
                'title' => 'Xóa nhóm thiết bị'
            ]);
        }
    }

    public function import(Request $request)
    {
        try {
            // Validate file upload
            $request->validate([
                'file' => 'required|mimes:xlsx,xls|max:2048'
            ]);

            $file = $request->file('file');
            $reader = IOFactory::createReader('Xlsx');
            $spreadsheet = $reader->load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            // Remove header row if exists
            if (isset($rows[0]) && is_array($rows[0])) {
                array_shift($rows);
            }

            $successCount = 0;
            $errorCount = 0;
            $errors = [];

            foreach ($rows as $index => $row) {
                try {
                    // Skip empty rows
                    if (empty(array_filter($row))) {
                        continue;
                    }

                    // Validate and create record
                    $tennhom = trim($row[0] ?? '');
                    
                    if (empty($tennhom)) {
                        throw new \Exception('Tên nhóm thiết bị không được để trống');
                    }

                    Nhommaymocthietbi::firstOrCreate([
                        'tennhom' => $tennhom
                    ]);

                    $successCount++;

                } catch (\Exception $e) {
                    $errorCount++;
                    $errors[] = "Dòng " . ($index + 2) . ": " . $e->getMessage();
                }
            }

            $message = "Import hoàn tất. Thành công: $successCount, Lỗi: $errorCount";
            
            return redirect()->route('nhomthietbi.index')->with([
                'success' => $message,
                'errors' => $errors,
                'title' => 'Import nhóm thiết bị'
            ]);

        } catch (\Exception $e) {
            Log::error('Error importing NhomThietBi: ' . $e->getMessage());
            return redirect()->route('nhomthietbi.index')->with([
                'error' => 'Đã xảy ra lỗi trong quá trình import file: ' . $e->getMessage(),
                'title' => 'Import nhóm thiết bị'
            ]);
        }
    }

    /**
     * Download Excel template for equipment groups
     */
    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Add headers
        $headers = ['Tên nhóm thiết bị (*)'];
        
        foreach ($headers as $index => $header) {
            $sheet->setCellValue(chr(65 + $index) . '1', $header);
        }
        
        // Add example data
        $exampleData = ['Nhóm thiết bị văn phòng'];
        
        foreach ($exampleData as $index => $value) {
            $sheet->setCellValue(chr(65 + $index) . '2', $value);
        }
        
        // Style the header row
        $sheet->getStyle('A1')->getFont()->setBold(true);
        
        // Auto size columns
        $sheet->getColumnDimension('A')->setAutoSize(true);
        
        // Create writer
        $writer = new Xlsx($spreadsheet);
        
        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="mau_nhom_thiet_bi.xlsx"');
        header('Cache-Control: max-age=0');
        
        // Save to PHP output
        $writer->save('php://output');
        exit;
    }

}