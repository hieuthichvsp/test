<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Thongkemaymoc
 * 
 * @property int $id
 * @property string $tentb
 * @property string|null $maso
 * @property string|null $mota
 * @property int|null $namsd
 * @property string|null $nguongoc
 * @property string|null $donvitinh
 * @property int|null $soluong
 * @property int|null $tontai
 * @property int|null $gia
 * @property string|null $chatluong
 * @property string|null $ghichu
 * @property string $model
 * @property string|null $tinhtrang
 * @property int|null $matinhtrang
 * @property int|null $maphongkho
 * @property int|null $maloai
 * @property int|null $manhom
 * @property int|null $idthietbi
 * @property string|null $namthongke
 * @property PhongKho $phongkho
 * @property Nhommaymocthietbi $nhomtb
 * @property Loaimaymocthietbi $loaitb
 * @property Tinhtrangthietbi $tinhtrang
 *
 * @package App\Models
 */
class Thongkemaymoc extends Model
{
	protected $table = 'thongkemaymoc';
	public $timestamps = false;

	protected $casts = [
		'namsd' => 'int',
		'soluong' => 'int',
		'tontai' => 'int',
		'gia' => 'int',
		'matinhtrang' => 'int',
		'maphongkho' => 'int',
		'maloai' => 'int',
		'manhom' => 'int',
		'idthietbi' => 'int'
	];

	protected $fillable = [
		'tentb',
		'maso',
		'mota',
		'namsd',
		'nguongoc',
		'donvitinh',
		'soluong',
		'tontai',
		'gia',
		'chatluong',
		'ghichu',
		'model',
		'tinhtrang',
		'matinhtrang',
		'maphongkho',
		'maloai',
		'manhom',
		'idthietbi',
		'namthongke'
	];
	public function phongkho()
	{
		return $this->belongsTo(PhongKho::class, 'maphongkho');
	}
	public function nhomtb()
	{
		return $this->belongsTo(Nhommaymocthietbi::class, 'manhom');
	}
	public function loaitb()
	{
		return $this->belongsTo(Loaimaymocthietbi::class, 'maloai');
	}
	public function tinhtrang()
	{
		return $this->belongsTo(Tinhtrangthietbi::class, 'matinhtrang');
	}
}
