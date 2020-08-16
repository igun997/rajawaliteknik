<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 * 
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $username
 * @property string $password
 * @property int $level
 * @property bool $sub_level
 * @property bool $status
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|OrderCashbon[] $order_cashbons
 * @property Collection|Order[] $orders
 * @property Collection|Product[] $products
 * @property Collection|Purchase[] $purchases
 * @property Collection|Transaction[] $transactions
 *
 * @package App\Models
 */
class User extends Model
{
	protected $table = 'users';

	protected $casts = [
		'level' => 'int',
		'sub_level' => 'bool',
		'status' => 'bool'
	];

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'name',
		'email',
		'username',
		'password',
		'level',
		'sub_level',
		'status'
	];

	public function order_cashbons()
	{
		return $this->hasMany(OrderCashbon::class);
	}

	public function orders()
	{
		return $this->hasMany(Order::class);
	}

	public function products()
	{
		return $this->hasMany(Product::class);
	}

	public function purchases()
	{
		return $this->hasMany(Purchase::class);
	}

	public function transactions()
	{
		return $this->hasMany(Transaction::class);
	}
}
