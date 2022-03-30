<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 */
class Weightstore extends Model
{
	use HasFactory;

	protected $fillable = [
		'id',
		'assortment',
		'amount',
		'price',
		'sum'
	];

	public $timestamps = true;

}