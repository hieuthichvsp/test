<?php

namespace App\Http\Controllers\quanlyfilequytrinh;

use App\Http\Controllers\Controller;
use App\Models\DanhmucMuasam;
use Illuminate\Http\Request;
use App\Models\File;
use App\Models\FileLuuTru;
use App\Models\LoaiFileLuuTru;
use App\Models\ThongTinLuuTru;
use App\Models\DeNghi;
use App\Models\HocKy;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class QuanLyFileController extends Controller
{
    public function index($id)
    {
        $loaifile = LoaiFileLuuTru::all();
        $files = FileLuuTru::where('id_thongtinluutru', $id)->get();
        $thongtinluutrus = ThongTinLuuTru::with(['danhMuc', 'deNghi', 'hocKy', 'taiKhoan'])
            ->orderBy('created_at', 'desc')
            ->get();
        $danhmucs = DanhMucMuaSam::all();
        $denghis = DeNghi::all();
        $hockys = HocKy::all();
        $hockyCurrent = HocKy::where('current', 1)->first();
        $fileluutrus = FileLuuTru::all();
        $loaifile = LoaiFileLuuTru::all();
        return view('quanlyfilequytrinh.partials.danhsachfile', compact(
            'thongtinluutrus',
            'danhmucs',
            'denghis',
            'hockys',
            'hockyCurrent',
            'files',
            'loaifile'
        ));
    }
}
