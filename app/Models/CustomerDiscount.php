<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CustomerDiscount
 * 
 * @property int $id
 * @property int $customer_id
 * @property int $product_id
 * @property float $percentage_discount
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Customer $customer
 * @property Product $product
 *
 * @package App\Models
 */
class CustomerDiscount extends Model
{
	protected $table = 'customer_discounts';

	protected $casts = [
		'customer_id' => 'int',
		'product_id' => 'int',
		'percentage_discount' => 'float'
	];

	protected $fillable = [
		'customer_id',
		'product_id',
		'percentage_discount'
	];

	public function customer()
	{
		return $this->belongsTo(Customer::class);
	}

	public function product()
	{
		return $this->belongsTo(Product::class);
	}
}
