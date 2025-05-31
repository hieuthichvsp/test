<?php

namespace App\Http\Controllers\ghisonhatky\nhatkyphongmay;

use App\Http\Controllers\Controller;
use App\Models\Loaimaymocthietbi;
use App\Models\Maymocthietbi;
use App\Models\PhongKho;
use App\Models\Tinhtrangthietbi;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DanhSachThietBiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get_data()
    {
        $query = Maymocthietbi::with(['loaimaymocthietbi', 'tinhtrangthietbi', 'phong_kho']);
        return DataTables::of($query)
            ->addIndexColumn() // Thêm dòng này để có cột STT
            ->addColumn('Mã số', function ($item) {
                return $item->maso ?? '';
            })
            ->addColumn('Tên thiết bị', function ($item) {
                return $item->tentb ?? '';
            })
            ->addColumn('Số máy', function ($item) {
                return $item->somay ?? '';
            })
            ->addColumn('Mô tả', function ($item) {
                return $item->mota ?? '';
            })
            ->addColumn('Số lượng', function ($item) {
                return $item->soluong ?? '';
            })
            ->addColumn('Năm sử dụng', function ($item) {
                return $item->namsd ?? '';
            })
            ->addColumn('Nguồn gốc', function ($item) {
                return $item->nguongoc ?? '';
            })
            ->addColumn('Đơn vị tính', function ($item) {
                return $item->donvitinh ?? '';
            })
            ->addColumn('Giá', function ($item) {
                return $item->gia . ' VND';
            })
            ->addColumn('Phòng', function ($item) {
                return $item->phong_kho->tenphong ?? '';
            })
            ->addColumn('Chất lượng', function ($item) {
                return $item->chatluong . '%' ?? '';
            })
            ->addColumn('Thao tác', function ($item) {
                return '<a href="#" class="btn btn-warning btn-xs edit-btn" data-tooltip="Cập nhật" data-id="' . $item->id . '"><i class="fa fa-pencil"></i></a>';
            })
            ->rawColumns(['Thao tác'])
            ->make(true);
    }
    public function filter(Request $request)
    {
        // Lấy các giá trị từ request
        $loaitb_id = $request->input('loaitb-filter');
        $tinhtrang_id = $request->input('tinhtrang-filter');
        $namsd = $request->input('namsd-filter');
        $nguongoc = $request->input('nguongoc-filter');
        $gia = $request->input('gia-filter');
        $chatluong = $request->input('chatluong-filter');
        $phong_id = $request->input('phong-filter');

        $query = Maymocthietbi::query()
            ->with(['loaimaymocthietbi', 'tinhtrangthietbi', 'phong_kho']);
        if ($phong_id) {
            $query->where('maphongkho', $phong_id);
        }
        // Áp dụng các điều kiện filter
        if ($loaitb_id) {
            $query->where('maloai', $loaitb_id);
        }

        if ($tinhtrang_id) {
            $query->where('matinhtrang', $tinhtrang_id);
        }

        if ($namsd) {
            $query->where('namsd', $namsd);
        }

        if ($nguongoc) {
            $query->where('nguongoc', $nguongoc);
        }

        // Filter theo giá
        if ($gia) {
            switch ($gia) {
                case '1':
                    $query->where('gia', '<', 1000000);
                    break;
                case '2':
                    $query->whereBetween('gianhap', [1000000, 5000000]);
                    break;
                case '3':
                    $query->whereBetween('gianhap', [5000000, 10000000]);
                    break;
                case '4':
                    $query->whereBetween('gianhap', [10000000, 20000000]);
                    break;
                case '5':
                    $query->where('gianhap', '>', 20000000);
                    break;
            }
        }

        // Filter theo chất lượng
        if ($chatluong) {
            switch ($chatluong) {
                case '1':
                    $query->whereBetween('chatluong', [0, 20]);
                    break;
                case '2':
                    $query->whereBetween('chatluong', [21, 40]);
                    break;
                case '3':
                    $query->whereBetween('chatluong', [41, 60]);
                    break;
                case '4':
                    $query->whereBetween('chatluong', [61, 80]);
                    break;
                case '5':
                    $query->whereBetween('chatluong', [81, 100]);
                    break;
            }
        }

        return DataTables::of($query)
            ->addIndexColumn() // Thêm dòng này để có cột STT
            ->addColumn('Mã số', function ($item) {
                return $item->maso ?? '';
            })
            ->addColumn('Tên thiết bị', function ($item) {
                return $item->tentb ?? '';
            })
            ->addColumn('Số máy', function ($item) {
                return $item->somay ?? '';
            })
            ->addColumn('Mô tả', function ($item) {
                return $item->mota ?? '';
            })
            ->addColumn('Số lượng', function ($item) {
                return $item->mota ?? '';
            })
            ->addColumn('Năm sử dụng', function ($item) {
                return $item->namsd ?? '';
            })
            ->addColumn('Nguồn gốc', function ($item) {
                return $item->nguongoc ?? '';
            })
            ->addColumn('Đơn vị tính', function ($item) {
                return $item->donvitinh ?? '';
            })
            ->addColumn('Giá', function ($item) {
                return $item->gia . ' VND';
            })
            ->addColumn('Phòng', function ($item) {
                return $item->phong_kho->tenphong ?? '';
            })
            ->addColumn('Chất lượng', function ($item) {
                return $item->chatluong . '%' ?? '';
            })
            ->addColumn('Thao tác', function ($item) {
                return '
                @can("hasRole_A_M_L")
                <a href="#" class="btn btn-warning btn-xs edit-btn" data-tooltip="Cập nhật" data-id="' . $item->id . '"><i class="fa fa-pencil"></i></a>
                @endcan';
            })
            ->rawColumns(['Thao tác'])
            ->make(true);
    }
    public function edit($id)
    {
        $thietbi = Maymocthietbi::find($id);
        if (request()->ajax()) {
            if ($thietbi) {
                return response()->json(['thietbi' => $thietbi]);
            }
        }
    }
    protected function mapEditFields(array $input)
    {
        $mapped = [];
        foreach ($input as $key => $value) {
            if (str_ends_with($key, '-edit')) {
                $dbField = str_replace('-edit', '', $key);
                $mapped[$dbField] = $value;
            }
        }
        return $mapped;
    }
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'tentb-edit' => 'string|max:255',
                'maso-edit' => 'nullable|string|max:40',
                'somay-edit' => 'nullable|integer',
                'mota-edit' => 'nullable|string|max:255',
                'namsd-edit' => 'nullable|integer',
                'nguongoc-edit' => 'nullable|string|max:255',
                'donvitinh-edit' => 'nullable|string|max:255',
                'soluong-edit' => 'nullable|integer',
                'gia-edit' => 'required|numeric',
                'chatluong-edit' => 'nullable|numeric',
                'ghichu-edit' => 'nullable|string|max:255',
            ]);
            $thietbi = Maymocthietbi::find($id);
            if ($thietbi) {
                $thietbi->update($this->mapEditFields($validated));
                return  redirect()->back()->with(['success' => 'Cập nhật thiết bị thành công', 'title' => 'Cập nhật thiết bị']);
            } else {
                return  redirect()->back()->with(['error' => 'Thiết bị không tồn tại', 'title' => 'Cập nhật thiết bị']);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Cập nhật thiết bị thất bại!' . $e->getMessage(), 'title' => 'Cập nhật thiết bị']);
        }
    }
}
