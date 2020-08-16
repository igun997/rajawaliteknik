<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Casts\StatusOrder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Order
 * 
 * @property int $id
 * @property string $invoice_number
 * @property int $status
 * @property int $customer_id
 * @property int $user_id
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Customer $customer
 * @property User $user
 * @property Collection|OrderItem[] $order_items
 * @property Collection|Transaction[] $transactions
 *
 * @package App\Models
 */
class Order extends Model
{
	protected $table = 'orders';

	protected $casts = [
		'status' => StatusOrder::class,
		'customer_id' => 'int',
		'user_id' => 'int'
	];

	protected $fillable = [
		'invoice_number',
		'status',
		'proof_docs',
		'customer_id',
		'user_id'
	];

	public function customer()
	{
		return $this->belongsTo(Customer::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function order_items()
	{
		return $this->hasMany(OrderItem::class);
	}

	public function transactions()
	{
		return $this->hasMany(Transaction::class, 'ref_id');
	}
}
