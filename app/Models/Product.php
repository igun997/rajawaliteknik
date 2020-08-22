<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 * 
 * @property int $id
 * @property string $name
 * @property int $size_id
 * @property float $price
 * @property float $stock
 * @property bool $status
 * @property int $user_id
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * 
 * @property ProductSize $product_size
 * @property User $user
 * @property Collection|CustomerDiscount[] $customer_discounts
 * @property Collection|OrderItem[] $order_items
 * @property Collection|PurchaseItem[] $purchase_items
 *
 * @package App\Models
 */
class Product extends Model
{
	protected $table = 'products';

	protected $casts = [
		'size_id' => 'int',
		'price' => 'float',
		'stock' => 'float',
		'status' => 'int',
		'user_id' => 'int'
	];

	protected $fillable = [
		'name',
		'size_id',
		'price',
		'stock',
		'status',
		'user_id'
	];

	public function product_size()
	{
		return $this->belongsTo(ProductSize::class, 'size_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function customer_discounts()
	{
		return $this->hasMany(CustomerDiscount::class);
	}

	public function order_items()
	{
		return $this->hasMany(OrderItem::class);
	}

	public function purchase_items()
	{
		return $this->hasMany(PurchaseItem::class);
	}
}
