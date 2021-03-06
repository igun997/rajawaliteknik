<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Order
 * 
 * @property int $id
 * @property string $invoice_number
 * @property int $status
 * @property string|null $proof_docs
 * @property int $customer_id
 * @property int $user_id
 * @property string|null $additional_info
 * @property float $total
 * @property float|null $discount
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Customer $customer
 * @property User $user
 * @property Collection|OrderCashbon[] $order_cashbons
 * @property Collection|OrderItem[] $order_items
 *
 * @package App\Models
 */
class Order extends Model
{
	protected $table = 'orders';

	protected $casts = [
		'status' => 'int',
		'customer_id' => 'int',
		'user_id' => 'int',
		'total' => 'float',
		'discount' => 'float'
	];

	protected $fillable = [
		'invoice_number',
		'status',
		'proof_docs',
		'customer_id',
		'user_id',
		'additional_info',
		'total',
		'discount'
	];

	public function customer()
	{
		return $this->belongsTo(Customer::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function order_cashbons()
	{
		return $this->hasMany(OrderCashbon::class);
	}

	public function order_items()
	{
		return $this->hasMany(OrderItem::class);
	}
}
