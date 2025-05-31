<?php

namespace App\Imports;

use App\Models\PhongKho;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithStartRow;

class PhongKhoImport implements ToModel, WithStartRow
{
    protected $donvi;
    protected $gvql;
    protected $inserted = 0;

    public function startRow(): int
    {
        return 2;
    }

    public function __construct($donvi, $gvql)
    {
        $this->donvi = $donvi;
        $this->gvql = $gvql;
    }

    public function model(array $row)
    {

        // Lấy dữ liệu theo thứ tự cột
        $maPhong = trim($row[0] ?? '');
        $tenPhong = trim($row[1] ?? '');
        $khu = trim($row[2] ?? '');
        $lau = trim($row[3] ?? '');
        $soPhong = (int)trim($row[4] ?? '');
        $gvql = $this->gvql;
        $donvi = $this->donvi;

        // Kiểm tra dữ liệu bắt buộc
        if (empty($maPhong) || empty($tenPhong) || !$gvql || !$donvi) {
            Log::warning('Thiếu dữ liệu: ' . json_encode($row));
            return null;
        }

        // Kiểm tra phòng đã tồn tại
        $existingPhong = PhongKho::where('maphong', $maPhong)
            ->orWhere('tenphong', $tenPhong)
            ->first();

        if ($existingPhong) {
            Log::channel('import')->info('Phòng đã tồn tại, bỏ qua: ' . $maPhong);
            return null;
        }
        // Tạo mới phòng
        $this->inserted++;
        Log::channel('import')->info($maPhong . ' ' . $tenPhong . ' ' . $khu . ' ' . $lau . ' ' . $soPhong . ' ' . $gvql->id . ' ' . $donvi->id);
        // Trả về model để Laravel Excel tự save
        return new PhongKho([
            'maphong' => $maPhong,
            'tenphong' => $tenPhong,
            'khu' => $khu,
            'lau' => $lau,
            'sophong' => $soPhong,
            'magvql' => $gvql->id,
            'madonvi' => $donvi->id,
        ]);
    }
    public function getInsertedCount(): int
    {
        return $this->inserted;
    }
}
