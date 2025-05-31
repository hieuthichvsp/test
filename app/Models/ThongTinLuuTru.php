<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThongTinLuuTru extends Model
{
    protected $table = 'ThongTinLuuTru';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    protected $fillable = [
        'tenthongtin',
        'mota',
        'id_danhmuc',
        'id_denghi',
        'id_hocky',
        'id_user',
        'created_at',
        'updated_at',
    ];

    // Relationship với bảng danhmuc_muasam
    public function danhMuc()
    {
        return $this->belongsTo(DanhMucMuaSam::class, 'id_danhmuc');
    }

    // Relationship với bảng de_nghi
    public function deNghi()
    {
        return $this->belongsTo(DeNghi::class, 'id_denghi');
    }

    // Relationship với bảng hocky
    public function hocKy()
    {
        return $this->belongsTo(HocKy::class, 'id_hocky');
    }

    // Relationship với bảng taikhoan
    public function taiKhoan()
    {
        return $this->belongsTo(TaiKhoan::class, 'id_user');
    }

    public function fileluutru()
    {
        return $this->hasMany(FileLuuTru::class, 'id_thongtinluutru');
    }
}