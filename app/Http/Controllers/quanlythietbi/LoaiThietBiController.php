<?php

namespace App\Http\Controllers\quanlythietbi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Loaimaymocthietbi;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class LoaiThietBiController extends Controller
{
    public function index()
    {
        $loaithietbis = Loaimaymocthietbi::all();
        return view('quanlythietbi.loaithietbi.index', compact('loaithietbis'));
    }

    public function store(Request $request)
    {
        try {
            $validate = $request->validate([
                'tenloai' => 'required|string|max:255'
            ]);

            $loaithietbi = Loaimaymocthietbi::create($validate);
            
            if (!$loaithietbi) {
                return redirect()->route('loaithietbi.index')->with([
                    'error' => 'Đã xảy ra lỗi trong quá trình thêm loại thiết bị.',
                    'title' => 'Thêm loại thiết bị'
                ]);
            }

            return redirect()->route('loaithietbi.index')->with([
                'success' => 'Thêm loại thiết bị thành công.',
                'title' => 'Thêm loại thiết bị'
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating LoaiThietBi: ' . $e->getMessage());
            return redirect()->route('loaithietbi.index')->with([
                'error' => 'Lỗi nhập liệu. Vui lòng kiểm tra lại thông tin.',
                'title' => 'Thêm loại thiết bị'
            ]);
        }
    }

    public function edit($id)
    {
        $loaithietbi = Loaimaymocthietbi::find($id);
        if (request()->ajax()) {
            return response()->json([
                'loaithietbi' => $loaithietbi
            ]);
        }
        return view('quanlythietbi.loaithietbi.partials.modals', compact('loaithietbi'));
    }

    public function update(Request $request, $id)
    {
        try {
            $validate = $request->validate([
                'tenloai' => 'required|string|max:255'
            ]);

            $loaithietbi = Loaimaymocthietbi::findOrFail($id);
            if (!$loaithietbi) {
                return redirect()->route('loaithietbi.index')->with([
                    'error' => 'Loại thiết bị không tồn tại.',
                    'title' => 'Cập nhật loại thiết bị'
                ]);
            }

            $loaithietbi->update($validate);
            return redirect()->route('loaithietbi.index')->with([
                'success' => 'Cập nhật loại thiết bị thành công.',
                'title' => 'Cập nhật loại thiết bị'
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating LoaiThietBi: ' . $e->getMessage());
            return redirect()->route('loaithietbi.index')->with([
                'error' => 'Lỗi nhập liệu. Vui lòng kiểm tra lại thông tin.',
                'title' => 'Cập nhật loại thiết bị'
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $loaithietbi = Loaimaymocthietbi::findOrFail($id);
            if (!$loaithietbi) {
                return redirect()->route('loaithietbi.index')->with([
                    'error' => 'Loại thiết bị không tồn tại.',
                    'title' => 'Xóa loại thiết bị'
                ]);
            }

            $loaithietbi->delete();
            return redirect()->route('loaithietbi.index')->with([
                'success' => 'Xóa loại thiết bị thành công.',
                'title' => 'Xóa loại thiết bị'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting LoaiThietBi: ' . $e->getMessage());
            return redirect()->route('loaithietbi.index')->with([
                'error' => 'Đã xảy ra lỗi trong quá trình xóa.',
                'title' => 'Xóa loại thiết bị'
            ]);
        }
    }

    /**
     * Import equipment types from Excel file
     */
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
                    $tenloai = trim($row[0] ?? '');
                    
                    if (empty($tenloai)) {
                        throw new \Exception('Tên loại thiết bị không được để trống');
                    }

                    Loaimaymocthietbi::firstOrCreate([
                        'tenloai' => $tenloai
                    ]);

                    $successCount++;

                } catch (\Exception $e) {
                    $errorCount++;
                    $errors[] = "Dòng " . ($index + 2) . ": " . $e->getMessage();
                }
            }

            $message = "Import hoàn tất. Thành công: $successCount, Lỗi: $errorCount";
            
            return redirect()->route('loaithietbi.index')->with([
                'success' => $message,
                'errors' => $errors,
                'title' => 'Import loại thiết bị'
            ]);

        } catch (\Exception $e) {
            Log::error('Error importing LoaiThietBi: ' . $e->getMessage());
            return redirect()->route('loaithietbi.index')->with([
                'error' => 'Đã xảy ra lỗi trong quá trình import file: ' . $e->getMessage(),
                'title' => 'Import loại thiết bị'
            ]);
        }
    }

    /**
     * Download Excel template for equipment types
     */
    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Add headers
        $headers = ['Tên loại thiết bị (*)'];
        
        foreach ($headers as $index => $header) {
            $sheet->setCellValue(chr(65 + $index) . '1', $header);
        }
        
        // Add example data
        $exampleData = ['Máy tính'];
        
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
        header('Content-Disposition: attachment;filename="mau_loai_thiet_bi.xlsx"');
        header('Cache-Control: max-age=0');
        
        // Save to PHP output
        $writer->save('php://output');
        exit;
    }

}