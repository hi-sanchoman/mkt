<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 */
class Realization extends Model
{
	use HasFactory;

	protected $fillable = [
		'realizator',
		'realization_sum',
		'defect_sum',
		'percent',
		'realizator_income',
		'bill',
		'cash',
		'majit',
		'sordor',
		'kaspi',
		'income',
		'sold',
		'status'
	];

	public $timestamps = true;

	public function realizator(){
		return $this->belongsTo(User::class,'realizator','id');
	}

	public function status(){

		return $this->belongsTo(Status::class,'status','id');
	}

	public function order(){
		return $this->hasMany(Report::class,'realization_id','id');
	}

	public function nak(){
		return $this->hasMany(Nak::class,'realization_id','id');
	}
}