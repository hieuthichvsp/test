<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Hocphan
 * 
 * @property string $maHP
 * @property string $tenHP
 * 
 * @property Collection|Capphat[] $capphats
 * @property Collection|Vattu[] $vattus
 *
 * @package App\Models
 */
class Hocphan extends Model
{
	protected $table = 'hocphan';
	protected $primaryKey = 'id';
	public $incrementing = false;
	public $timestamps = false;

	protected $fillable = [
		'maHP',
		'tenHP'
	];

	// public function cap_phats()
	// {
	// 	return $this->hasMany(CapPhat::class, 'ma_hoc_phan');
	// }

	public function capphats()
	{
		return $this->hasMany(Capphat::class, 'hocphan_id');
	}

	public function vattus()
	{
		return $this->hasMany(Vattu::class, 'hocphan_id');
	}

	public static function boot()
	{
		parent::boot();
		static::deleting(function ($hocphan) {
			// $hocphan->cap_phats()->delete();
			$hocphan->capphats()->delete();
			$hocphan->vattus()->delete();
		});
	}
}
