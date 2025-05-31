<?php

namespace App\Http\Controllers\quanlytaikhoan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Loaitaikhoan;
use Illuminate\Support\Facades\Log;

class LoaiTaiKhoanController extends Controller
{
    public function index()
    {
        // Lấy danh sách loại tài khoản
        $loaitaikhoans = Loaitaikhoan::all();
        // Truyền dữ liệu vào view
        return view('quanlytaikhoan.loaitaikhoan.index', compact('loaitaikhoans'));
    }

    public function store(Request $request)
    {
        try {
            // Kiểm tra dữ liệu đầu vào
            $validate = $request->validate([
                'tenloai' => 'required|string|max:255|unique:loaitaikhoan,tenloai',
                'mota' => 'nullable|string|max:255',
            ]);

            // Thêm loại tài khoản
            $loaitaikhoan = Loaitaikhoan::create($validate);

            if (!$loaitaikhoan) {
                return redirect()->back()->with([
                    'error' => 'Đã xảy ra lỗi trong quá trình thêm loại tài khoản.',
                    'title' => 'Thêm loại tài khoản'
                ]);
            }
            return redirect()->back()->with([
                'success' => 'Thêm loại tài khoản thành công.',
                'title' => 'Thêm loại tài khoản'
            ]);
        } catch (\Exception $e) {
            //Xử lý lỗi ngoại lệ
            Log::channel('query')->error("Error create loaitaikhoan: " . $e->getMessage());
            return redirect()->back()->with([
                'error' => 'Lỗi nhập liệu!. Vui lòng thử lại.',
                'title' => 'Thêm loại tài khoản'
            ]);
        }
    }
    public function edit($id)
    {
        // Tìm loại tài khoản theo ID
        $loaitaikhoan = Loaitaikhoan::find($id);
        if (request()->ajax()) {
            return response()->json([
                'loaitaikhoan' => $loaitaikhoan,
            ]);
        }
        return view('quanlytaikhoan.loaitaikhoan.partials.modals', compact('loaitaikhoan'));
    }
    public function update(Request $request, $id)
    {
        try {
            // Kiểm tra dữ liệu đầu vào
            $validate = $request->validate([
                'tenloai' => 'required|string|max:255|unique:loaitaikhoan,tenloai,' . $id,
                'mota' => 'nullable|string|max:255',
            ]);

            // Tìm loại tài khoản cần cập nhật
            $loaitaikhoan = Loaitaikhoan::find($id);
            if (!$loaitaikhoan) {
                return redirect()->back()->with([
                    'error' => 'Đã xảy ra lỗi trong quá trình cập nhật loại tài khoản.',
                    'title' => 'Cập nhật loại tài khoản'
                ]);
            }
            $loaitaikhoan->update($validate);
            return redirect()->back()->with([
                'success' => 'Cập nhật loại tài khoản thành công.',
                'title' => 'Cập nhật loại tài khoản'
            ]);
        } catch (\Exception $e) {
            Log::channel('query')->error("Error create loaitaikhoan: " . $e->getMessage());
            return redirect()->route('loaitaikhoan.index')->with([
                'error' => 'Lỗi nhập liệu!. Vui lòng thử lại.',
                'title' => 'Cập nhật loại tài khoản'
            ]);
        }
    }
}
