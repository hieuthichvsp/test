<?php

namespace App\Imports;

use App\Models\Donvi;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithStartRow;

class DonViImport implements ToModel, WithStartRow
{
    protected $loaidonvi;
    protected $inserted = 0;

    public function startRow(): int
    {
        return 2;
    }

    public function __construct($loaidonvi,)
    {
        $this->loaidonvi = $loaidonvi;
    }

    public function model(array $row)
    {

        // Lấy dữ liệu theo thứ tự cột
        $tendonvi = trim($row[0] ?? '');
        $tenviettat = trim($row[1] ?? '');
        $loaidonvi = $this->loaidonvi;

        // Kiểm tra dữ liệu bắt buộc
        if (empty($tendonvi) || empty($tenviettat) || !$loaidonvi) {
            Log::warning('Thiếu dữ liệu: ' . json_encode($row));
            return null;
        }

        // Kiểm tra phòng đã tồn tại
        $existingPhong = Donvi::where('tendonvi', $tendonvi)
            ->orWhere('tenviettat', $tenviettat)
            ->first();

        if ($existingPhong) {
            Log::channel('import')->info('Đơn vị đã tồn tại, bỏ qua: ' . $tendonvi);
            return null;
        }
        // Tạo mới đơn vị
        $this->inserted++;
        Log::channel('import')->info($tendonvi . ' ' . $tenviettat . ' ' . $loaidonvi->id);
        return new Donvi([
            'tendonvi' => $tendonvi,
            'tenviettat' => $tenviettat,
            'maloai' => $loaidonvi->id
        ]);
    }
    public function getInsertedCount(): int
    {
        return $this->inserted;
    }
}
