<?php

namespace App\Http\Controllers\quanlythietbi;

use App\Http\Controllers\Controller;
use App\Models\Maymocthietbi;
use App\Models\Loaimaymocthietbi;
use App\Models\Nhommaymocthietbi;
use App\Models\PhongKho;
use App\Models\Tinhtrangthietbi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\DataTables;
use App\Models\Donvi;
use App\Models\Taikhoan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;


class MayMocThietBiController extends Controller
{
    /**
     * Hiển thị danh sách máy móc thiết bị
     */
    public function index(Request $request)

    {
        // Không tải toàn bộ dữ liệu, chỉ tải dữ liệu cho các dropdown
        $donvis = Donvi::all();
        $loaithietbis = Loaimaymocthietbi::all();
        $nhomthietbis = Nhommaymocthietbi::all();
        $phongkhos = PhongKho::all();
        $tinhtrangthietbis = Tinhtrangthietbi::all();

        return view('quanlythietbi.maymocthietbi.index', compact(
            'donvis',
            'loaithietbis',
            'nhomthietbis',
            'phongkhos',
            'tinhtrangthietbis'
        ));
    }

    /**
     * Lấy dữ liệu cho DataTables
     */
    public function getData(Request $request)
    {
        $query = Maymocthietbi::with(['loaimaymocthietbi', 'nhommaymocthietbi', 'phong_kho', 'tinhtrangthietbi']);

        // Áp dụng các bộ lọc nếu có
        if ($request->has('madonvi') && $request->madonvi != '') {
            if (!$request->has('maphongkho') || $request->maphongkho == '') {
                $phongkhoIds = PhongKho::where('madonvi', $request->madonvi)->pluck('id')->toArray();
                $query->whereIn('maphongkho', $phongkhoIds);
            }
        }

        if ($request->has('maphongkho') && $request->maphongkho != '') {
            $query->where('maphongkho', $request->maphongkho);
        }

        if ($request->has('maloai') && $request->maloai != '') {
            $query->where('maloai', $request->maloai);
        }

        if ($request->has('manhom') && $request->manhom != '') {
            $query->where('manhom', $request->manhom);
        }

        if ($request->has('matinhtrang') && $request->matinhtrang != '') {
            $query->where('matinhtrang', $request->matinhtrang);
        }

        if ($request->has('namsd') && $request->namsd != '') {
            $query->where('namsd', $request->namsd);
        }
        $hasPermission = Gate::allows('hasRole_A_M_L');
        $hasPermissionAM = Gate::allows('hasRole_Admin_Manager');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) use ($hasPermission, $hasPermissionAM) {
                $buttons = '<button class="btn btn-primary btn-xs view-btn" data-tooltip="Xem chi tiết" data-id="' . $row->id . '" data-toggle="modal" data-target="#viewMMTBModal"><i class="fa fa-eye"></i></button>';
                if ($hasPermission) {
                    $buttons .= ' <button class="btn btn-warning btn-xs edit-btn" data-tooltip="Cập nhật" data-id="' . $row->id . '" data-toggle="modal" data-target="#editMMTBModal"><i class="fa fa-edit"></i></button>';
                    if ($hasPermissionAM) {
                        $buttons .= ' <button class="btn btn-danger btn-xs delete-btn" data-tooltip="Xóa" data-id="' . $row->id . '" data-toggle="modal" data-target="#deleteMMTBModal"><i class="fa fa-trash"></i></button>';
                    }
                }
                return $buttons;
            })
            ->editColumn('loaimaymocthietbi.tenloai', function ($row) {
                return $row->loaimaymocthietbi->tenloai ?? 'N/A';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'tentb' => 'required|string|max:255',
                'somay' => 'nullable|integer',
                'maso' => 'nullable|string|max:50',
                'mota' => 'nullable|string',
                'namsd' => 'nullable|integer',
                'nguongoc' => 'nullable|string|max:100',
                'donvitinh' => 'nullable|string|max:50',
                'soluong' => 'nullable|integer|min:0',
                'gia' => 'nullable|string',
                'chatluong' => 'nullable|string',
                'ghichu' => 'nullable|string',
                'ghichutinhtrang' => 'nullable|string',
                'model' => 'required|string|max:100',
                'matinhtrang' => 'nullable|exists:tinhtrangthietbi,id',
                'maphongkho' => 'nullable|exists:phong_kho,id',
                'maloai' => 'nullable|exists:loaimaymocthietbi,id',
                'manhom' => 'nullable|exists:nhommaymocthietbi,id',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with([
                        'error' => 'Lỗi nhập liệu. Vui lòng kiểm tra lại thông tin.',
                        'title' => 'Thêm máy móc thiết bị'
                    ]);
            }

            $maymocthietbi = Maymocthietbi::create($request->all());

            if (!$maymocthietbi) {
                return redirect()->back()->with([
                    'error' => 'Đã xảy ra lỗi trong quá trình thêm thiết bị.',
                    'title' => 'Thêm máy móc thiết bị'
                ]);
            }

            return redirect()->back()->with([
                'success' => 'Thêm máy móc thiết bị thành công.',
                'title' => 'Thêm máy móc thiết bị'
            ]);
        } catch (\Exception $e) {
            Log::error('Error inserting Maymocthietbi: ' . $e->getMessage());
            return redirect()->back()->with([
                'error' => 'Đã xảy ra lỗi: ' . $e->getMessage(),
                'title' => 'Thêm máy móc thiết bị'
            ]);
        }
    }

    /**
     * Hiển thị thông tin chi tiết máy móc thiết bị
     */
    public function edit($id)
    {
        $maymocthietbi = Maymocthietbi::find($id);
        $nhomThietBi = Nhommaymocthietbi::where('id', $maymocthietbi->manhom)->first();
        $loaiThietBi = Loaimaymocthietbi::where('id', $maymocthietbi->maloai)->first();
        $phongKho = PhongKho::where('id', $maymocthietbi->maphongkho)->first();
        $tinhtrang = Tinhtrangthietbi::where('id', $maymocthietbi->matinhtrang)->first();
        if (request()->ajax()) {
            return response()->json([
                'maymocthietbi' => $maymocthietbi,
                'nhomthietbi' => $nhomThietBi,
                'loaithietbi' => $loaiThietBi,
                'phongkho' => $phongKho,
                'tinhtrang' => $tinhtrang,
            ]);
        }
        return view('quanlythietbi.maymocthietbi.partials.modals', compact('maymocthietbi', 'nhomThietBi', 'loaiThietBi', 'phongKho', 'tinhtrang'));
    }

    /**
     * Cập nhật thông tin máy móc thiết bị
     */
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'tentb' => 'required|string|max:255',
                'somay' => 'nullable|integer',
                'maso' => 'nullable|string|max:50',
                'mota' => 'nullable|string',
                'namsd' => 'nullable|integer',
                'nguongoc' => 'nullable|string|max:100',
                'donvitinh' => 'nullable|string|max:50',
                'soluong' => 'nullable|integer|min:0',
                'gia' => 'nullable|string',
                'chatluong' => 'nullable|string',
                'ghichu' => 'nullable|string',
                'ghichutinhtrang' => 'nullable|string',
                'model' => 'required|string|max:100',
                'matinhtrang' => 'nullable|exists:tinhtrangthietbi,id',
                'maphongkho' => 'nullable|exists:phong_kho,id',
                'maloai' => 'nullable|exists:loaimaymocthietbi,id',
                'manhom' => 'nullable|exists:nhommaymocthietbi,id',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with([
                        'error' => 'Lỗi nhập liệu. Vui lòng kiểm tra lại thông tin.',
                        'title' => 'Cập nhật máy móc thiết bị'
                    ]);
            }

            $maymocthietbi = Maymocthietbi::findOrFail($id);
            if (!$maymocthietbi) {
                return redirect()->back()->with([
                    'error' => 'Thiết bị không tồn tại.',
                    'title' => 'Cập nhật máy móc thiết bị'
                ]);
            }

            $maymocthietbi->update($request->all());
            return redirect()->back()->with([
                'success' => 'Cập nhật máy móc thiết bị thành công.',
                'title' => 'Cập nhật máy móc thiết bị'
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating Maymocthietbi: ' . $e->getMessage());
            return redirect()->back()->with([
                'error' => 'Đã xảy ra lỗi: ' . $e->getMessage(),
                'title' => 'Cập nhật máy móc thiết bị'
            ]);
        }
    }

    /**
     * Xóa máy móc thiết bị
     */
    public function destroy($id)
    {
        try {
            $maymocthietbi = Maymocthietbi::findOrFail($id);
            if (!$maymocthietbi) {
                return redirect()->back()->with([
                    'error' => 'Thiết bị không tồn tại.',
                    'title' => 'Xóa máy móc thiết bị'
                ]);
            }

            $maymocthietbi->delete();
            return redirect()->back()->with([
                'success' => 'Xóa máy móc thiết bị thành công.',
                'title' => 'Xóa máy móc thiết bị'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting Maymocthietbi: ' . $e->getMessage());
            return redirect()->back()->with([
                'error' => 'Đã xảy ra lỗi trong quá trình xóa: ' . $e->getMessage(),
                'title' => 'Xóa máy móc thiết bị'
            ]);
        }
    }

    /**
     * Import dữ liệu từ file Excel
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
                    $tentb = trim($row[0] ?? '');
                    $model = trim($row[1] ?? '');

                    if (empty($tentb)) {
                        throw new \Exception('Tên thiết bị không được để trống');
                    }

                    if (empty($model)) {
                        throw new \Exception('Model thiết bị không được để trống');
                    }

                    // Tìm hoặc tạo loại thiết bị nếu có
                    $loaiThietBi = null;
                    if (!empty($row[2])) {
                        $loaiThietBi = Loaimaymocthietbi::firstOrCreate(
                            ['tenloai' => trim($row[2])]
                        );
                    }

                    // Tìm hoặc tạo nhóm thiết bị nếu có
                    $nhomThietBi = null;
                    if (!empty($row[3])) {
                        $nhomThietBi = Nhommaymocthietbi::firstOrCreate(
                            ['tennhom' => trim($row[3])]
                        );
                    }

                    Maymocthietbi::create([
                        'tentb' => $tentb,
                        'model' => $model,
                        'maso' => $row[4] ?? null,
                        'somay' => !empty($row[5]) ? (int)$row[5] : null,
                        'mota' => $row[6] ?? null,
                        'namsd' => !empty($row[7]) ? (int)$row[7] : null,
                        'nguongoc' => $row[8] ?? null,
                        'donvitinh' => $row[9] ?? null,
                        'soluong' => !empty($row[10]) ? (int)$row[10] : null,
                        'gia' => $row[11] ?? null,
                        'chatluong' => $row[12] ?? null,
                        'tinhtrang' => $row[13] ?? null,
                        'ghichu' => $row[13] ?? null,
                        'ghichutinhtrang' => $row[14] ?? null,
                        'maloai' => $loaiThietBi ? $loaiThietBi->id : null,
                        'manhom' => $nhomThietBi ? $nhomThietBi->id : null,
                    ]);

                    $successCount++;
                } catch (\Exception $e) {
                    $errorCount++;
                    $errors[] = "Dòng " . ($index + 2) . ": " . $e->getMessage();
                }
            }

            $message = "Import hoàn tất. Thành công: $successCount, Lỗi: $errorCount";

            return redirect()->back()->with([
                'success' => $message,
                'errors' => $errors,
                'title' => 'Import máy móc thiết bị'
            ]);
        } catch (\Exception $e) {
            Log::error('Error importing Maymocthietbi: ' . $e->getMessage());
            return redirect()->back()->with([
                'error' => 'Đã xảy ra lỗi trong quá trình import file: ' . $e->getMessage(),
                'title' => 'Import máy móc thiết bị'
            ]);
        }
    }

    /**
     * Tải mẫu file Excel
     */
    public function downloadTemplate()
    {
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

        // Add example data
        $exampleData = [
            'Máy tính Dell Inspiron',
            'Inspiron 3501',
            'Máy tính',
            'Thiết bị văn phòng',
            'DELL-3501',
            '12345',
            'Máy tính xách tay Dell Inspiron 15 inch',
            '2022',
            'Nhập khẩu',
            'Cái',
            '1',
            '15000000',
            'Mới',
            'Đang sử dụng',
            'Thiết bị mới nhập về',
            'Hoạt động tốt'
        ];

        foreach ($exampleData as $index => $value) {
            $sheet->setCellValue(chr(65 + $index) . '2', $value);
        }

        // Style the header row
        $sheet->getStyle('A1:P1')->getFont()->setBold(true);

        // Auto size columns
        foreach (range('A', 'P') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Create writer
        $writer = new Xlsx($spreadsheet);

        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="mau_may_moc_thiet_bi.xlsx"');
        header('Cache-Control: max-age=0');

        // Save to PHP output
        $writer->save('php://output');
        exit;
    }

    public function show($id)
    {
        $maymocthietbi = Maymocthietbi::findOrFail($id);
        $loaithietbi = $maymocthietbi->loaimaymocthietbi;
        $nhomthietbi = $maymocthietbi->nhommaymocthietbi;
        $phongkho = $maymocthietbi->phong_kho;
        $tinhtrangthietbi = $maymocthietbi->tinhtrangthietbi;

        return response()->json([
            'maymocthietbi' => $maymocthietbi,
            'loaithietbi' => $loaithietbi,
            'nhomthietbi' => $nhomthietbi,
            'phongkho' => $phongkho,
            'tinhtrangthietbi' => $tinhtrangthietbi
        ]);
    }
}
