<?php

namespace App\Http\Controllers\capphatvattu;

use App\Http\Controllers\Controller;
use App\Models\Capphat;
use App\Models\Donvitinh;
use App\Models\Hocky;
use App\Models\Hocphan;
use App\Models\Vattu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class QuanLyVatTuController extends Controller
{
    public function index()
    {
        $hockies = Hocky::orderBy('dennam', 'desc')->get();
        $hocphans = Hocphan::all();
        $dvts = Donvitinh::all();
        $hockyCurrent = Hocky::where('current', '1')->first();
        return view('quanlyvattu.index', compact(['hockies', 'hocphans', 'hockyCurrent', 'dvts']));
    }
    public function filter(Request $request)
    {
        $hocky_id = $request->input('hocky_id');
        $hocphan_id = $request->input('hocphan_id');
        $capphat = Vattu::with(['hocky', 'hocphan', 'donvitinh'])
            ->where('hocky_id', $hocky_id)
            ->where('hocphan_id', $hocphan_id)
            ->get();
        return response()->json(['data' => $capphat]);
    }
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
    public function store(Request $request)
    {
        try {
            $validate = $request->validate([
                'ten-add' => 'required|string|max:255',
                'soluong-add' => 'nullable|integer|min:0',
                'dvt_id-add' => 'nullable|integer',
                'dongia-add' => 'nullable|integer',
                'khaitoan-add' => 'nullable|integer',
                'dinhmuc-add' => 'nullable|string|max:255',
                'mahieu-add' => 'nullable|string|max:255',
                'nhanhieu-add' => 'nullable|string|max:255',
                'xuatxu-add' => 'nullable|string|max:100',
                'namsx-add' => 'nullable|integer|min:1900|max:' . date('Y'),
                'hangsx-add' => 'nullable|string|max:255',
                'cauhinh-add' => 'nullable|string|max:1000',
                'hocky_id-add' => 'required|integer',
                'hocphan_id-add' => 'required|integer',
            ]);
            $data = $this->mapFields($validate, '-add');
            Vattu::create($data);
            return redirect()->back()->with(['success' => 'Thêm vật tư thành công!', 'title' => 'Thêm vật tư']);
        } catch (\Exception $e) {
            Log::channel('query')->error("Error inserting vattu: " . $e->getMessage());
            return redirect()->back()->with(['error' => 'Có lỗi xảy ra thêm!. Vui lòng thử lại', 'title' => 'Thêm vật tư']);
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $vattu = VatTu::findOrFail($id);
            if (!$vattu)
                return redirect()->back()->with(['error' => 'Không tìm thấy bản ghi cần cập nhật!', 'title' => 'Cập nhật vật tư']);
            $validate = $request->validate([
                'ten-edit' => 'required|string|max:255',
                'soluong-edit' => 'nullable|integer|min:0',
                'dvt_id-edit' => 'nullable|integer',
                'dongia-edit' => 'nullable|integer',
                'khaitoan-edit' => 'nullable|integer',
                'dinhmuc-edit' => 'nullable|string|max:255',
                'mahieu-edit' => 'nullable|string|max:255',
                'nhanhieu-edit' => 'nullable|string|max:255',
                'xuatxu-edit' => 'nullable|string|max:100',
                'namsx-edit' => 'nullable|integer|min:1900|max:' . date('Y'),
                'hangsx-edit' => 'nullable|string|max:255',
                'cauhinh-edit' => 'nullable|string|max:1000',
                'hocky_id-edit' => 'required|integer',
                'hocphan_id-edit' => 'required|integer',
            ]);
            $data = $this->mapFields($validate, '-edit');
            $vattu->update($data);
            return redirect()->back()->with(['success' => 'Cập nhật vật tư thành công!', 'title' => 'Cập nhật vật tư']);
        } catch (\Exception $e) {
            Log::channel('query')->error("Error updating vattu: " . $e->getMessage());
            return redirect()->back()->with(['error' => 'Có lỗi xảy ra cập nhật!. Vui lòng thử lại', 'title' => 'Cập nhật vật tư']);
        }
    }

    public function destroy($id)
    {
        try {
            $vattu = Vattu::find($id);
            if (!$vattu)
                return redirect()->back()->with(['error' => 'Không tìm thấy bản ghi cần xóa!', 'title' => 'Xóa vật tư']);
            $vattu->delete();
            return redirect()->back()->with(['success' => 'Xóa vật tư thành công!', 'title' => 'Xóa vật tư']);
        } catch (\Exception $e) {
            Log::channel('query')->error("Error deleting vattu: " . $e->getMessage());
            return redirect()->back()->with(['error' => 'Có lỗi xảy ra xóa!. Vui lòng thử lại', 'title' => 'Xóa vật tư']);
        }
    }
}
