<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\auth\ProfileController;
use App\Http\Controllers\quanlydonvi\DonViController;
use App\Http\Controllers\quanlydonvi\PhongKhoController;
use App\Http\Controllers\quanlytaikhoan\LoaiTaiKhoanController;
use App\Http\Controllers\quanlytaikhoan\TaiKhoanController;
use App\Http\Controllers\ghisonhatky\nhatkyphongmay\NhatKyPhongMayController;
use App\Http\Controllers\ghisonhatky\nhatkyphongmay\NhatKySuDungController;
use App\Http\Controllers\ghisonhatky\nhatkyphongmay\DanhSachThietBiController;
use App\Http\Controllers\ghisonhatky\HocKyController;
use App\Http\Controllers\quanlynoithat\LoaiNoiThatController;
use App\Http\Controllers\quanlynoithat\NoiThatController;
use App\Http\Controllers\quanlynoithat\KiemkeController;
use App\Http\Controllers\quanlythietbi\LoaiThietBiController;
use App\Http\Controllers\quanlythietbi\MayMocThietBiController;
use App\Http\Controllers\quanlythietbi\NhomThietBiController;
use App\Http\Controllers\quanlyfilequytrinh\QuanLyDanhMucController;
use App\Http\Controllers\quanlyfilequytrinh\DeNghiController;
use App\Http\Controllers\ghisonhatky\NhatKyLoaiThietBiController;
use App\Http\Controllers\ghisonhatky\nhatkyphongmay\BaoTriSuaChuaController;
use App\Http\Controllers\quanlythietbi\ThongKeController;
use App\Http\Controllers\capphatvattu\CapPhatVatTuController;
use App\Http\Controllers\capphatvattu\QuanLyVatTuController;
use App\Http\Controllers\ghisonhatky\SoKhoController;
use App\Http\Controllers\quanlybieumau\BieuMauThietBiController;
use App\Http\Controllers\quanlybieumau\BieuMauPhongMayController;
use App\Http\Controllers\quanlybieumau\SoQuanLyKhoController;
use Symfony\Component\HttpKernel\Profiler\Profile;

Route::get('/', function () {
    return redirect()->route('login');
});
Route::middleware('auth')->group(function () {
    Route::get('/home', function () {
        return view('layouts.app');
    })->name('home');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Password reset routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [ProfileController::class, 'update'])->name('update');
        Route::put('/update-avatar/{id}', [ProfileController::class, 'updateAvatar'])->name('updateAvatar');
        Route::get('/password-update-form', [ProfileController::class, 'passwordUpdateForm'])->name('passwordUpdateForm');
        Route::put('/password-update/{id}', [ProfileController::class, 'updatePassword'])->name('passwordUpdate');
    });
    Route::prefix('donvi')->name('donvi.')->group(function () {
        Route::get('/', [DonViController::class, 'index'])->name('index');
        Route::middleware('can:isAdmin')->group(function () {
            Route::post('/create', [DonViController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [DonViController::class, 'edit'])->name('edit');
            Route::put('/upd/{id}', [DonViController::class, 'update'])->name('update');
            Route::delete('/del/{id}', [DonViController::class, 'destroy'])->name('destroy');
            Route::post('/import', [DonViController::class, 'import'])->name('import');
        });
    });
    Route::prefix('phongkho')->name('phongkho.')->group(function () {
        Route::get('/', [PhongKhoController::class, 'index'])->name('index');
        Route::get('/get-gvql-by-don-vi/{id}', [PhongKhoController::class, 'getGVQLByDonVi'])->name('getGVQLByDonVi');
        Route::middleware('can:isAdmin')->group(function () {
            Route::post('/create', [PhongKhoController::class, 'store'])->name('store');
            Route::put('/upd/{id}', [PhongKhoController::class, 'update'])->name('update');
            Route::get('/edit/{id}', [PhongKhoController::class, 'edit'])->name('edit');
            Route::delete('/del/{id}', [PhongKhoController::class, 'destroy'])->name('destroy');
            Route::post('/import', [PhongKhoController::class, 'import'])->name('import');
        });
    });
    Route::prefix('loaitaikhoan')->middleware('can:isAdmin')->name('loaitaikhoan.')->group(function () {
        Route::get('/', [LoaiTaiKhoanController::class, 'index'])->name('index');
        Route::post('/create', [LoaiTaiKhoanController::class, 'store'])->name('store');
        Route::put('/upd/{id}', [LoaiTaiKhoanController::class, 'update'])->name('update');
        Route::get('/edit/{id}', [LoaiTaiKhoanController::class, 'edit'])->name('edit');
    });
    Route::prefix('taikhoan')->middleware('can:isAdmin')->name('taikhoan.')->group(function () {
        Route::get('/', [TaiKhoanController::class, 'index'])->name('index');
        Route::post('/create', [TaiKhoanController::class, 'store'])->name('store');
        Route::put('/upd/{id}', [TaiKhoanController::class, 'update'])->name('update');
        Route::get('/edit/{id}', [TaiKhoanController::class, 'edit'])->name('edit');
        Route::delete('/del/{id}', [TaiKhoanController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('nhatkyphongmay')->name('nhatkyphongmay.')->group(function () {
        //Nhật ký sử dụng
        Route::get('/', [NhatKyPhongMayController::class, 'index'])->name('index');
        Route::get('/search-phong', [NhatKyPhongMayController::class, 'searchPhongMay'])->name('search-phong');
        Route::get('/loadMachines', [NhatKyPhongMayController::class, 'loadMachines'])->name('loadMachines');
        Route::prefix('nhatkysudung')->name('nhatkysudung.')->group(function () {
            Route::post('/create', [NhatKySuDungController::class, 'storeNew'])->name('storeNew');
            Route::put('/upd/{id}', [NhatKySuDungController::class, 'update'])->name('update');
            Route::get('/edit/{id}', [NhatKySuDungController::class, 'edit'])->name('edit');
            Route::delete('/del/{id}', [NhatKySuDungController::class, 'destroy'])->name('destroy');
            Route::get('/loadTable', [NhatKySuDungController::class, 'loadTable'])->name('loadTable');
            Route::put('/update-status-pc/{idtb}', [NhatKySuDungController::class, 'updateStatusPC'])->name('update-status-pc');
        });
        //Danh sách thiết bị
        Route::prefix('danhsachthietbi')->name('danhsachthietbi.')->group(function () {
            Route::get('/filter', [DanhSachThietBiController::class, 'filter'])->name('filter');
            Route::get('/get-data', [DanhSachThietBiController::class, 'get_data'])->name('get_data');
            Route::put('/upd/{id}', [DanhSachThietBiController::class, 'update'])->name('update');
            Route::get('/edit/{id}', [DanhSachThietBiController::class, 'edit'])->name('edit');
        });
        Route::prefix('baotrisuachua')->name('baotrisuachua.')->group(function () {
            Route::get('/filter', [BaoTriSuaChuaController::class, 'filter'])->name('filter');
            Route::put('/upd/{id}', [BaoTriSuaChuaController::class, 'update'])->name('update');
            Route::get('/edit/{id}', [BaoTriSuaChuaController::class, 'edit'])->name('edit');
            Route::delete('/del/{id}', [BaoTriSuaChuaController::class, 'destroy'])->name('destroy');
            Route::post('/create', [BaoTriSuaChuaController::class, 'store'])->name('store');
            Route::get('/getListDevices/{phong_id}', [BaoTriSuaChuaController::class, 'getListDevicesByRoom'])->name('getListDevices');
        });
    });
    Route::prefix('soquanlykho')->name('soquanlykho.')->group(function () {
        Route::get('/', [SoKhoController::class, 'index'])->name('index');
        Route::get('/filter', [SoKhoController::class, 'filter'])->name('filter');
        Route::put('/upd/{id}', [SoKhoController::class, 'update'])->name('update');
        Route::get('/edit/{id}', [SoKhoController::class, 'edit'])->name('edit');
        Route::delete('/del/{id}', [SoKhoController::class, 'destroy'])->name('destroy');
        Route::post('/create', [SoKhoController::class, 'store'])->name('store');
    });
    Route::prefix('nhatkyloaithietbi')->name('nhatkyloaithietbi.')->group(function () {
        Route::get('/', [NhatKyLoaiThietBiController::class, 'index'])->name('index');
        Route::post('/create', [NhatKyLoaiThietBiController::class, 'storeNew'])->name('storeNew');
        Route::get('/{id}/edit', [NhatKyLoaiThietBiController::class, 'edit'])->name('edit');
        Route::put('/{id}', [NhatKyLoaiThietBiController::class, 'update'])->name('update');
        Route::delete('/{id}', [NhatKyLoaiThietBiController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('hocky')->name('hocky.')->group(function () {
        Route::get('/', [HocKyController::class, 'index'])->name('index');
        Route::post('/create', [HocKyController::class, 'store'])->name('store');
        Route::delete('/del/{id}', [HocKyController::class, 'destroy'])->name('destroy');
        Route::put('/upd/{id}', [HocKyController::class, 'update'])->name('update');
        Route::get('/edit/{id}', [HocKyController::class, 'edit'])->name('edit');
        Route::post('/save-hocky-current/{id}', [HocKyController::class, 'saveHocKyCurrent'])->name('saveHocKyCurrent');
    });
    Route::prefix('loainoithat')->name('loainoithat.')->group(function () {
        Route::get('/', [LoaiNoiThatController::class, 'index'])->name('index');
        Route::post('/create', [LoaiNoiThatController::class, 'store'])->name('store');
        Route::put('/upd/{id}', [LoaiNoiThatController::class, 'update'])->name('update');
        Route::get('/edit/{id}', [LoaiNoiThatController::class, 'edit'])->name('edit');
        Route::delete('/del/{id}', [LoaiNoiThatController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('noithat')->name('noithat.')->group(function () {
        Route::get('/', [NoiThatController::class, 'index'])->name('index');
        Route::post('/store', [NoiThatController::class, 'store'])->name('store');
        Route::put('/upd/{id}', [NoiThatController::class, 'update'])->name('update');
        Route::get('/edit/{id}', [NoiThatController::class, 'edit'])->name('edit');
        Route::delete('/del/{id}', [NoiThatController::class, 'destroy'])->name('destroy');
        Route::post('/updateTinhTrang', [NoiThatController::class, 'updateTinhTrang'])->name('updateTinhTrang');
        Route::get('/filter/{tochuc}', [NoiThatController::class, 'getByLoai'])->name('getDonViByToChuc');
        Route::get('/filterByRoom', [NoiThatController::class, 'locTheoDonVi'])->name('locTheoDonVi');
        Route::delete('/xoanhieu', [NoiThatController::class, 'xoanhieu'])->name('xoaNhieu');
        Route::get('/api/phong/{madonvi}', [NoiThatController::class, 'getPhongTheoDonVi'])->name('getPhongTheoDonVi');
        Route::get('/api/thietbi/phong/{maphong}', [NoiThatController::class, 'getThietBiTheoPhong'])->name('getThietBiTheoPhong');
    });
    Route::prefix('kiemke')->name('kiemke.')->group(function () {
        Route::get('/', [KiemkeController::class, 'index'])->name('index');         // Danh sách
        Route::post('/store', [KiemkeController::class, 'store'])->name('store');  // Thêm mới
        Route::put('/upd/{id}', [KiemkeController::class, 'update'])->name('update'); // Cập nhật
        Route::get('/edit/{id}', [KiemkeController::class, 'edit'])->name('edit');  // Lấy dữ liệu sửa
        Route::delete('/del/{id}', [KiemkeController::class, 'destroy'])->name('destroy'); // Xóa
        Route::get('/api/phongkho', [KiemkeController::class, 'locTheoDonVi'])->name('locTheoDonVi'); // Lọc theo đơn vị
    });

    Route::prefix('loaithietbi')->name('loaithietbi.')->group(function () {
        Route::get('/', [LoaiThietBiController::class, 'index'])->name('index');
        Route::middleware('can:hasRole_A_M_L')->group(function () {
            Route::post('/create', [LoaiThietBiController::class, 'store'])->name('store');
            Route::put('/upd/{id}', [LoaiThietBiController::class, 'update'])->name('update');
            Route::get('/edit/{id}', [LoaiThietBiController::class, 'edit'])->name('edit');
            Route::delete('/del/{id}', [LoaiThietBiController::class, 'destroy'])->name('destroy');
            Route::post('/import', [LoaiThietBiController::class, 'import'])->name('import');
            Route::get('/template', [LoaiThietBiController::class, 'downloadTemplate'])->name('downloadTemplate');
        });
    });

    Route::prefix('nhomthietbi')->name('nhomthietbi.')->group(function () {
        Route::get('/', [NhomThietBiController::class, 'index'])->name('index');
        Route::post('/create', [NhomThietBiController::class, 'store'])->name('store');
        Route::put('/upd/{id}', [NhomThietBiController::class, 'update'])->name('update');
        Route::get('/edit/{id}', [NhomThietBiController::class, 'edit'])->name('edit');
        Route::delete('/del/{id}', [NhomThietBiController::class, 'destroy'])->name('destroy');
        Route::post('/import', [NhomThietBiController::class, 'import'])->name('import');
        Route::get('/template', [NhomThietBiController::class, 'downloadTemplate'])->name('downloadTemplate');
    });
    // Routes cho quản lý thiết bị máy móc
    Route::prefix('maymocthietbi')->group(function () {
        Route::get('/', [MayMocThietBiController::class, 'index'])->name('maymocthietbi.index');
        Route::post('/store', [MayMocThietBiController::class, 'store'])->name('maymocthietbi.store');
        Route::get('/show/{id}', [MayMocThietBiController::class, 'show'])->name('maymocthietbi.show');
        Route::get('/edit/{id}', [MayMocThietBiController::class, 'edit'])->name('maymocthietbi.edit');
        Route::put('/update/{id}', [MayMocThietBiController::class, 'update'])->name('maymocthietbi.update');
        Route::delete('/destroy/{id}', [MayMocThietBiController::class, 'destroy'])->name('maymocthietbi.destroy');
        Route::post('/import', [MayMocThietBiController::class, 'import'])->name('maymocthietbi.import');
        Route::get('/export', [MayMocThietBiController::class, 'export'])->name('maymocthietbi.export');
        Route::get('/template', [MayMocThietBiController::class, 'downloadTemplate'])->name('maymocthietbi.downloadTemplate');
        Route::get('/get-data', [MayMocThietBiController::class, 'getData'])->name('maymocthietbi.get-data');
    });
    ///Quanlydanhmuc
    Route::prefix('quanlydanhmuc')->middleware('can:hasRole_A_M_L')->name('quanlydanhmuc.')->group(function () {
        Route::get('/', [QuanLyDanhMucController::class, 'index'])->name('index');
        Route::post('/create', [QuanLyDanhMucController::class, 'store'])->name('store');
        Route::put('/upd/{id}', [QuanLyDanhMucController::class, 'update'])->name('update');
        Route::get('/edit/{id}', [QuanLyDanhMucController::class, 'edit'])->name('edit');
        Route::delete('/del/{id}', [QuanLyDanhMucController::class, 'destroy'])->name('destroy');
        Route::get('/download/{id}', [QuanLyDanhMucController::class, 'download'])->name('download');
        Route::get('/show/{id}', [QuanLyDanhMucController::class, 'show'])->name('show');
        Route::get('/getFileInfo/{id}', [QuanLyDanhMucController::class, 'getFileInfo'])->name('getFileInfo');
        Route::put('/upload/{id}', [QuanLyDanhMucController::class, 'upload'])->name('upload');
        Route::get('/reset', [QuanLyDanhMucController::class, 'reset'])->name('reset');
        Route::get('/readfile/{id}', [QuanLyDanhMucController::class, 'readfile'])->name('readfile');
        Route::get('/getFileDetail/{id}', [QuanLyDanhMucController::class, 'getFileDetail'])->name('getFileDetail');
        Route::get('/downloadfile/{id}', [QuanLyDanhMucController::class, 'downloadfile'])->name('downloadfile');
        Route::get('/downloadByCategory', [QuanLyDanhMucController::class, 'downloadByCategory'])->name('downloadByCategory');
    });
    Route::prefix('denghi')->middleware('can:hasRole_A_M_L')->name('denghi.')->group(function () {
        Route::get('/', [DeNghiController::class, 'index'])->name('index');
        Route::post('/create', [DeNghiController::class, 'store'])->name('store');
        Route::put('/upd/{id}', [DeNghiController::class, 'update'])->name('update');
        Route::get('/edit/{id}', [DeNghiController::class, 'edit'])->name('edit');
        Route::delete('/del/{id}', [DeNghiController::class, 'destroy'])->name('destroy');
        Route::get('/download/{id}', [DeNghiController::class, 'download'])->name('download');
        Route::delete('/file/{id}', [DeNghiController::class, 'deleteFile'])->name('deleteFile');
    });
    // Trong phần route cho thống kê
    Route::prefix('thongke')->middleware('can:hasRole_A_M_L')->name('thongke.')->group(function () {
        Route::get('/', [ThongKeController::class, 'index'])->name('index');
        Route::get('/theoloai', [ThongKeController::class, 'thongKeTheoLoai'])->name('theoloai');
        Route::get('/theonhom', [ThongKeController::class, 'thongKeTheoNhom'])->name('theonhom');
        Route::get('/theophongkho', [ThongKeController::class, 'thongKeTheoPhongKho'])->name('theophongkho');
        Route::get('/theotinhtrang', [ThongKeController::class, 'thongKeTheoTinhTrang'])->name('theotinhtrang');
        Route::get('/export', [ThongKeController::class, 'export'])->name('xuatexcel');
        Route::get('/exportfile', [ThongKeController::class, 'xuatfileexcel'])->name('xuatfileexcel');
    });

    Route::prefix('capphatvattu')->middleware('can:hasRole_Admin_Manager')->name('capphatvattu.')->group(function () {
        Route::get('/', [CapPhatVatTuController::class, 'index'])->name('index');
        Route::post('/create', [CapPhatVatTuController::class, 'store'])->name('store');
        Route::get('/tai-file/{filename}', [CapPhatVatTuController::class, 'taiFile'])->name('tai-file');
        Route::post('/upload-xacnhan/{id}', [CapPhatVatTuController::class, 'uploadXacNhanSubmit'])->name('upload-xacnhan');
        Route::delete('/del/{id}', [CapPhatVatTuController::class, 'destroy'])->name('destroy');
        Route::get('/filter', [CapPhatVatTuController::class, 'filter'])->name('filter');
    });
    Route::prefix('quanlyvattu')->middleware('can:hasRole_Admin_Manager')->name('quanlyvattu.')->group(function () {
        Route::get('/', [QuanLyVatTuController::class, 'index'])->name('index');
        Route::get('/filter', [QuanLyVatTuController::class, 'filter'])->name('filter');
        Route::post('/create', [QuanLyVatTuController::class, 'store'])->name('store');
        Route::put('/upd/{id}', [QuanLyVatTuController::class, 'update'])->name('update');
        Route::delete('/del/{id}', [QuanLyVatTuController::class, 'destroy'])->name('destroy');
    });


    // Routes quản lý biểu mẫu
    Route::prefix('bieumau')->name('bieumau.')->group(function () {
        // Biểu mẫu thiết bị
        Route::get('/thietbi', [BieuMauThietBiController::class, 'index'])->name('thietbi');
        Route::post('/thietbi/store', [BieuMauThietBiController::class, 'store'])->name('thietbi.store');
        Route::get('/thietbi/download/{id}', [BieuMauThietBiController::class, 'download'])->name('thietbi.download');
        Route::delete('/thietbi/delete/{id}', [BieuMauThietBiController::class, 'destroy'])->name('thietbi.destroy');
        Route::get('/thietbi/{id}/edit', [BieuMauThietBiController::class, 'edit'])->name('thietbi.edit');
        Route::put('/thietbi/{id}/update', [BieuMauThietBiController::class, 'update'])->name('thietbi.update');

        // Sổ quản lý kho
        Route::get('/sokho', [SoQuanLyKhoController::class, 'index'])->name('sokho');
        Route::get('/sokho/download/{id}', [SoQuanLyKhoController::class, 'download'])->name('sokho.download');
        Route::get('/sokho/print', [SoQuanLyKhoController::class, 'print'])->name('sokho.print');
        Route::get('/sokho/print-lylich', [SoQuanLyKhoController::class, 'printLyLich'])->name('sokho.print-lylich');
        Route::get('/sokho/print-sanxuat', [SoQuanLyKhoController::class, 'printSanXuat'])->name('sokho.print-sanxuat');

        // Nhật ký phòng máy
        Route::get('/nhatky', [BieuMauPhongMayController::class, 'index'])->name('nhatky');
        Route::post('/nhatky/store', [BieuMauPhongMayController::class, 'store'])->name('nhatky.store');
        Route::get('/nhatky/download/{id}', [BieuMauPhongMayController::class, 'download'])->name('nhatky.download');
        Route::get('/nhatky/export/{khoaId}/{phongId}', [BieuMauPhongMayController::class, 'export'])->name('nhatky.export');

        // phòng kho
        Route::get('/phongkho/get-by-khoa', [SoQuanLyKhoController::class, 'getPhongByKhoa'])->name('phongkho.get-by-khoa');
    });

    // Thêm routes cho in sổ quản lý kho
    Route::get('bieumau/lylich/export/{khoaId}/{phongId}', [SoQuanLyKhoController::class, 'printLyLich'])->name('bieumau.lylich.export');
    Route::get('bieumau/sanxuat/export/{khoaId}/{phongId}', [SoQuanLyKhoController::class, 'printSanXuat'])->name('bieumau.sanxuat.export');
});
