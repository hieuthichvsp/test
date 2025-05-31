<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Vattu
 * 
 * @property int $id
 * @property string|null $ten
 * @property string|null $dvt_id
 * @property int|null $soluong
 * @property string|null $dongia
 * @property string|null $khaitoan
 * @property string|null $dinhmuc
 * @property string|null $mahieu
 * @property string|null $nhanhieu
 * @property int|null $namsx
 * @property string|null $xuatxu
 * @property string|null $hangsx
 * @property string|null $cauhinh
 * @property string $maHP
 * @property string $caTH
 * @property int $hocky_id
 * 
 * @property Hocky $hocky
 * @property Hocphan $hocphan
 * @property Donvitinh $donvitinh
 *
 * @package App\Models
 */
class Vattu extends Model
{
	protected $table = 'vattu';
	public $timestamps = false;

	protected $casts = [
		'soluong' => 'int',
		'namsx' => 'int',
		'hocky_id' => 'int',
		'dongia' => 'int',
		'khaitoan' => 'int'
	];

	protected $fillable = [
		'ten',
		'dvt_id',
		'soluong',
		'dongia',
		'khaitoan',
		'dinhmuc',
		'mahieu',
		'nhanhieu',
		'namsx',
		'xuatxu',
		'hangsx',
		'cauhinh',
		'hocphan_id',
		'caTH',
		'hocky_id'
	];

	public function hocky()
	{
		return $this->belongsTo(Hocky::class, 'hocky_id');
	}

	public function hocphan()
	{
		return $this->belongsTo(Hocphan::class, 'hocphan_id');
	}

	public function donvitinh()
	{
		return $this->belongsTo(Donvitinh::class, 'dvt_id');
	}
}
