<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileLuuTru extends Model
{
    protected $table = 'FileLuuTru';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    protected $fillable = [
        'tenfile',
        'duongdan',
        'id_loaifile',
        'id_thongtinluutru',
        'id_user',
        'created_at',
        'updated_at'
    ];

    // Relationship với bảng LoaiFileLuuTru
    public function loaiFile()
    {
        return $this->belongsTo(LoaiFileLuuTru::class, 'id_loaifile');
    }

    // Relationship với bảng ThongTinLuuTru
    public function thongTinLuuTru()
    {
        return $this->belongsTo(ThongTinLuuTru::class, 'id_thongtinluutru');
    }

    // Relationship với bảng taikhoan
    public function taiKhoan()
    {
        return $this->belongsTo(TaiKhoan::class, 'id_user');
    }
}