<?php

namespace App\Http\Controllers\ghisonhatky;

use App\Http\Controllers\Controller;
use App\Models\Hocky;
use App\Models\Maymocthietbi;
use App\Models\PhongKho;
use App\Models\Soquanlykho;
use App\Models\Taikhoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class SoKhoController extends Controller
{
    public function index()
    {
        $hockys = Hocky::all()->sortByDesc('id');
        $hockysCurrent = Hocky::where('current', 1)->first();
        $phongmays = PhongKho::all();
        $taikhoans = Taikhoan::all();
        return view('ghisonhatky.quanlysokho.index', compact(['hockys', 'hockysCurrent', 'phongmays', 'taikhoans']));
    }
    public function filter(Request $request)
    {
        // Validate các tham số đầu vào
        $hocky = $request->input('hocky_id');
        $phong = $request->input('phong_id');
        $query = DB::table('soquanlykho as sqlk')
            ->select(
                'sqlk.*',
                'mmtb.tentb',
                'mmtb.maso',
                'a.hoten as giangvien_hoten',
            )
            ->leftJoin('taikhoan as a', 'sqlk.matk', '=', 'a.id')
            ->join('maymocthietbi as mmtb', 'sqlk.matb', '=', 'mmtb.id')
            ->join('phong_kho as p', 'sqlk.maphong', '=', 'p.id')
            ->join('hocky as hk', 'sqlk.mahocky', '=', 'hk.id')
            ->where('sqlk.maphong', $phong)
            ->where('sqlk.mahocky', $hocky);
        // Log::info('Data: ' . $query->get());
        return DataTables::of($query)
            ->setTransformer(function ($item) {
                return [
                    'id' => $item->id,
                    'tentb' => $item->maso . '-' . $item->tentb ?? '',
                    'ngaymuon' => date('d/m/Y', strtotime($item->ngaymuon)),
                    'ngaytra' => date('d/m/Y', strtotime($item->ngaytra)),
                    'mucdichsd' => $item->mucdichsd ?? '',
                    'tinhtrangtruocsd' => $item->tinhtrangtruoc ?? '',
                    'tinhtrangsausd' => $item->tinhtrangsau ?? '',
                    'giaoviensd' => $item->giangvien_hoten ?? 'Không có'
                ];
            })
            ->toJson();
    }
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'ngaymuon-edit' => 'required|date',
                'ngaytra-edit' => 'required|date',
                'mucdichsd-edit' => 'nullable|string|max:1000',
                'tinhtrangtruoc-edit' => 'nullable|string|max:1000',
                'tinhtrangsau-edit' => 'nullable|string|max:1000'
            ]);
            $validated['ngaycapnhat'] = time();
            $baotri = Soquanlykho::find($id);
            if ($baotri) {
                $baotri->ngaymuon = $validated['ngaymuon-edit'];
                $baotri->ngaytra = $validated['ngaytra-edit'];
                $baotri->mucdichsd = $validated['mucdichsd-edit'] ?? null;
                $baotri->tinhtrangtruoc = $validated['tinhtrangtruoc-edit'] ?? null;
                $baotri->tinhtrangsau = $validated['tinhtrangsau-edit'] ?? null;
                $baotri->ngaycapnhat = $validated['ngaycapnhat'];
                $baotri->save();
                return  redirect()->back()->with(['success' => 'Cập nhật ký kho thành công', 'title' => 'Cập nhật ký kho']);
            } else {
                return  redirect()->back()->with(['error' => $this->noRecord . ' cập nhật', 'title' => 'Cập nhật ký kho']);
            }
        } catch (\Exception $e) {
            Log::channel('query')->error('Error updating Soquanlykho ' . $e->getMessage());
            return redirect()->back()->with(['error' => $this->errorUpdate, 'title' => 'Cập nhật ký kho']);
        }
    }
    public function destroy($id)
    {
        try {
            $baotri = Soquanlykho::find($id);
            if ($baotri) {
                $baotri->delete();
                return redirect()->back()->with(['success' => 'Xóa thành công', 'title' => 'Xóa nhật ký kho']);
            } else {
                return redirect()->back()->with(['error' => $this->noRecord . ' xóa', 'title' => 'Xóa nhật ký kho']);
            }
        } catch (\Exception $e) {
            Log::channel('query')->error('Error deleting Soquanlykho ' . $e->getMessage());
            return redirect()->back()->with(['error' => $this->errorDelete, 'title' => 'Xóa nhật ký kho']);
        }
    }
    public function store(Request $request)
    {
        try {
            $validate = $request->validate([
                'maphong-add' => 'required|integer',
                'matk-add' => 'required|integer',
                'mahocky-add' => 'required|integer',
                'matb-add' => 'required|integer',
                'ngaymuon-add' => 'required|date',
                'ngaytra-add' => 'required|date',
                'mucdichsd-add' => 'nullable|string|max:1000',
                'tinhtrangtruoc-add' => 'nullable|string|max:1000',
                'tinhtrangsau-add' => 'nullable|string|max:1000'
            ]);
            $ngaycapnhat = time();
            $ngaytao = time();
            Soquanlykho::create([
                'matb' => $validate['matb-add'],
                'maphong' => $validate['maphong-add'],
                'mahocky' => $validate['mahocky-add'],
                'mucdichsd' => $validate['mucdichsd-add'],
                'ngaymuon' => date('Y-m-d', strtotime($validate['ngaymuon-add'])),
                'ngaytra' => date('Y-m-d', strtotime($validate['ngaytra-add'])),
                'tinhtrangtruoc' => $validate['tinhtrangtruoc-add'],
                'tinhtrangsau' => $validate['tinhtrangsau-add'],
                'ngaytao' => $ngaytao,
                'ngaycapnhat' => $ngaycapnhat,
                'matk' => $validate['matk-add']
            ]);

            return redirect()->back()->with(['success' => 'Thêm nhật ký kho thành công.', 'title' => 'Thêm nhật ký kho']);
        } catch (\Exception $e) {
            Log::channel('query')->error("Error inserting Soquanlykho: " . $e->getMessage());
            return redirect()->back()->with(['error' => $this->errorInsert, 'title' => 'Thêm nhật ký kho']);
        }
    }
    public function getListDevicesByRoom($phong_id)
    {
        $listDevices = Maymocthietbi::select('id', 'tentb')
            ->where('maphongkho', $phong_id)
            ->get();
        return response()->json(['listDevices' => $listDevices]);
    }
}
