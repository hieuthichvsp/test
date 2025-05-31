<?php

namespace App\Http\Controllers\report;

use App\Http\Controllers\Controller;
use App\Models\Nhatkyphongmay;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Converter;

class RoomUsageController extends Controller
{
    public function generateWeeklyReport()
    {
        try {
            // Xác định tuần trước
            $startOfLastWeek = Carbon::now()->startOfWeek(Carbon::MONDAY)->subWeek();
            $endOfLastWeek = Carbon::now()->endOfWeek(Carbon::SUNDAY)->subWeek();

            // Lấy dữ liệu lịch sử từ bảng Nhatkyphongmay
            $records = Nhatkyphongmay::with(['phong_kho', 'taikhoan'])->whereBetween('ngay', [$startOfLastWeek->format('Y-m-d'), $endOfLastWeek->format('Y-m-d')])->get();

            $reports = [];

            foreach ($records as $record) {
                $gvql = $record->phong_kho?->taikhoan; // Giảng viên quản lý phòng này
                if (!$gvql) continue;
                $key = $gvql->email;

                if (!isset($reports[$key])) {
                    $reports[$key] = [
                        'gvql_name' => $gvql->hoten,
                        'rooms' => [],
                    ];
                }

                $roomKey = $record->phong_kho->tenphong ?? 'Không xác định';
                if (!isset($reports[$key]['rooms'][$roomKey])) {
                    $reports[$key]['rooms'][$roomKey] = [];
                }

                // Thêm từng phiên sử dụng vào mảng
                $reports[$key]['rooms'][$roomKey][] = [
                    'ngay' => $record->ngay,
                    'giovao' => $record->giovao,
                    'giora' => $record->giora,
                    'mucdichsd' => $record->mucdichsd,
                    'tinhtrangtruoc' => $record->tinhtrangtruoc,
                    'tinhtrangsau' => $record->tinhtrangsau,
                    'giangvien' => $record->taikhoan->hoten ?? 'Không xác định',
                ];
            }

            return [
                'start_date' => $startOfLastWeek->format('d/m/Y'),
                'end_date' => $endOfLastWeek->format('d/m/Y'),
                'reports_by_gvql' => $reports,
            ];
        } catch (\Exception $e) {
            Log::error("Error generating weekly report: " . $e->getMessage());
            return null;
        }
    }

    public function createWordReport($reports, $gvqlEmail)
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        // Tiêu đề
        $section->addText('BÁO CÁO SỬ DỤNG PHÒNG MÁY TUẦN', ['bold' => true, 'size' => 16], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $section->addTextBreak(1);

        // Thông tin người nhận
        $section->addText("Kính gửi: {$reports['reports_by_gvql'][$gvqlEmail]['gvql_name']}");
        $section->addText("Thời gian: Từ {$reports['start_date']} đến {$reports['end_date']}");
        $section->addTextBreak(1);

        // Tạo bảng cho từng phòng
        foreach ($reports['reports_by_gvql'][$gvqlEmail]['rooms'] as $phongTen => $sessions) {
            $section->addText("Phòng máy: $phongTen", ['bold' => true]);

            $table = $section->addTable([
                'borderSize' => 6,
                'borderColor' => '000000',
                'cellMargin' => 50,
            ]);

            // Header
            $table->addRow();
            $table->addCell(Converter::cmToTwip(2))->addText('Ngày', ['bold' => true]);
            $table->addCell(Converter::cmToTwip(1.5))->addText('Giờ vào', ['bold' => true]);
            $table->addCell(Converter::cmToTwip(1.5))->addText('Giờ ra', ['bold' => true]);
            $table->addCell(Converter::cmToTwip(3))->addText('Môn học giảng dạy', ['bold' => true]);
            $table->addCell(Converter::cmToTwip(2.5))->addText('Tình trạng trước khi sử dụng', ['bold' => true]);
            $table->addCell(Converter::cmToTwip(2.5))->addText('Tình trạng sau khi sử dụng', ['bold' => true]);
            $table->addCell(Converter::cmToTwip(2.5))->addText('Giảng viên giảng dạy', ['bold' => true]);

            // Nội dung
            foreach ($sessions as $session) {
                $table->addRow();
                $table->addCell(Converter::cmToTwip(2))->addText($session['ngay']);
                $table->addCell(Converter::cmToTwip(1.5))->addText($session['giovao']);
                $table->addCell(Converter::cmToTwip(1.5))->addText($session['giora']);
                $table->addCell(Converter::cmToTwip(3))->addText($session['mucdichsd']);
                $table->addCell(Converter::cmToTwip(2.5))->addText($session['tinhtrangtruoc']);
                $table->addCell(Converter::cmToTwip(2.5))->addText($session['tinhtrangsau']);
                $table->addCell(Converter::cmToTwip(2.5))->addText($session['giangvien']);
            }

            $section->addTextBreak(1);
        }

        // Chữ ký
        $section->addText('Trân trọng,', [], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]);
        $section->addText('Hệ thống quản lý thiết bị', [], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]);

        // Lưu file
        $filePath = storage_path("app/public/weekly_report_{$gvqlEmail}.docx");
        $phpWord->save($filePath, 'Word2007');

        return $filePath;
    }
}
