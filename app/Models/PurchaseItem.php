<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PurchaseItem
 * 
 * @property int $id
 * @property int $purchase_id
 * @property int $product_id
 * @property float $qty
 * @property float $price
 * @property float $subtotal
 * 
 * @property Product $product
 * @property Purchase $purchase
 *
 * @package App\Models
 */
class PurchaseItem extends Model
{
	protected $table = 'purchase_items';
	public $timestamps = false;

	protected $casts = [
		'purchase_id' => 'int',
		'product_id' => 'int',
		'qty' => 'float',
		'price' => 'float',
		'subtotal' => 'float'
	];

	protected $fillable = [
		'purchase_id',
		'product_id',
		'qty',
		'price',
		'subtotal'
	];

	public function product()
	{
		return $this->belongsTo(Product::class);
	}

	public function purchase()
	{
		return $this->belongsTo(Purchase::class);
	}
}
