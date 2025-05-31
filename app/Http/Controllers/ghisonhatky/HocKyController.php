<?php

namespace App\Http\Controllers\ghisonhatky;

use App\Http\Controllers\Controller;
use App\Models\Hocky;
use Illuminate\Contracts\Support\ValidatedData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HocKyController extends Controller
{
    //
    public function index()
    {
        $hockies = Hocky::orderByDesc('id')->get();
        $hockyCurrent = Hocky::where('current', '1')->first();
        return view('ghisonhatky.quanlyhocky.index', compact('hockies', 'hockyCurrent'));
    }
    public function store(Request $request)
    {
        // Validate dữ liệu đầu vào
        $request->validate(
            [
                'tenhocky' => 'string|max:255',
                'tunam'    => 'integer',
                'dennam'   => 'integer|gt:tunam',
            ],
            [
                'dennam.gt' => 'Đến năm phải lớn hơn hoặc từ năm.',
            ]
        );
        if ($this->checkNamHocKy($request->tenhocky, $request->tunam, $request->dennam)) {
            return redirect()->back()->with([
                'error' => 'Năm học đã tồn tại',
                'title' => "Thêm học kỳ"
            ]);
        }
        // Nếu đã có học kỳ nào thì set tất cả về current = 0
        if (Hocky::count() > 0) {
            Hocky::query()->update(['current' => 0]);
        }

        // Tạo học kỳ mới và set current = 1
        Hocky::create([
            'hocky' => $request->tenhocky,
            'tunam'    => $request->tunam,
            'dennam'   => $request->dennam,
            'current'   => 1,
            'ngaytao' => time(),
            'madonvi' => null
        ]);

        // Chuyển hướng hoặc trả về thông báo
        return redirect()->back()->with([
            'success' => 'Thêm học kỳ thành công!',
            'title' => "Thêm học kỳ"
        ]);
    }
    public function destroy($id)
    {
        try {
            $hocky = Hocky::find($id);
            if ($hocky->current == '1') {
                return redirect()->back()->with([
                    'error' => 'Không thể xóa học kỳ này vì đang được sử dụng làm học kỳ hiện tại.',
                    'title' => "Xóa học kỳ",
                ]);
            }
            $hocky->delete();
            return redirect()->back()->with([
                'success' => 'Xóa học kỳ thành công',
                'title' => "Xóa học kỳ"
            ]);
        } catch (\Exception $e) {
            Log::error("Lỗi trong quá trình xóa học kỳ:" . $e);
            return redirect()->back()->with([
                'error' => $this->errorDelete,
                'title' => 'Xóa học kỳ'
            ]);
        }
    }
    public function edit($id)
    {
        $hocky = Hocky::find($id);
        if (request()->ajax()) {
            return response()->json($hocky);
        }
        return view('ghisonhatky.quanlyhocky.modals', compact('hocky'));
    }
    public function update(Request $request, $id)
    {
        $hocky = Hocky::find($id);
        if ($hocky->current === 1) {
            return redirect()->back()->with([
                'error' => 'Không thể cập nhật học kỳ này vì đang được sử dụng làm học kỳ hiện tại.',
                'title' => 'Cập nhật học kỳ',
            ]);
        }
        $validatedData = $request->validate([
            'hocky' => 'required|string|max:255',
            'tunam' => 'required|integer',
            'dennam' => 'required|integer|gt:tunam',
        ], [
            'dennam.gt' => 'Đến năm phải lớn hơn hoặc từ năm!',
        ]);
        $hocky->hocky = $validatedData['hocky'];
        $hocky->tunam = $validatedData['tunam'];
        $hocky->dennam = $validatedData['dennam'];
        $hocky->save();
        return redirect()->back()->with([
            'success' => 'Cập nhật học kỳ thành công',
            'title' => "Cập nhật học kỳ"
        ]);
    }
    public function saveHocKyCurrent($id)
    {
        try {
            $hocky = Hocky::find($id);
            if ($hocky->current == '1') {
                return redirect()->back()->with([
                    'warning' => 'Học kỳ đang là học kỳ hiện tại',
                    'title' => "Cập nhật học kỳ",
                ]);
            }
            foreach (Hocky::all() as $item) {
                $item->update(['current' => '0']);
            }
            $hocky->update(['current' => '1']);
            return response()->json([
                'status' => true,
                'message' => 'Cập nhật học kỳ hiện tại thành công',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật học kỳ hiện tại.'
            ]);
        }
    }
    public function checkNamHocKy($hocky, $tunam, $dennam)
    {
        $check = Hocky::where('hocky', $hocky)
            ->where('tunam', $tunam)
            ->where('dennam', $dennam)->first();
        if (!$check) {
            return false;
        }
        return true;
    }
}
