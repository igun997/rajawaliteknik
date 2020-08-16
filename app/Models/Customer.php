<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Customer
 * 
 * @property int $id
 * @property string $name
 * @property string $address
 * @property bool $has_discount
 * @property float $percentage_discount
 * @property int $status
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Order[] $orders
 *
 * @package App\Models
 */
class Customer extends Model
{
	protected $table = 'customers';

	protected $casts = [
		'has_discount' => 'bool',
		'percentage_discount' => 'float',
		'status' => 'int'
	];

	protected $fillable = [
		'name',
		'address',
		'has_discount',
		'percentage_discount',
		'status'
	];

	public function orders()
	{
		return $this->hasMany(Order::class);
	}
}
