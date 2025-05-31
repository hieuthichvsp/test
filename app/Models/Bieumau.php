<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Bieumau
 * 
 * @property int $id
 * @property string $tenbieumau
 * @property string $tentaptin
 * @property int $create_at
 *
 * @package App\Models
 */
class Bieumau extends Model
{
	protected $table = 'bieumau';
	public $timestamps = false;

	protected $casts = [
		'create_at' => 'int'
	];

	protected $fillable = [
		'tenbieumau',
		'tentaptin',
		'create_at'
	];
}
