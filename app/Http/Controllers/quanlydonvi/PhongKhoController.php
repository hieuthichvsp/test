<?php

namespace App\Http\Controllers\quanlydonvi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PhongKho;
use App\Models\Donvi;
use App\Models\Taikhoan;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PhongKhoImport;
use Illuminate\Support\Facades\Log;

class PhongKhoController extends Controller
{
    //
    public function index()
    {
        $phongkhos = PhongKho::with(['donvi', 'taikhoan'])->get();
        $donvis = Donvi::all();
        $magvqls = Taikhoan::all();
        return view('quanlydonvi.phongkho.index', compact('phongkhos', 'donvis', 'magvqls'));
    }
    public function store(Request $request)
    {
        try {
            $validate = $request->validate([
                'tenphong-add' => 'nullable|string|max:255|unique:phongkho,tenphong',
                'maphong-add' => 'required|string|max:30|unique:phongkho,maphong',
                'khu-add' => 'nullable|string|max:10',
                'lau-add' => 'nullable|integer',
                'sophong-add' => 'nullable|integer',
                'magvql-add' => 'required|integer',
                'madonvi-add' => 'nullable|integer',
            ]);
            $data = $this->mapFields($validate, '-add');
            PhongKho::create($data);
            return redirect()->back()->with([
                'success' => 'Thêm phòng kho thành công.',
                'title' => 'Thêm phòng kho'
            ]);
        } catch (\Exception $e) {
            Log::channel('query')->error('Error inserting phongkho: ' . $e->getMessage());
            return redirect()->back()->with([
                'error' => $this->errorInsert,
                'title' => 'Thêm phòng kho'
            ]);
        }
    }
    public function edit($id)
    {
        $phongkho = PhongKho::find($id);
        $donvi = Donvi::all();
        $magvql = Taikhoan::all();
        if (request()->ajax()) {
            return response()->json([
                'phongkho' => $phongkho,
                'donvi' => $donvi,
                'magvql' => $magvql
            ]);
        }
        return view('quanlydonvi.phongkho.modals', compact('phongkho', 'donvi', 'magvql'));
    }
    public function update(Request $request, $id)
    {
        try {
            $phongkho = PhongKho::find($id);
            if (!$phongkho)
                return redirect()->back()->with([
                    'warning' => $this->noRecord . ' cập nhật',
                    'title' => 'Cập nhật phòng kho'
                ]);
            $validate = $request->validate([
                'maphong-edit' => 'required|string|max:30|unique:phongkho,maphong,' . $id,
                'tenphong-edit' => 'nullable|string|max:255|unique:phongkho,tenphong,' . $id,
                'khu-edit' => 'nullable|string|max:10',
                'lau-edit' => 'nullable|integer',
                'sophong-edit' => 'nullable|integer',
                'magvql-edit' => 'required|integer',
                'madonvi-edit' => 'required|integer',
            ]);
            $data = $this->mapFields($validate, '-edit');
            $phongkho->update($data);
            return redirect()->back()->with([
                'success' => 'Cập nhật phòng kho thành công.',
                'title' => 'Cập nhật phòng kho'
            ]);
        } catch (\Exception $e) {
            Log::channel('query')->error('Error updating phongkho: ' . $e->getMessage());
            return redirect()->back()->with([
                'error' => $this->errorUpdate,
                'title' => 'Cập nhật phòng kho'
            ]);
        }
    }
    public function destroy($id)
    {
        try {
            $phongkho = PhongKho::find($id);
            if (!$phongkho) {
                return redirect()->back()->with([
                    'warning' => $this->noRecord . ' xóa',
                    'title' => 'Xóa phòng kho'
                ]);
            }
            $phongkho->delete();
            return redirect()->back()->with([
                'success' => 'Xóa phòng kho thành công.',
                'title' => 'Xóa phòng kho'
            ]);
        } catch (\Exception $e) {
            Log::channel('query')->error('Error deleting phongkho: ' . $e->getMessage());
            return redirect()->back()->with([
                'error' => $this->errorDelete,
                'title' => 'Xóa phòng kho'
            ]);
        }
    }
    public function getPhongKhoByDonVi($madonvi)
    {
        $phongkhos = PhongKho::where('madonvi', $madonvi)->get();
        return response()->json($phongkhos);
    }
    public function getGVQLByDonVi($madonvi)
    {
        $gvqls = Taikhoan::where('madonvi', $madonvi)->get();
        return response()->json($gvqls);
    }
    public function import(Request $request)
    {
        try {
            $file = $request->file('file');
            if ($file) {
                $donviId = $request->input('donvi');
                $gvqlId = $request->input('gvql');
                $donvi = Donvi::find($donviId);
                $gvql = Taikhoan::find($gvqlId);
                $result = new PhongKhoImport($donvi, $gvql);
                Excel::import($result, $file);
                if ($result->getInsertedCount() == 0) {
                    return redirect()->back()->with([
                        'warning' => 'Không có dữ liệu được thêm mới (có thể do trùng hoặc lỗi dữ liệu)!',
                        'title' => 'Nhập dữ liệu phòng!'
                    ]);
                }
                return redirect()->back()->with([
                    'success' => 'Đã thêm ' . $result->getInsertedCount() . ' phòng thành công!',
                    'title' => 'Nhập dữ liệu phòng!'
                ]);
            }
            return redirect()->back()->with(['error' => 'File không hợp lệ!', 'title' => 'Nhập dữ liệu phòng']);
        } catch (\Exception $e) {
            Log::channel('import')->error('Lỗi khi nhập dữ liệu từ Excel vào PhongKho: ' . $e->getMessage());
            return redirect()->back()->with(['error' => 'Có lỗi xảy ra khi nhập dữ liệu. Vui lòng thử lại sau!', 'title' => 'Nhập dữ liệu phòng']);
        }
    }
}
