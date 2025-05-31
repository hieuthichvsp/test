<?php

namespace App\Http\Controllers\ghisonhatky\nhatkyphongmay;

use App\Http\Controllers\Controller;
use App\Models\Maymocthietbi;
use App\Models\Nhatkybaotri;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class BaoTriSuaChuaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request)
    {
        // Validate các tham số đầu vào
        $hocky = $request->input('mahocky');
        $phong = $request->input('maphong');
        $query = DB::table('nhatkybaotri as nkb')
            ->select(
                'nkb.*',
                'mmtb.tentb',
                'a.hoten as nguoibaotri_hoten',
                'b.hoten as nguoikiemtra_hoten'
            )
            ->leftJoin('taikhoan as a', 'nkb.nguoibaotri', '=', 'a.id')
            ->leftJoin('taikhoan as b', 'nkb.nguoikiemtra', '=', 'b.id')
            ->join('maymocthietbi as mmtb', 'nkb.matb', '=', 'mmtb.id')
            ->join('phong_kho as p', 'nkb.maphong', '=', 'p.id')
            ->join('hocky as hk', 'nkb.mahocky', '=', 'hk.id')
            ->where('nkb.maphong', $phong)
            ->where('nkb.mahocky', $hocky);
        // Log::info('Data: ' . $query->get());
        return DataTables::of($query)
            ->setTransformer(function ($item) {
                return [
                    'id' => $item->id,
                    'tentb' => $item->tentb ?? '',
                    'matb' => $item->matb ?? '',
                    'ngaybaotri' => date('d/m/Y', strtotime($item->ngaybaotri)),
                    'motahuhong' => $item->motahuhong ?? '',
                    'noidungbaotri' => $item->noidungbaotri ?? '',
                    'nguoibaotri_id' => $item->nguoibaotri ?? '',
                    'nguoibaotri_hoten' => $item->nguoibaotri_hoten ?? '',
                    'nguoikiemtra_id' => $item->nguoikiemtra ?? '',
                    'nguoikiemtra_hoten' => $item->nguoikiemtra_hoten ?? '',
                    'ghichu' => $item->ghichu ?? '',
                    'ngaytao' => date('d/m/Y', $item->ngaytao),
                ];
            })
            ->toJson();
    }

    public function edit($id)
    {
        $baotri = Nhatkybaotri::find($id);
        if (request()->ajax()) {
            if ($baotri) {
                return response()->json(['baotri' => $baotri]);
            }
        }
    }
    protected function mapEditFields(array $input, $tail)
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
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'ngaybaotri-edit' => 'required|date',
                'motahuhong-edit' => 'required|string|max:500',
                'noidungbaotri-edit' => 'required|string|max:500',
                'nguoikiemtra-edit' => 'required|integer',
                'nguoibaotri-edit' => 'required|integer',
                'ghichu-edit' => 'nullable|string|max:1000',
                'user_update-edit' => 'nullable|integer'
            ]);
            $baotri = Nhatkybaotri::find($id);
            if ($baotri) {
                $baotri->update($this->mapEditFields($validated, '-edit'));
                return  redirect()->back()->with(['success' => 'Cập nhật ký bảo trì thành công', 'title' => 'Cập nhật ký bảo trì']);
            } else {
                return  redirect()->back()->with(['error' => 'Không tìm thấy bản ghi cần cập nhật!', 'title' => 'Cập nhật ký bảo trì']);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Cập nhật ký bảo trì thất bại!' . $e->getMessage(), 'title' => 'Cập nhật ký bảo trì']);
        }
    }
    public function destroy($id)
    {
        try {
            $baotri = Nhatkybaotri::find($id);
            if ($baotri) {
                $baotri->delete();
                return redirect()->back()->with(['success' => 'Xóa thành công', 'title' => 'Xóa nhật ký bảo trì']);
            } else {
                return redirect()->back()->with(['error' => 'Không tìm thấy nhật ký bảo trì', 'title' => 'Xóa nhật ký bảo trì']);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Xóa thất bại!' . $e->getMessage(), 'title' => 'Xóa nhật ký bảo trì']);
        }
    }
    public function store(Request $request)
    {
        try {
            $validate = $request->validate([
                'phong_id-add' => 'required|integer',
                'hocky_id-add' => 'required|integer',
                'dsThietBi' => 'required|array',
                'ngaybaotri-add' => 'required|date',
                'motahuhong-add' => 'required|string|max:500',
                'noidungbaotri-add' => 'required|string|max:500',
                'nguoibaotri-add' => 'required|integer',
                'nguoikiemtra-add' => 'required|integer',
                'ghichu-add' => 'nullable|string',
                'matk-add' => 'required|integer'
            ]);
            $ngaytao = time();
            foreach ($validate['dsThietBi'] as $idTB) {
                NhatKyBaoTri::create([
                    'matb' => $idTB,
                    'maphong' => $validate['phong_id-add'],
                    'mahocky' => $validate['hocky_id-add'],
                    'ngaybaotri' => date('Y-m-d', strtotime($validate['ngaybaotri-add'])),
                    'motahuhong' => $validate['motahuhong-add'],
                    'noidungbaotri' => $validate['noidungbaotri-add'],
                    'nguoibaotri' => $validate['nguoibaotri-add'],
                    'nguoikiemtra' => $validate['nguoikiemtra-add'],
                    'ghichu' => $validate['ghichu-add'],
                    'ngaytao' => $ngaytao,
                    'matk' => $validate['matk-add']
                ]);
            }
            return redirect()->back()->with(['success' => 'Đã thêm nhật ký bảo trì cho các thiết bị đã chọn.', 'title' => 'Thêm nhật ký bảo trì']);
        } catch (\Exception $e) {
            Log::channel('query')->error("Error inserting nhatkybaotri: " . $e->getMessage());
            return redirect()->back()->with(['error' => 'Lỗi nhập liệu!. Vui lòng thử lại.', 'title' => 'Thêm nhật ký bảo trì']);
        }
    }
    public function getListDevicesByRoom($phong_id)
    {
        $listDevices = Maymocthietbi::select('id', 'maso', 'tentb', 'mota')
            ->where('maphongkho', $phong_id)
            ->get();
        return response()->json(['listDevices' => $listDevices]);
    }
}
