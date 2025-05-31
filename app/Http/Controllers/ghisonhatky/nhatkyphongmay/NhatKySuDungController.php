<?php

namespace App\Http\Controllers\ghisonhatky\nhatkyphongmay;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\Nhatkyphongmay;
use App\Models\TaiKhoan;
use App\Models\Hocky;
use App\Models\PhongKho;
use App\Models\Maymocthietbi;
use Illuminate\Support\Arr;
use App\Models\Loaimaymocthietbi;
use App\Models\Thongkemaymoc;
use App\Models\Tinhtrangthietbi;
use Yajra\DataTables\Facades\DataTables;



class NhatKySuDungController extends Controller
{
    // public function index()
    // {
    //     // Logic to retrieve and pass data to the index view for NhatKySuDung
    //     $nhatkys = Nhatkyphongmay::with([
    //         'phong_kho',
    //         'taikhoan',
    //         'hocky'
    //     ])->get();
    //     $phongmays = PhongKho::all()->sortBy('maphong');
    //     $taikhoans = Taikhoan::all();
    //     $hockys = Hocky::all()->sortByDesc('id');
    //     $hockysCurrent = Hocky::where('current', 1)->first();
    //     // Logic to retrieve and pass data to the index view for DanhSachThietBi
    //     $tinhtrangs = Tinhtrangthietbi::all();
    //     $loaitbs = Loaimaymocthietbi::all();
    //     $namsds = Maymocthietbi::select('namsd')
    //         ->orderBy('namsd', 'desc')
    //         ->distinct()
    //         ->get();
    //     $nguongocs = Maymocthietbi::select('nguongoc')
    //         ->where('nguongoc', '!=', '')
    //         ->where('nguongoc', '!=', null)
    //         ->orderBy('nguongoc', 'desc')
    //         ->distinct()
    //         ->get();
    //     $gias = [
    //         '1' => 'Dưới 1 triệu',
    //         '2' => 'Từ 1 triệu đến 5 triệu',
    //         '3' => 'Từ 5 triệu đến 10 triệu',
    //         '4' => 'Từ 10 triệu đến 20 triệu',
    //         '5' => 'Trên 20 triệu'
    //     ];
    //     $chatluongs = [
    //         '1' => '0-20%',
    //         '2' => '21-40%',
    //         '3' => '41-60%',
    //         '4' => '61-80%',
    //         '5' => '81-100%'
    //     ];
    //     // Logic to display the index view for NhatKyPhongMay
    //     return view('ghisonhatky.nhatkyphongmay.index', compact('nhatkys', 'phongmays', 'taikhoans', 'hockys', 'hockysCurrent', 'tinhtrangs', 'loaitbs', 'namsds', 'nguongocs', 'gias', 'chatluongs'));
    // }
    public function storeNew(Request $request)
    {
        try {
            $validate = $request->validate([
                'maphong' => 'integer',
                'mahocky' => 'integer',
                'matk' => 'nullable|integer',
                'ngay' => 'date',
                'giovao' => 'string|max:255',
                'giora' => 'string|max:255',
                'mucdichsd' => 'string|max:255',
                'tinhtrangtruoc' => 'string|max:255',
                'tinhtrangsau' => 'string|max:255',
            ]);
            $ngayTao = time();
            $dataSave = Arr::only($validate, ["maphong", "matk", "mahocky", "ngay", "giovao", "giora", "mucdichsd", "tinhtrangtruoc", "tinhtrangsau"]);
            $dataSave['ngaytao'] = $ngayTao;
            Nhatkyphongmay::create($dataSave);
            return redirect()->back()->with([
                'success' => 'Thêm nhật ký phòng máy thành công',
                'title' => 'Thêm nhật ký phòng máy',
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating NhatKyPhongMay: ' . $e->getMessage());
            return redirect()->back()->with([
                'error' => 'Đã xảy ra lỗi trong quá trình thêm nhật ký.',
                'title' => 'Thêm nhật ký phòng máy',
            ]);
        }
    }
    public function edit($id)
    {
        $nhatky = Nhatkyphongmay::find($id);
        if (request()->ajax()) {
            return response()->json([
                'nhatky' => $nhatky
            ]);
        }
        return view('ghisonhatky.nhatkyphongmay.nhatkysudung.partials.modals', compact('nhatky'));
    }
    public function update(Request $request, $id)
    {
        try {
            $nhatky = Nhatkyphongmay::findOrFail($id);
            if (!$nhatky) {
                return redirect()->back()->with([
                    'error' => 'Đơn vị không tồn tại!',
                    'title' => 'Cập nhật nhật ký phòng máy'
                ]);
            }
            $validate = $request->validate([
                'maphong' => 'require|integer',
                'giovao' => 'string|max:255',
                'giora' => 'string|max:255',
                'mucdichsd' => 'string|max:255',
                'tinhtrangtruoc' => 'string|max:255',
                'tinhtrangsau' => 'string|max:255',
            ]);
            //update từng các trường của đối tượng $nhatky bằng giá trị mới từ request
            $nhatky->update($validate);
            return redirect()->back()->with([
                'success' => 'Cập nhật nhật ký phòng máy thành công.',
                'title' => 'Cập nhật nhật ký phòng máy'
            ]);
        } catch (\Exception $e) {
            Log::chanel('query')->error('Error updating NhatKyPhongMay: ' . $e->getMessage());
            return redirect()->back()->with([
                'error' => 'Đã xảy ra lỗi trong quá trình cập nhật.',
                'title' => 'Cập nhật nhật ký phòng máy'
            ]);
        }
    }
    public function destroy($id)
    {
        try {
            $nhatky = Nhatkyphongmay::findOrFail($id);
            $nhatky->delete();
            return redirect()->back()
                ->with([
                    'success' => 'Xóa nhật ký phòng máy thành công.',
                    'title' => 'Xóa nhật ký phòng máy'
                ]);
        } catch (\Exception $e) {
            Log::error('Error deleting NhatKyPhongMay: ' . $e->getMessage());
            return  redirect()->back()
                ->with([
                    'error' => 'Đã xảy ra lỗi trong quá trình xóa nhật ký.',
                    'title' => 'Xóa nhật ký phòng máy',
                ]);
        }
    }
    public function loadTable(Request $request)
    {
        $idphong = $request->input('phong_id');
        $idhocky = $request->input('hocky_id');
        $data = Nhatkyphongmay::with(['taikhoan',])
            ->where('maphong', $idphong)
            ->where('mahocky', $idhocky)->get();
        return response()->json([
            'data' => $data->map(function ($item) {
                return [
                    'id' => $item->id,
                    'ngay' => date('d/m/Y', strtotime($item->ngay)),
                    'giovao' => $item->giovao ?? '',
                    'giora' => $item->giora ?? '',
                    'mucdichsd' => $item->mucdichsd ?? '',
                    'tinhtrangtruocsd' => $item->tinhtrangtruoc ?? '',
                    'tinhtrangsausd' => $item->tinhtrangsau ?? '',
                    'giaoviensd' => $item->taikhoan->hoten ?? 'Chưa có',
                ];
            })
        ]);
    }

    public function updateStatusPC(Request $request, $idtb)
    {
        try {
            $tb = Maymocthietbi::with(['tinhtrangthietbi'])->find($idtb);
            if (!$tb) {
                return response()->json([
                    "message" => 'Thiết bị không tồn tại.'
                ]);
            }
            $tb->matinhtrang = $request->input('tinhtrang');
            $tb->ghichu = $request->input('ghichu');
            $tb->save();
            if ($tb->matinhtrang == 5) { // Trạng thái hư hỏng
                $gvql = PhongKho::find($tb->maphongkho);
                $this->sendHuHongEmail($tb->phong_kho, $tb, $gvql->taikhoan);
                return redirect()->back()->with([
                    'success' => "Đã gửi email thông báo đến GVQL.",
                    'title' => 'Cập nhật trạng thái thiết bị',
                ]);
                // return response()->json([
                //     'maphong' => $tb->phong_kho,
                //     'tb' => $tb,
                //     "gvql" => $gvql->taikhoan
                // ]);
            }
            return redirect()->back()->with([
                'success' => "Trạng thái đã được cập nhật.",
                'title' => "Cập nhật trạng thái thiết bị",
                'flag' => 0
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating status PC: ' . $e->getMessage());
            return redirect()->back()->with([
                'error' =>  $e->getMessage(),
                'title' => "Lỗi nhập liệu",
                'flag' => -1
            ]);
        }
    }
    public function sendHuHongEmail($phong, $thietbi, $gvql)
    {
        $data = [
            'hoten' => $gvql->hoten,
            'tenphong' => $phong->tenphong,
            'tentb' => $thietbi->tentb,
            'matb' => $thietbi->id,
            'somay' => $thietbi->somay,
            'mota' => $thietbi->mota,
            'ghichu' => $thietbi->ghichu,
            'tinhtrang' => $thietbi->tinhtrangthietbi->tinhtrang,
        ];

        Mail::send('emails.sendStatusPC', $data, function ($message) use ($gvql) {
            $message->to($gvql->email)
                ->subject('Thông báo thiết bị hư hỏng');
        });
    }
}
