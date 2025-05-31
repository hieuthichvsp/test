<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\Taikhoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('auth.profile', compact('user'));
    }
    public function update(Request $request, $id)
    {
        try {
            $user = Taikhoan::find($id);
            if (!$user) {
                return redirect()->back()->with('error', 'Người dùng không tồn tại.');
            }
            $request->validate([
                'email-edit' => 'required|email|unique:taikhoan,email,' . $user->id,
                'hoten-edit' => 'required|string|max:255',
                'cmnd-edit' => 'required|string|max:20|unique:taikhoan,cmnd,' . $user->id,
            ]);
            $user->email = $request->input('email-edit');
            $user->hoten = $request->input('hoten-edit');
            $user->cmnd = $request->input('cmnd-edit');
            $user->save();
            return redirect()->back()->with(['success' => 'Cập nhật thông tin thành công.', 'title' => 'Cập nhật thông tin người dùng']);
        } catch (\Exception $e) {
            Log::error('Error updating profile: ' . $e->getMessage());
            return redirect()->back()->with(['error' => 'Có lỗi xảy ra khi cập nhật thông tin.', 'title' => 'Cập nhật thông tin người dùng']);
        }
    }
    public function passwordUpdateForm()
    {
        return view('auth.password-update');
    }
    public function updatePassword(Request $request, $id)
    {
        try {
            $user = Taikhoan::find($id);
            if (!$user) {
                return redirect()->back()->with('error', 'Người dùng không tồn tại.');
            }

            // Kiểm tra xem người dùng có quyền cập nhật thông tin hay không
            if (Auth::user()->id !== $user->id) {
                return redirect()->back()->with('error', 'Bạn không có quyền cập nhật thông tin của người dùng này.');
            }

            if (!Hash::check($request->input('matkhau_old'), $user->matkhau)) {
                return back()->withInput()->withErrors(['matkhau_old' => 'Mật khẩu cũ không đúng!']);
            }
            //Xác nhận mật khẩu mới
            if ($request->input('matkhau_new') !== $request->input('matkhau_confirmation')) {
                return back()->withInput()->withErrors(['matkhau_confirmation' => 'Mật khẩu mới và xác nhận mật khẩu không khớp!']);
            }
            // Validate mật khẩu mới
            $request->validate([
                'matkhau_new' => [
                    'required',
                    'string',
                    'min:8',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/'
                ]
            ], [
                'matkhau_new.regex' => 'Mật khẩu phải chứa ít nhất 1 chữ hoa, 1 chữ thường, 1 số và 1 ký tự đặc biệt.'
            ]);
            // Cập nhật mật khẩu mới
            $user->matkhau = Hash::make($request->input('matkhau_new'));
            $user->save();
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            // Xóa JWT token nếu có
            if (session()->has('jwt_token')) {
                try {
                    JWTAuth::setToken(session('jwt_token'))->invalidate();
                } catch (\Exception $e) {
                    Log::error('Error invalidating JWT token: ' . $e->getMessage());
                }
            }
            return redirect('/login')->with('updatePasswordSuccess', 'Cập nhật mật khẩu thành công. Vui lòng đăng nhập lại.');
        } catch (\Exception $e) {
            Log::error('Error updating password: ' . $e->getMessage());
            return back()->withErrors('error', 'Có lỗi xảy ra khi cập nhật mật khẩu. Vui lòng thử lại sau.');
        }
    }

    public function updateAvatar(Request $request, $id)
    {
        try {
            $user = Taikhoan::find($id);
            if (!$user) {
                return redirect()->back()->with(['error' => 'Người dùng không tồn tại.', 'title' => 'Cập nhật ảnh đại diện']);
            }
            if (Auth::user()->id !== $user->id) {
                return redirect()->back()->with(['error' => 'Bạn không có quyền truy cập', 'title' => 'Cập nhật ảnh đại diện']);
            }
            $validate = $request->validate([
                'hinhanh-edit' => 'required|image|mimes:jpeg,png,jpg,gif|max:4096',
            ]);
            $folderName = $user->email . '_' . $user->id;
            $publicPath = public_path('avatar/taikhoan/' . $folderName);
            if (!file_exists($publicPath)) {
                mkdir($publicPath, 0777, true);
            }
            // Xóa ảnh cũ nếu tồn tại và không phải ảnh mặc định
            if ($user->hinhanh) {
                $oldAvatarPath = $publicPath . '/' . $user->hinhanh;
                if (file_exists($oldAvatarPath)) {
                    unlink($oldAvatarPath);
                }
            }
            // Lưu ảnh mới (ví dụ khi upload qua request)
            if ($validate['hinhanh-edit']) {
                $file = $validate['hinhanh-edit'];
                $fileName = time() . '_' . $file->getClientOriginalName(); // tạo tên mới tránh trùng
                $file->move($publicPath, $fileName);

                // Cập nhật tên file mới vào CSDL
                $user->hinhanh = $fileName;
                $user->save();
            }
            return redirect()->back()->with(['success' => 'Cập nhật ảnh đại diện thành công.', 'title' => 'Cập nhật ảnh đại diện']);
        } catch (\Exception $e) {
            Log::error('Error updating avatar: ' . $e->getMessage());
            return redirect()->back()->with(['error' => 'Có lỗi xảy ra khi cập nhật ảnh đại diện.', 'title' => 'Cập nhật ảnh đại diện']);
        }
    }
}
