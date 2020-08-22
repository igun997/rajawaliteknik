<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OrderItem
 * 
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property float $qty
 * @property float $subtotal
 * @property float $price
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Product $product
 * @property Order $order
 *
 * @package App\Models
 */
class OrderItem extends Model
{
	protected $table = 'order_items';

	protected $casts = [
		'order_id' => 'int',
		'product_id' => 'int',
		'qty' => 'float',
		'subtotal' => 'float',
		'total_discount' => 'float',
		'price' => 'float'
	];

	protected $fillable = [
		'order_id',
		'product_id',
		'total_discount',
		'qty',
		'subtotal',
		'price'
	];

	public function product()
	{
		return $this->belongsTo(Product::class);
	}

	public function order()
	{
		return $this->belongsTo(Order::class);
	}
}
