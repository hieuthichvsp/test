<?php

namespace App\Http\Controllers\ghisonhatky\nhatkyphongmay;

use App\Http\Controllers\Controller;
use App\Models\Nhatkyphongmay;
use App\Models\TaiKhoan;
use App\Models\Hocky;
use App\Models\PhongKho;
use App\Models\Maymocthietbi;
use App\Models\Loaimaymocthietbi;
use App\Models\Tinhtrangthietbi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NhatKyPhongMayController extends Controller
{
    public function index()
    {
        // Logic to retrieve and pass data to the index view for NhatKySuDung
        $nhatkys = Nhatkyphongmay::with([
            'phong_kho',
            'taikhoan',
            'hocky'
        ])->get();
        $phongmays = PhongKho::all()->sortBy('maphong');
        $taikhoans = Taikhoan::all();
        $hockys = Hocky::all()->sortByDesc('id');
        $hockysCurrent = Hocky::where('current', 1)->first();
        // Logic to retrieve and pass data to the index view for DanhSachThietBi
        $tinhtrangs = Tinhtrangthietbi::all();
        $loaitbs = Loaimaymocthietbi::all();
        $namsds = Maymocthietbi::select('namsd')
            ->orderBy('namsd', 'desc')
            ->distinct()
            ->get();
        $nguongocs = Maymocthietbi::select('nguongoc')
            ->where('nguongoc', '!=', '')
            ->where('nguongoc', '!=', null)
            ->orderBy('nguongoc', 'desc')
            ->distinct()
            ->get();
        $gias = [
            '1' => 'Dưới 1 triệu',
            '2' => 'Từ 1 triệu đến 5 triệu',
            '3' => 'Từ 5 triệu đến 10 triệu',
            '4' => 'Từ 10 triệu đến 20 triệu',
            '5' => 'Trên 20 triệu'
        ];
        $chatluongs = [
            '1' => '0-20%',
            '2' => '21-40%',
            '3' => '41-60%',
            '4' => '61-80%',
            '5' => '81-100%'
        ];
        // Logic to retrieve and pass data to the index view for BaoTriSuaChua
        // Logic to display the index view for NhatKyPhongMay
        return view('ghisonhatky.nhatkyphongmay.index', compact('nhatkys', 'phongmays', 'taikhoans', 'hockys', 'hockysCurrent', 'tinhtrangs', 'loaitbs', 'namsds', 'nguongocs', 'gias', 'chatluongs'));
    }
    public function getMayMocByPhong($idphong)
    {
        $mays = Maymocthietbi::whereNotNull('somay')
            ->where('maphongkho', $idphong)->get()->toArray();
        return $mays;
    }
    public function getGVQL($idphong)
    {
        try {
            $phong = PhongKho::with(['taikhoan'])->find($idphong);
            if ($phong) {
                $tengvql = $phong->taikhoan->hoten;
                if (!$tengvql) {
                    return null;
                }
                return $tengvql;
            }
        } catch (\Exception $e) {
            Log::error('Error fetching GVQL: ' . $e->getMessage());
            return null;
        }
    }
    public function searchPhongMay(Request $request)
    {
        $search = $request->input('q');
        $result = PhongKho::where('maphong', 'LIKE', $search . '%')
            ->select('id', 'tenphong', 'maphong')
            ->limit(10)
            ->get();
        if ($result->isEmpty()) {
            return redirect()->back()->with([
                "error" => 'Không tìm thấy',
                "title" => 'Tìm kiếm phòng máy'
            ]);
        }
        $response = array();
        foreach ($result as $phong) {
            $id = $phong->id;
            $tenphong = $phong->tenphong;
            $maphong = $phong->maphong;
            $mays = $this->getMayMocByPhong($id);
            $tengvql = $this->getGVQL($id);
            $response[] = array(
                "id" => $id,
                "maphong" => $maphong,
                "tenphong" => $tenphong,
                "mays" => $mays,
                "tengvql" => $tengvql,
            );
        }
        // Log::info('Response: ' . json_encode($response));
        return response()->json($response);
    }
    public function loadMachines(Request $request)
    {
        $phong = $request->input('idphong');
        $result = PhongKho::where('id', $phong)->get();
        if ($result->count() == 0) {
            return;
        }
        $response = array();
        foreach ($result as $phong) {
            $id = $phong->id;
            $mays = $this->getMayMocByPhong($id);
            $response[] = array(
                "mays" => $mays,
            );
        }
        // Log::info('Response: ' . json_encode($response));
        return response()->json($response);
    }
}
