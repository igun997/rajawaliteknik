<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductSize
 * 
 * @property int $id
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Product[] $products
 *
 * @package App\Models
 */
class ProductSize extends Model
{
	protected $table = 'product_sizes';

	protected $fillable = [
		'name'
	];

	public function products()
	{
		return $this->hasMany(Product::class, 'size_id');
	}
}
