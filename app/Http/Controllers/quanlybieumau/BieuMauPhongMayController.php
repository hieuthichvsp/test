<?php

namespace App\Http\Controllers\quanlybieumau;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;


class BieuMauPhongMayController extends Controller
{
    public function index()
    {
        // Lấy danh sách khoa
        $khoas = DB::table('donvi')->get();

        // Lấy dữ liệu nhật ký phòng máy
        $bieumaus = DB::table('bieumau')
            ->whereIn('tenbieumau', ['BM-TH-03-00'])
            ->get();

        return view('quanlybieumau.nhatky', compact('khoas', 'bieumaus'));
    }

    public function store(Request $request)
    {
        // Logic xử lý tải lên nhật ký phòng máy
        $request->validate([
            'tenbieumau' => 'required|string|max:50',
            'file' => 'required|mimes:pdf,doc,docx|max:10240',
        ]);

        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $filePath = 'uploads/bieumau/nhatky/' . $fileName;
            $file->storeAs('uploads/bieumau/nhatky', $fileName, 'public');

            DB::table('bieumau')->insert([
                'tenbieumau' => $request->tenbieumau,
                'tentaptin' => $fileName,
                'create_at' => time()
            ]);

            return redirect()->route('bieumau.nhatky')->with('success', 'Biểu mẫu đã được tải lên thành công');
        }

        return redirect()->back()->with('error', 'Có lỗi xảy ra khi tải lên tập tin');
    }

    public function download($id)
    {
        $bieumau = DB::table('bieumau')->where('id', $id)->first();

        if (!$bieumau) {
            return redirect()->back()->with('error', 'Không tìm thấy biểu mẫu');
        }

        $filePath = storage_path('app/public/uploads/bieumau/' . $bieumau->tentaptin);

        if (file_exists($filePath)) {
            return Response::download($filePath, $bieumau->tentaptin);
        }

        return redirect()->back()->with('error', 'Không tìm thấy tập tin');
    }
    public function export($khoaId, $phongId)
    {
        try {
            // Kiểm tra xem thư viện PhpWord có tồn tại hay không
            if (!class_exists('PhpOffice\PhpWord\PhpWord')) {
                return redirect()->back()->with('error', 'Thư viện PhpOffice/PhpWord không được cài đặt. Vui lòng chạy "composer require phpoffice/phpword" để cài đặt.');
            }

            // Lấy thông tin khoa và phòng
            $khoa = DB::table('donvi')->where('id', $khoaId)->first();
            // Lấy thông tin phòng
            $phong = DB::table('phong_kho')->where('id', $phongId)->first();

            if (!$phong) {
                return redirect()->back()->with('error', 'Không tìm thấy thông tin phòng');
            }

            // Lấy dữ liệu kiểm kê của phòng từ bảng kiemke
            $kiemke = DB::table('kiemke')
                ->where('id_phong', $phongId)
                ->orderBy('id', 'desc')
                ->get();

            Log::info('Thông tin khoa: ' . json_encode($khoa));
            Log::info('Thông tin phòng: ' . json_encode($phong));
            Log::info('Số lượng bản ghi kiểm kê: ' . count($kiemke));

            // Tạo tên file
            $fileName = 'nhatky_phongmay_' . $phongId . '.docx';

            // Tạo file Word bằng PHPWord
            $phpWord = new PhpWord();
            $section = $phpWord->addSection();

            // Thiết lập font chữ UTF-8 để hỗ trợ tiếng Việt
            $fontStyle = ['name' => 'Times New Roman', 'size' => 12];
            $paragraphStyle = ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 100];

            // Tiêu đề
            $section->addText('NHẬT KÝ PHÒNG MÁY', ['bold' => true, 'size' => 16], $paragraphStyle);
            $section->addTextBreak(1);

            // Thông tin phòng và khoa
            $section->addText('Phòng: ' . ($phong->tenphong ?? 'Không xác định'), ['bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT]);
            if ($khoa) {
                $section->addText('Khoa: ' . $khoa->tendonvi, ['bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT]);
            }

            $section->addTextBreak(1);

            // Tạo bảng cho danh sách kiểm kê
            $table = $section->addTable(['borderSize' => 1, 'borderColor' => '000000', 'width' => 100 * 50, 'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT]);

            // Header dòng
            $table->addRow();
            $table->addCell(500, ['bgColor' => 'D3D3D3'])->addText('STT', ['bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
            $table->addCell(2500, ['bgColor' => 'D3D3D3'])->addText('Tên thiết bị', ['bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
            $table->addCell(1500, ['bgColor' => 'D3D3D3'])->addText('Mã số', ['bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
            $table->addCell(1000, ['bgColor' => 'D3D3D3'])->addText('Năm SX', ['bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
            $table->addCell(1500, ['bgColor' => 'D3D3D3'])->addText('Hiện trạng', ['bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
            $table->addCell(1800, ['bgColor' => 'D3D3D3'])->addText('Nội dung bảo trì', ['bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
            $table->addCell(1000, ['bgColor' => 'D3D3D3'])->addText('Ghi chú', ['bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

            // Thêm dữ liệu
            $stt = 1;
            foreach ($kiemke as $item) {
                $table->addRow();
                $table->addCell(500)->addText($stt++, [], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
                $table->addCell(2500)->addText($item->ten_tb ?? '');
                $table->addCell(1500)->addText($item->maso ?? '');
                $table->addCell(1000)->addText($item->namsx ?? '', [], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
                $table->addCell(1500)->addText($item->hientrang ?? '');
                $table->addCell(1800)->addText($item->nd_baotri ?? '');
                $table->addCell(1000)->addText($item->ghichu ?? '');
            }

            // Lưu file
            $objWriter = IOFactory::createWriter($phpWord, 'Word2007');

            // Trả về file cho người dùng tải xuống
            header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
            header('Content-Disposition: attachment;filename="' . $fileName . '"');
            header('Cache-Control: max-age=0');

            $objWriter->save('php://output');
            exit;
        } catch (\Exception $e) {
            Log::error('Lỗi in nhật ký: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi in nhật ký phòng máy: ' . $e->getMessage());
        }
    }

    public function exportSoKho($khoaId, $phongId)
    {
        // Lấy thông tin phòng để lấy mã phòng
        $phong = DB::table('phong_kho')->where('id', $phongId)->first();
        $maphong = $phong ? $phong->maphong : $phongId;

        // Tạo tên file
        $fileName = 'soquanlykho_' . $maphong . '.docx';

        // Lấy dữ liệu cần thiết cho sổ quản lý kho
        $thietBi = DB::table('thietbi')
            ->join('phong_kho', 'thietbi.id_phong', '=', 'phong_kho.id')
            ->where('phong_kho.id', $phongId)
            ->where('phong_kho.id_donvi', $khoaId)
            ->select('thietbi.*', 'phong_kho.tenphong')
            ->get();

        // Tạo file Word bằng PHPWord
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();

        // Tiêu đề
        $section->addText('SỔ QUẢN LÝ KHO THIẾT BỊ', ['bold' => true, 'size' => 16], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

        // Thêm thông tin phòng và khoa
        $khoa = DB::table('donvi')->where('id', $khoaId)->first();
        $section->addText('Khoa: ' . $khoa->tendonvi, ['bold' => true]);
        $section->addText('Phòng: ' . $phong->tenphong, ['bold' => true]);

        // Tạo bảng cho danh sách thiết bị
        $table = $section->addTable(['borderSize' => 1, 'borderColor' => '000000']);

        // Header dòng
        $table->addRow();
        $table->addCell(800)->addText('STT', ['bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $table->addCell(3000)->addText('Tên thiết bị', ['bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $table->addCell(1500)->addText('Mã số', ['bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $table->addCell(1000)->addText('Số lượng', ['bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $table->addCell(1000)->addText('Đơn vị tính', ['bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $table->addCell(1500)->addText('Tình trạng', ['bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

        // Thêm dữ liệu
        $stt = 1;
        foreach ($thietBi as $item) {
            $table->addRow();
            $table->addCell(800)->addText($stt++, [], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
            $table->addCell(3000)->addText($item->tentb ?? '');
            $table->addCell(1500)->addText($item->matb ?? '');
            $table->addCell(1000)->addText($item->soluong ?? '', [], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

            // Lấy đơn vị tính
            $donvitinh = DB::table('donvitinh')->where('id', $item->id_donvitinh)->first();
            $table->addCell(1000)->addText($donvitinh ? $donvitinh->tendonvi : '', [], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

            $table->addCell(1500)->addText($item->tinhtrang ?? '');
        }
        // Lưu file
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');

        // Trả về file cho người dùng tải xuống
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $objWriter->save('php://output');
        exit;
    }

    public function getPhongByKhoa(Request $request)
    {
        $khoaId = $request->input('khoa_id');

        if (empty($khoaId)) {
            return response()->json(['error' => 'Chưa chọn khoa'], 400);
        }

        try {
            Log::info('Đang truy vấn phòng cho khoa: ' . $khoaId);

            $phongs = DB::table('phong_kho')
                ->where('madonvi', $khoaId)
                ->select(['id', 'tenphong'])
                ->orderBy('tenphong', 'asc')
                ->get();

            Log::info('Tìm thấy ' . count($phongs) . ' phòng');

            return response()->json([
                'status' => 'success',
                'data' => $phongs,
                'count' => count($phongs)
            ]);
        } catch (\Exception $e) {
            Log::error('Lỗi getPhongByKhoa: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
