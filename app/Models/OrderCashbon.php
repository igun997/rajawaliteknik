<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OrderCashbon
 * 
 * @property int $id
 * @property int $order_id
 * @property float $total
 * @property int $user_id
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Order $order
 * @property User $user
 *
 * @package App\Models
 */
class OrderCashbon extends Model
{
	protected $table = 'order_cashbons';

	protected $casts = [
		'order_id' => 'int',
		'total' => 'float',
		'user_id' => 'int'
	];

	protected $fillable = [
		'order_id',
		'total',
		'user_id'
	];

	public function order()
	{
		return $this->belongsTo(Order::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
