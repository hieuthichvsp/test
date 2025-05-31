<?php

namespace App\Http\Controllers\danhmucmuasam;

use App\Http\Controllers\Controller;
use App\Models\DanhmucMuasam;
use App\Models\DeNghi;
use App\Models\Hocky;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MuaSamSuaChuaThuongXuyenController extends Controller
{
    public function index(Request $request)
    {
        // Lấy danh sách học kỳ
        // $dsHocKy = DB::select("
        //             SELECT * 
        //             FROM hocky, nam_hoc
        //             WHERE hoc_ky.id_n = nam_hoc.id_nh 
        //             ORDER BY hoc_ky.id_hk DESC ");
        $dsHocKy = Hocky::all()->sortByDesc('id');
        //Lấy trạng thái khóa
        // $isLocked = DB::table('danhmuc_muasam')
        //     ->where('id', 1)
        //     ->value('is_locked');
        $isLocked = DanhmucMuasam::find(1)->is_locked;

        // Lấy học kỳ hiện tại
        // $hocKyChon = $request->get('id_hk', config('custom.hoc_ky')); // Này tui lấy từ config.custom.hoc_ky ă
        $hockyCurrent = Hocky::where('current', 1)->first();
        if ($hockyCurrent == null) {
            return redirect()->back()->with([
                'error' => 'Không tìm thấy học kỳ hiện tại! Vui lòng liên hệ quản trị viên để khắc phục lỗi này.',
                'title' => 'Thông báo'
            ]);
        }
        // Lấy danh sách đề nghị
        $dsDeNghi = DeNghi::with(['taikhoan', 'hocky', 'danhmuc_muasam'])
            ->where('id_hocky', $hockyCurrent->id)
            ->where('id_danhmuc', 1) //1 : Mua sắm sửa chữa thường xuyên
            ->orderBy('id', 'desc')
            ->get();

        return view('quanlyfile.danhmucmuasam.index', compact('dsDeNghi', 'dsHocKy', 'hockyCurrent', 'isLocked'));
    }
}
