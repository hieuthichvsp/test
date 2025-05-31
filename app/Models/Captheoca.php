<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Captheoca
 * 
 * @property int $id
 * @property int $si_so
 * @property int $ca_cp
 * @property string $thiet_bi
 * @property Carbon $created_at
 * @property int|null $id_capphat
 * @property CapPhat|null $capphat
 *
 * @package App\Models
 */
class Captheoca extends Model
{
	protected $table = 'captheoca';
	public $timestamps = false;

	protected $casts = [
		'si_so' => 'int',
		'ca_cp' => 'int',
		'id_capphat' => 'int'
	];

	protected $fillable = [
		'si_so',
		'ca_cp',
		'thiet_bi',
		'id_capphat'
	];

	public function capphat()
	{
		return $this->belongsTo(Capphat::class, 'id');
	}
}
