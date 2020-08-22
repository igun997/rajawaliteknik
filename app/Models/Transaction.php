<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Transaction
 * 
 * @property int $id
 * @property int $ref_type
 * @property int|null $ref_id
 * @property float $total
 * @property string $descriptions
 * @property int|null $user_id
 * @property int $type
 * @property int $status
 * @property Carbon|null $updated_at
 * @property Carbon $created_at
 * 
 * @property User $user
 *
 * @package App\Models
 */
class Transaction extends Model
{
	protected $table = 'transactions';

	protected $casts = [
		'ref_type' => 'int',
		'ref_id' => 'int',
		'total' => 'float',
		'user_id' => 'int',
		'type' => 'int',
		'status' => 'int'
	];

	protected $fillable = [
		'ref_type',
		'ref_id',
		'total',
		'descriptions',
		'user_id',
		'type',
		'status'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
