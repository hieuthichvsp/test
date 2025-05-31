<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Hocky
 * 
 * @property int $id
 * @property string $hocky
 * @property int $tunam
 * @property int $dennam
 * @property int|null $current
 * @property int $ngaytao
 * @property int $madonvi
 * 
 * @property Donvi $donvi
 * @property Collection|Capphat[] $capphats
 * @property Collection|DeNghi[] $de_nghis
 * @property Collection|Kiemke[] $kiemkes
 * @property Collection|Luutrufilevattu[] $luutrufilevattus
 * @property Collection|Nhatkyphongmay[] $nhatkyphongmays
 * @property Collection|Nhatkyqlphong[] $nhatkyqlphongs
 * @property Collection|Soquanlykho[] $soquanlykhos
 * @property Collection|Vattu[] $vattus
 * @property Collection|Muasamvattu[] $muasamvattus
 * @property Collection|Sovattugiangday[] $sovattugiangdays
 *
 * @package App\Models
 */
class Hocky extends Model
{
	protected $table = 'hocky';
	public $timestamps = false;

	protected $casts = [
		'tunam' => 'int',
		'dennam' => 'int',
		'current' => 'int',
		'ngaytao' => 'int',
		'madonvi' => 'int'
	];

	protected $fillable = [
		'hocky',
		'tunam',
		'dennam',
		'current',
		'ngaytao',
		'madonvi'
	];

	public function donvi()
	{
		return $this->belongsTo(Donvi::class, 'madonvi');
	}

	public function capphats()
	{
		return $this->hasMany(Capphat::class, 'id_hocky');
	}

	public function de_nghis()
	{
		return $this->hasMany(DeNghi::class, 'id_hocky');
	}

	public function kiemkes()
	{
		return $this->hasMany(Kiemke::class, 'id_hocky');
	}

	public function luutrufilevattus()
	{
		return $this->hasMany(Luutrufilevattu::class, 'id_hocky');
	}

	public function nhatkyphongmays()
	{
		return $this->hasMany(Nhatkyphongmay::class, 'mahocky');
	}

	public function nhatkyqlphongs()
	{
		return $this->hasMany(Nhatkyqlphong::class, 'mahocky');
	}

	public function soquanlykhos()
	{
		return $this->hasMany(Soquanlykho::class, 'mahocky');
	}

	public function vattus()
	{
		return $this->hasMany(Vattu::class, 'hocky_id');
	}
	public function muasamvattus()
	{
		return $this->hasMany(MuaSamVatTu::class, 'idhk');
	}
	public function sovattugiangdays()
	{
		return $this->hasMany(Sovattugiangday::class, 'mahocky');
	}

	public static function boot()
	{
		parent::boot();
		static::deleting(function ($hocky) {
			$hocky->capphats()->delete();
			$hocky->de_nghis()->delete();
			$hocky->kiemkes()->delete();
			$hocky->luutrufilevattus()->delete();
			$hocky->nhatkyphongmays()->delete();
			$hocky->nhatkyqlphongs()->delete();
			$hocky->soquanlykhos()->delete();
			$hocky->vattus()->delete();
			$hocky->muasamvattus()->delete();
			$hocky->sovattugiangdays()->delete();
		});
	}
}
