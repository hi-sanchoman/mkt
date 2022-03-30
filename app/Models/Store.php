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
		'sum'
	];

	public $timestamps = true;

}