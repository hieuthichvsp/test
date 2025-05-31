<?php

namespace App\Http\Controllers\danhmucmuasam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DanhmucMuasam;

class DanhMucMuaSamController extends Controller
{
    public function lockDanhMuc(Request $request, $id)
    {
        $danhMuc = DanhmucMuasam::find($id);

        if ($danhMuc) {
            $danhMuc->lock_start_date = $request->lock_start_date;
            $danhMuc->lock_end_date = $request->lock_end_date;
            $danhMuc->is_locked = 1;

            $danhMuc->save();
            return redirect()->back()->with([
                'success' => 'Danh mục đã được khóa thành công!',
                'title' => 'Khóa danh mục'
            ]);
        } else {
            return redirect()->back()->with([
                'error' => 'Danh mục không tồn tại!',
                'title' => 'Khóa danh mục!'
            ]);
        }
    }

    public function unlockDanhMuc(Request $request, $id)
    {
        $danhMuc = DanhmucMuaSam::find($id);

        if ($danhMuc) {
            $danhMuc->is_locked = 0;
            $danhMuc->save();
            return redirect()->back()->with([
                'success' => 'Danh mục đã được mở khóa thành công!',
                'title' => 'Mở khóa danh mục'
            ]);
        } else {
            return redirect()->back()->with([
                'error' => 'Danh mục không tồn tại!',
                'title' => 'Mở khóa danh mục!'
            ]);
        }
    }
}
