<?php

namespace App\Http\Controllers\quanlythietbi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Maymocthietbi;
use App\Models\Loaimaymocthietbi;
use App\Models\Nhommaymocthietbi;
use App\Models\PhongKho;
use App\Models\Tinhtrangthietbi;
use Illuminate\Support\Facades\DB;
use App\Exports\ThongKeExport;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Illuminate\Support\Facades\Log;
use App\Models\Donvi;

class ThongKeController extends Controller
{
    public function thongKeTheoLoai()
    {
        $thongke = Maymocthietbi::select(
            'loaimaymocthietbi.tenloai',
            DB::raw('COUNT(maymocthietbi.id) as soluong'),
            DB::raw('SUM(CAST(maymocthietbi.gia AS DECIMAL(15,2))) as tonggiatri')
        )
            ->leftJoin('loaimaymocthietbi', 'maymocthietbi.maloai', '=', 'loaimaymocthietbi.id')
            ->groupBy('loaimaymocthietbi.tenloai')
            ->get();

        return response()->json($thongke);
    }

    public function thongKeTheoNhom()
    {
        $thongke = Maymocthietbi::select(
            'nhommaymocthietbi.tennhom',
            DB::raw('COUNT(maymocthietbi.id) as soluong'),
            DB::raw('SUM(CAST(maymocthietbi.gia AS DECIMAL(15,2))) as tonggiatri')
        )
            ->leftJoin('nhommaymocthietbi', 'maymocthietbi.manhom', '=', 'nhommaymocthietbi.id')
            ->groupBy('nhommaymocthietbi.tennhom')
            ->get();

        return response()->json($thongke);
    }

    public function thongKeTheoPhongKho()
    {
        $thongke = Maymocthietbi::select(
            'phong_kho.tenphong',
            DB::raw('COUNT(maymocthietbi.id) as soluong'),
            DB::raw('SUM(CAST(maymocthietbi.gia AS DECIMAL(15,2))) as tonggiatri')
        )
            ->leftJoin('phong_kho', 'maymocthietbi.maphongkho', '=', 'phong_kho.id')
            ->groupBy('phong_kho.tenphong')
            ->get();

        return response()->json($thongke);
    }

    public function thongKeTheoTinhTrang()
    {
        $thongke = Maymocthietbi::select(
            'tinhtrangthietbi.tinhtrang',
            DB::raw('COUNT(maymocthietbi.id) as soluong'),
            DB::raw('SUM(CAST(maymocthietbi.gia AS DECIMAL(15,2))) as tonggiatri')
        )
            ->leftJoin('tinhtrangthietbi', 'maymocthietbi.matinhtrang', '=', 'tinhtrangthietbi.id')
            ->groupBy('tinhtrangthietbi.tinhtrang')
            ->get();

        return response()->json($thongke);
    }

    public function export(Request $request)
    {
        // Tạo truy vấn với các bộ lọc
        $query = Maymocthietbi::with(['loaimaymocthietbi', 'nhommaymocthietbi', 'phong_kho', 'tinhtrangthietbi']);

        if ($request->has('maloai') && $request->maloai) {
            $query->where('maloai', $request->maloai);
        }

        if ($request->has('manhom') && $request->manhom) {
            $query->where('manhom', $request->manhom);
        }

        if ($request->has('maphongkho') && $request->maphongkho) {
            $query->where('maphongkho', $request->maphongkho);
        }

        if ($request->has('matinhtrang') && $request->matinhtrang) {
            $query->where('matinhtrang', $request->matinhtrang);
        }

        if ($request->has('namsd') && $request->namsd) {
            $query->where('namsd', $request->namsd);
        }

        // Lấy dữ liệu một lần duy nhất
        $maymocthietbis = $query->get();

        // Kiểm tra nếu không có dữ liệu
        if ($maymocthietbis->isEmpty()) {
            return response()->json([
                'message' => 'Không tìm thấy dữ liệu phù hợp với tiêu chí lọc'
            ], 404);
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Add headers
        $headers = [
            'Tên thiết bị (*)',
            'Model (*)',
            'Loại thiết bị',
            'Nhóm thiết bị',
            'Mã số',
            'Số máy',
            'Mô tả',
            'Năm sử dụng',
            'Nguồn gốc',
            'Đơn vị tính',
            'Số lượng',
            'Giá',
            'Chất lượng',
            'Tình trạng',
            'Ghi chú',
            'Ghi chú tình trạng'
        ];

        foreach ($headers as $index => $header) {
            $sheet->setCellValue(chr(65 + $index) . '1', $header);
        }

        // Add data rows
        $row = 2;
        foreach ($maymocthietbis as $thietbi) {
            $sheet->setCellValue('A' . $row, $thietbi->tentb);
            $sheet->setCellValue('B' . $row, $thietbi->model);
            $sheet->setCellValue('C' . $row, $thietbi->loaimaymocthietbi->tenloai ?? '');
            $sheet->setCellValue('D' . $row, $thietbi->nhommaymocthietbi->tennhom ?? '');
            $sheet->setCellValue('E' . $row, $thietbi->maso);
            $sheet->setCellValue('F' . $row, $thietbi->somay);
            $sheet->setCellValue('G' . $row, $thietbi->mota);
            $sheet->setCellValue('H' . $row, $thietbi->namsd);
            $sheet->setCellValue('I' . $row, $thietbi->nguongoc);
            $sheet->setCellValue('J' . $row, $thietbi->donvitinh);
            $sheet->setCellValue('K' . $row, $thietbi->soluong);
            $sheet->setCellValue('L' . $row, $thietbi->gia);
            $sheet->setCellValue('M' . $row, $thietbi->chatluong);
            $sheet->setCellValue('N' . $row, $thietbi->tinhtrangthietbi->tinhtrang ?? 'Chưa xác định');
            $sheet->setCellValue('O' . $row, $thietbi->ghichu);
            $sheet->setCellValue('P' . $row, $thietbi->ghichutinhtrang);
            $row++;
        }

        // Style the header row
        $sheet->getStyle('A1:P1')->getFont()->setBold(true);

        // Auto size columns
        foreach (range('A', 'P') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Create writer and set headers for download
        $writer = new Xlsx($spreadsheet);
        $fileName = 'thong_ke_thiet_bi_' . date('Y-m-d_H-i-s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        try {
            $writer->save('php://output');
            exit();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Lỗi khi xuất file: ' . $e->getMessage()
            ], 500);
        }
    }

    public function xuatFileExcel(Request $request)
    {
        try {
            $maphongkho = $request->maphongkho;
            $matinhtrang = $request->matinhtrang;
            $madonvi = $request->madonvi;

            $query = Maymocthietbi::with(['loaimaymocthietbi', 'nhommaymocthietbi', 'phong_kho', 'tinhtrangthietbi']);

            if ($matinhtrang) {
                $query->where('matinhtrang', $matinhtrang);
            }

            // Create new spreadsheet
            $spreadsheet = new Spreadsheet();

            if ($maphongkho) {
                // Nếu chọn phòng kho cụ thể
                $query->where('maphongkho', $maphongkho);
                $maymocthietbis = $query->get();
                $this->createWorksheet($spreadsheet->getActiveSheet(), $maymocthietbis, $matinhtrang);
            } else {
                // Nếu chọn tất cả phòng kho
                if ($madonvi) {
                    // Nếu đã chọn đơn vị, chỉ lấy các phòng kho thuộc đơn vị đó
                    $phongkhos = PhongKho::where('madonvi', $madonvi)->get();
                } else {
                    // Nếu không chọn đơn vị, lấy tất cả phòng kho
                    $phongkhos = PhongKho::all();
                }

                $isFirst = true;

                foreach ($phongkhos as $phongkho) {
                    $thietbiPhong = clone $query;
                    $thietbiPhong->where('maphongkho', $phongkho->id);
                    $maymocthietbis = $thietbiPhong->get();

                    if ($isFirst) {
                        $sheet = $spreadsheet->getActiveSheet();
                        $sheet->setTitle($phongkho->tenphong);
                        $isFirst = false;
                    } else {
                        $sheet = $spreadsheet->createSheet();
                        $sheet->setTitle($phongkho->tenphong);
                    }

                    $this->createWorksheet($sheet, $maymocthietbis, $matinhtrang, $phongkho);
                }
            }

            // Create Excel file
            $writer = new Xlsx($spreadsheet);
            $phongkho = $maphongkho ? PhongKho::find($maphongkho)->tenphong : 'tatca';
            $donvi = $madonvi ? Donvi::find($madonvi)->tendonvi : 'tatca';
            $filename = 'thong_ke_thiet_bi_' . $donvi . '_' . $phongkho . '_' . date('YmdHis') . '.xlsx';

            // Save to temp file
            $temp_file = tempnam(sys_get_temp_dir(), 'excel');
            $writer->save($temp_file);

            // Return file for download
            return response()->download($temp_file, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ])->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('Lỗi khi xuất file Excel: ' . $e->getMessage());
            return redirect()->back()->with([
                'error' => 'Đã xảy ra lỗi khi xuất file Excel: ' . $e->getMessage(),
                'title' => 'Xuất Excel'
            ]);
        }
    }

    private function createWorksheet($sheet, $maymocthietbis, $matinhtrang, $phongkho = null)
    {
        // Set title
        $sheet->setCellValue('A1', 'THỐNG KÊ THIẾT BỊ');
        $sheet->mergeCells('A1:J1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Filter info
        $row = 3;
        if ($phongkho) {
            $sheet->setCellValue('A' . $row, 'Phòng/Khoa:');
            $sheet->setCellValue('B' . $row, $phongkho->tenphong);
            $sheet->getStyle('A' . $row)->getFont()->setBold(true);
            $row++;
        }

        if ($matinhtrang) {
            $tinhtrangthietbi = Tinhtrangthietbi::find($matinhtrang);
            $sheet->setCellValue('A' . $row, 'Tình trạng:');
            $sheet->setCellValue('B' . $row, $tinhtrangthietbi ? $tinhtrangthietbi->tentinhtrang : 'N/A');
            $sheet->getStyle('A' . $row)->getFont()->setBold(true);
            $row++;
        }

        $row = $row + 1;

        // Set headers
        $headers = ['STT', 'Mã số', 'Tên thiết bị', 'Model', 'Loại thiết bị', 'Nhóm thiết bị', 'Đơn vị tính', 'Số lượng', 'Tình trạng', 'Phòng/Khoa'];
        $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];

        foreach ($columns as $index => $column) {
            $sheet->setCellValue($column . $row, $headers[$index]);
            $sheet->getStyle($column . $row)->getFont()->setBold(true);
            $sheet->getStyle($column . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('DDDDDD');
            $sheet->getStyle($column . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        }

        // Set data
        $row++;
        $stt = 1;

        foreach ($maymocthietbis as $maymocthietbi) {
            $sheet->setCellValue('A' . $row, $stt);
            $sheet->setCellValue('B' . $row, $maymocthietbi->maso);
            $sheet->setCellValue('C' . $row, $maymocthietbi->tentb);
            $sheet->setCellValue('D' . $row, $maymocthietbi->model);
            $sheet->setCellValue('E' . $row, $maymocthietbi->loaimaymocthietbi ? $maymocthietbi->loaimaymocthietbi->tenloai : 'N/A');
            $sheet->setCellValue('F' . $row, $maymocthietbi->nhommaymocthietbi ? $maymocthietbi->nhommaymocthietbi->tennhom : 'N/A');
            $sheet->setCellValue('G' . $row, $maymocthietbi->donvitinh);
            $sheet->setCellValue('H' . $row, $maymocthietbi->soluong);
            $sheet->setCellValue('I' . $row, $maymocthietbi->tinhtrang);
            $sheet->setCellValue('J' . $row, $maymocthietbi->phong_kho ? $maymocthietbi->phong_kho->tenphong : 'N/A');

            foreach ($columns as $column) {
                $sheet->getStyle($column . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            }

            $row++;
            $stt++;
        }

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(10);
        $sheet->getColumnDimension('I')->setWidth(20);
        $sheet->getColumnDimension('J')->setWidth(25);
    }
}
