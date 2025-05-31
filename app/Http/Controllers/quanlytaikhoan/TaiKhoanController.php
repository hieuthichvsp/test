<?php

namespace App\Http\Controllers\quanlytaikhoan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Taikhoan;
use Illuminate\Support\Facades\Hash;
use App\Models\Loaitaikhoan;
use App\Models\Donvi;
use Illuminate\Support\Facades\Log;

class TaiKhoanController extends Controller
{
    public function index()
    {
        // Lấy danh sách tài khoản
        $taikhoans = Taikhoan::with('loaitaikhoan', 'donvi')->get();
        // Lấy danh sách loại tài khoản
        $loaitaikhoans = Loaitaikhoan::all();
        // Lấy danh sách đơn vị
        $donvis = Donvi::all();
        // Truyền dữ liệu vào view 
        return view('quanlytaikhoan.taikhoan.index', compact('taikhoans', 'loaitaikhoans', 'donvis'));
    }
    public function store(Request $request)
    {
        try {
            // Kiểm tra dữ liệu đầu vào
            $validate = $request->validate([
                'hoten' => 'required|string|max:255',
                'matkhau' => 'nullable|string|max:255',
                'email' => 'required|email|max:255|unique:taikhoan,email',
                'cmnd' => 'nullable|string|max:255|unique:taikhoan,cmnd',
                'chucvu' => 'nullable|string|max:255',
                'maloaitk' => 'required|integer',
                'madonvi' => 'nullable|integer',
            ]);
            // Hash mật khẩu
            $validate['matkhau'] = Hash::make($validate['email']);
            // Thêm tài khoản
            $taikhoan = Taikhoan::create($validate);
            if (!$taikhoan) {
                return redirect()->back()->with([
                    'error' => 'Đã xảy ra lỗi trong quá trình thêm tài khoản.',
                    'title' => 'Thêm tài khoản'
                ]);
            }
            return redirect()->back()->with([
                'success' => 'Thêm tài khoản thành công.',
                'title' => 'Thêm tài khoản'
            ]);
        } catch (\Exception $e) {
            //Xứ lý lỗi ngoại lệ
            Log::channel('query')->error("Error create taikhoan: " . $e->getMessage());
            return redirect()->back()->with([
                'error' => $this->errorInsert,
                'title' => 'Thêm tài khoản'
            ]);
        }
    }

    public function edit($id)
    {
        // Tìm tài khoản theo ID
        $taikhoan = Taikhoan::find($id);
        if (request()->ajax()) {
            // Trả về dưới dạng JSON
            return response()->json([
                'taikhoan' => $taikhoan,
            ]);
        }
        return view('quanlytaikhoan.taikhoan.partials.modals', compact('taikhoan'));
    }

    public function update(Request $request, $id)
    {
        try {
            //Kiểm tra dữ liệu đầu vào
            $validate = $request->validate([
                'hoten' => 'string|max:255',
                'email' => 'email|max:255|unique:taikhoan,email,' . $id,
                'cmnd' => 'nullable|string|max:255|unique:taikhoan,cmnd,' . $id,
                'chucvu' => 'nullable|string|max:255',
                'maloaitk' => 'integer|exists:loaitaikhoan,id',
                'madonvi' => 'nullable|integer',
                'hinhanh' => 'nullable|string|max:255'
            ]);
            // Tìm tài khoản cần cập nhật
            $taikhoan = Taikhoan::find($id);
            if (!$taikhoan) {
                return redirect()->back()->with([
                    'error' => 'Không tìm thấy tài khoản.',
                    'title' => 'Cập nhật tài khoản'
                ]);
            }
            // Cập nhật tài khoản
            $taikhoan->update($validate);
            return redirect()->back()->with([
                'success' => 'Cập nhật tài khoản thành công.',
                'title' => 'Cập nhật tài khoản'
            ]);
        } catch (\Exception $e) {
            //Xử lý lỗi ngoại lệ
            Log::channel('query')->error("Error update taikhoan: " . $e->getMessage());
            return redirect()->back()->with([
                'error' => $this->errorUpdate,
                'title' => 'Cập nhật tài khoản'
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            // Tìm tài khoản cần xóa
            $taikhoan = Taikhoan::find($id);
            if ($taikhoan) {
                $taikhoan->delete();
                return redirect()->back()->with([
                    'success' => 'Xóa tài khoản thành công.',
                    'title' => 'Xóa tài khoản'
                ]);
            } else {
                return redirect()->back()->with([
                    'error' => 'Tài khoản không tồn tại.',
                    'title' => 'Xóa tài khoản'
                ]);
            }
        } catch (\Exception $e) {
            Log::channel('query')->error("Error delete taikhoan: " . $e->getMessage());
            return redirect()->back()->with([
                'error' => $this->errorDelete,
                'title' => 'Xóa tài khoản'
            ]);
        }
    }
}
