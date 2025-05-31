<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Donvitinh
 * 
 * @property int $id
 * @property string|null $tendonvi
 *  @property Collection|Vattu[] $vattus
 * @package App\Models
 */
class Donvitinh extends Model
{
	protected $table = 'donvitinh';
	public $timestamps = false;

	protected $fillable = [
		'tendonvi'
	];
	public function vattus()
	{
		return $this->hasMany(Vattu::class, 'dvt_id');
	}

	public static function boot()
	{
		parent::boot();
		static::deleting(function ($dvt) {
			$dvt->vattus()->delete();
		});
	}
}
