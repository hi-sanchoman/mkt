<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 */
class Store extends Model
{
	use HasFactory;

	protected $fillable = [
		'id',
		'type',
		'amount',
		'price',
		'sum',
		'num',
		'price_zavod',
	];

	public $timestamps = true;

	public function taras() {
		return $this->belongsToMany(Tara::class, 'tara_store');
	}

}