<?php

namespace App\Http\Controllers\quanlynoithat;

use App\Http\Controllers\Controller;
use App\Models\Loaithietbidogo;
use Illuminate\Http\Request;

class LoaiNoiThatController extends Controller
{
    public function index()
    {
        $loainoithats = Loaithietbidogo::all();
        return view('quanlynoithat.loainoithat.index', compact('loainoithats'));
    }
    public function edit($id)
    {
        $loainoithat = Loaithietbidogo::find($id);
        if (request()->ajax())
            return response()->json(['loainoithat' => $loainoithat]);
        return view('quanlynoithat.loainoithat.partials.modals');
    }
    public function store(Request $request)
    {
        try {
            $loainoithat = new Loaithietbidogo();
            $loainoithat->tenloai = $request->tenloai;
            $loainoithat->save();
            return redirect()->back()->with(['success' => 'Thêm loại nội thất thành công!', 'title' => 'Thêm loại nội thất']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Thêm loại nội thất không thành công!', 'title' => 'Thêm loại nội thất']);
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $loainoithat = Loaithietbidogo::findOrFail($id);
            $loainoithat->tenloai = $request->tenloai;
            $loainoithat->save();
            return redirect()->back()->with(['success' => 'Cập nhật loại nội thất thành công!', 'title' => 'Cập nhật loại nội thất']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Cập nhật loại nội thất không thành công!', 'title' => 'Cập nhật loại nội thất']);
        }
    }
    public function destroy($id)
    {
        try {
            $loainoithat = Loaithietbidogo::findOrFail($id);
            $loainoithat->delete();
            return redirect()->back()->with(['success' => 'Xóa loại nội thất thành công!', 'title' => 'Xóa loại nội thất']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Xóa loại nội thất không thành công!', 'title' => 'Xóa loại nội thất']);
        }
    }
}
