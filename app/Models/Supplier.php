<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Supplier
 * 
 * @property int $id
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Purchase[] $purchases
 *
 * @package App\Models
 */
class Supplier extends Model
{
	protected $table = 'suppliers';

	protected $fillable = [
		'name'
	];

	public function purchases()
	{
		return $this->hasMany(Purchase::class);
	}
}
