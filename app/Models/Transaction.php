<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Casts\RefType;
use App\Casts\StatusTransaction;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Transaction
 * 
 * @property int $id
 * @property int $ref_type
 * @property int $ref_id
 * @property float $total
 * @property string $descriptions
 * @property int|null $user_id
 * @property int $type
 * @property int $status
 * @property Carbon|null $updated_at
 * @property Carbon $created_at
 * 
 * @property Order $order
 * @property Purchase $purchase
 * @property User $user
 *
 * @package App\Models
 */
class Transaction extends Model
{
	protected $table = 'transactions';

	protected $casts = [
		'ref_type' => RefType::class,
		'ref_id' => 'int',
		'total' => 'float',
		'user_id' => 'int',
		'type' => 'int',
		'status' => StatusTransaction::class
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

	public function order()
	{
		return $this->belongsTo(Order::class, 'ref_id');
	}

	public function purchase()
	{
		return $this->belongsTo(Purchase::class, 'ref_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
