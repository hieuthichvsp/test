<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Tinhtrangthietbi;
use App\Models\Loaimaymocthietbi;
use App\Models\PhongKho;

/**
 * Class Thongkedogo
 * 
 * @property int $id
 * @property string $tentb
 * @property string|null $mota
 * @property string|null $maso
 * @property string|null $namsd
 * @property string|null $nguongoc
 * @property string|null $donvitinh
 * @property int|null $soluong
 * @property int|null $gia
 * @property int|null $chatluong
 * @property string|null $ghichu
 * @property int|null $tontai
 * @property string|null $tinhtrang
 * @property string $model
 * @property int|null $matinhtrang
 * @property int|null $maphongkho
 * @property int|null $maloai
 * @property int|null $idthietbi
 * @property string|null $namthongke
 * 
 * @property PhongKho|null $phong_kho
 * @property Loaimaymocthietbi|null $loai_thiet_bi
 * @property Tinhtrangthietbi|null $tinh_trang
 *
 * @package App\Models
 */
class Thongkedogo extends Model
{
	protected $table = 'thongkedogo';
	public $timestamps = false;

	protected $casts = [
		'soluong' => 'int',
		'gia' => 'int',
		'chatluong' => 'int',
		'tontai' => 'int',
		'matinhtrang' => 'int',
		'maphongkho' => 'int',
		'maloai' => 'int',
		'idthietbi' => 'int'
	];

	protected $fillable = [
		'tentb',
		'mota',
		'maso',
		'namsd',
		'nguongoc',
		'donvitinh',
		'soluong',
		'gia',
		'chatluong',
		'ghichu',
		'tontai',
		'tinhtrang',
		'model',
		'matinhtrang',
		'maphongkho',
		'maloai',
		'idthietbi',
		'namthongke'
	];

	public function phong_kho()
	{
		return $this->belongsTo(PhongKho::class, 'maphongkho');
	}
	public function loai_thiet_bi()
	{
		return $this->belongsTo(Loaimaymocthietbi::class, 'maloai');
	}
	public function tinh_trang()
	{
		return $this->belongsTo(Tinhtrangthietbi::class, 'matinhtrang');
	}
}
