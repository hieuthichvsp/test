<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function mapFields(array $input, $tail)
    {
        $mapped = [];
        foreach ($input as $key => $value) {
            if (str_ends_with($key, $tail)) {
                $dbField = str_replace($tail, '', $key);
                $mapped[$dbField] = $value;
            }
        }
        return $mapped;
    }
    protected $errorInsert = "Có lỗi xảy ra khi thêm!. Vui lòng thử lại";
    protected $errorUpdate = "Có lỗi xảy ra khi cập nhật!. Vui lòng thử lại";
    protected $errorDelete = "Có lỗi xảy ra khi xóa!. Vui lòng thử lại";
    protected $errorImport = "Có lỗi xảy ra khi thêm dữ liệu bằng excel!. Vui lòng thử lại";
    protected $noRecord = "Không tìm thấy bản ghi cần";
}
