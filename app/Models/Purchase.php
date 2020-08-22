<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Purchase
 * 
 * @property int $id
 * @property string $invoice_number
 * @property int $supplier_id
 * @property int $status
 * @property float $total
 * @property int $user_id
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Supplier $supplier
 * @property User $user
 * @property Collection|PurchaseItem[] $purchase_items
 *
 * @package App\Models
 */
class Purchase extends Model
{
	protected $table = 'purchases';

	protected $casts = [
		'supplier_id' => 'int',
		'status' => 'int',
		'total' => 'float',
		'user_id' => 'int'
	];

	protected $fillable = [
		'invoice_number',
		'supplier_id',
		'status',
		'total',
		'user_id'
	];

	public function supplier()
	{
		return $this->belongsTo(Supplier::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function purchase_items()
	{
		return $this->hasMany(PurchaseItem::class);
	}
}
