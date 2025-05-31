<?php

namespace App\Http\Controllers\quanlydonvi;

use App\Http\Controllers\Controller;
use App\Models\Donvi;
use App\Models\Loaidonvi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DonViImport;

class DonViController extends Controller
{
    public function index()
    {
        // Lấy danh sách đơn vị
        $donvis = Donvi::with('loaidonvi')->get();
        // Lấy danh sách loại đơn vị
        $loaidonvi = Loaidonvi::all();
        // Truyền dữ liệu vào view
        return view('quanlydonvi.donvi.index', compact('donvis', 'loaidonvi'));
    }

    public function store(Request $request)
    {
        try {
            //Kiểm tra dữ liệu đầu vào
            $validate = $request->validate([
                'tendonvi-add' => 'required|string|max:255|unique:donvi,tendonvi',
                'tenviettat-add' => 'required|string|max:255|unique:donvi,tenviettat',
                'maloai-add' => 'required|integer|max:255',
            ]);
            $data = $this->mapFields($validate, '-add');
            // Thêm đơn vị
            Donvi::create($data);
            return redirect()->back()->with([
                "success" => "Thêm đơn vị thành công.",
                "title" => "Thêm đơn vị"
            ]);
        } catch (\Exception $e) {
            // Xử lý lỗi ngoại lệ
            Log::channel('query')->error('Error inserting donvi: ' . $e->getMessage());
            return redirect()->back()->with([
                "error" => $this->errorInsert,
                "title" => "Thêm đơn vị"
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Kiểm tra đơn vị theo ID
            $donvi = Donvi::find($id);
            if (!$donvi) {
                return redirect()->back()->with([
                    'warning' => $this->noRecord . ' cập nhật',
                    'title' => 'Cập nhật đơn vị'
                ]);
            }
            $validate = $request->validate([
                'tendonvi-edit' => 'required|string|max:100|unique:donvi,tendonvi,' . $id,
                'tenviettat-edit' => 'required|string|max:20|unique:donvi,tenviettat,' . $id,
                'maloai-edit' => 'required|integer',
            ]);
            $data = $this->mapFields($validate, '-edit');
            $donvi->update($data);
            // Cập nhật thành công
            return redirect()->back()->with([
                "success" => "Đơn vị đã được cập nhật thành công.",
                "title" => "Cập nhật đơn vị"
            ]);
        } catch (\Exception $e) {
            Log::channel('query')->error('Error updating donvi: ' . $e->getMessage());
            return redirect()->back()->with([
                "error" => $this->errorUpdate,
                "title" => "Cập nhật đơn vị"
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $donvi = Donvi::find($id);
            if (!$donvi) {
                return redirect()->back()->with([
                    "warning" => $this->noRecord . ' xóa',
                    "title" => "Xóa đơn vị"
                ]);
            }
            $donvi->delete();
            return redirect()->back()->with([
                "success" => "Xóa đơn vị thành công.",
                "title" => "Xóa đơn vị"
            ]);
        } catch (\Exception $e) {
            Log::channel('query')->error('Error deleting donvi: ' . $e->getMessage());
            return redirect()->back()->with([
                "error" => $this->errorDelete,
                "title" => "Xóa đơn vị"
            ]);;
        }
    }
    public function import(Request $request)
    {
        try {
            $file = $request->file('file');
            if ($file) {
                $loaidonviId = $request->input('tenloai');
                $loaidonvi = Loaidonvi::find($loaidonviId);
                $result = new DonViImport($loaidonvi);
                Excel::import($result, $file);
                if ($result->getInsertedCount() == 0) {
                    return redirect()->back()->with([
                        'warning' => 'Không có dữ liệu được thêm mới (có thể do trùng hoặc lỗi dữ liệu)!',
                        'title' => 'Nhập dữ liệu đơn vị!'
                    ]);
                }
                return redirect()->back()->with([
                    'success' => 'Đã thêm ' . $result->getInsertedCount() . ' đơn vị thành công!',
                    'title' => 'Nhập dữ liệu đơn vị!'
                ]);
            }
            return redirect()->back()->with(['error' => 'File không hợp lệ!', 'title' => 'Nhập dữ liệu đơn vị']);
        } catch (\Exception $e) {
            Log::channel('import')->error('Lỗi khi nhập dữ liệu từ Excel vào Donvi: ' . $e->getMessage());
            return redirect()->back()->with(['error' => $this->errorImport, 'title' => 'Nhập dữ liệu đơn vị']);
        }
    }
}
