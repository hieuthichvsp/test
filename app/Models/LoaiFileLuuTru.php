<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoaiFileLuuTru extends Model
{
    protected $table = 'LoaiFileLuuTru';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    protected $fillable = [
        'tenloai',
        'mota',
        'create_at',
        'update_at'
    ];

    // Relationship với bảng thông tin lưu trữ (nếu cần)
    public function fileLuuTru()
    {
        return $this->hasMany(FileLuuTru::class, 'id_loaifile');
    }
}