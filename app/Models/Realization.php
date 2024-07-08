<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Realization extends Model
{
	use HasFactory;

    const STATUS_2 = 2; //'не знаю';
    const STATUS_5 = 5; //'Продукция изготовлена и перемещена в склад';

	protected $fillable = [
		'realizator', // Это ID реализатора
		'realization_sum',
		'defect_sum',
		'percent',
		'realizator_income',
		'bill',
		'cash',
		'income_id',
		'majit',
		'sordor',
		'kaspi',
		'income',
		'sold',
		'status',
		'is_produced',// после нажатия изготовлено
		'is_released',
		'is_accepted',
		'is_read',
	];

	public $timestamps = true;

	public function real()
	{
		return $this->belongsTo(User::class, 'realizator', 'id')->withTrashed();
	}

	public function realizator()
	{
		return $this->belongsTo(User::class, 'realizator', 'id')->withTrashed();
	}

	public function status()
	{
		return $this->belongsTo(Status::class, 'status', 'id');
	}

	public function order()
	{
		return $this->hasMany(Report::class, 'realization_id', 'id');
	}

	public function nak()
	{
		return $this->hasMany(Nak::class, 'realization_id', 'id');
	}

	public function dops()
	{
		return $this->hasMany(OrderDop::class, 'realization_id', 'id');
	}

	public function reports()
	{
		return $this->hasMany(Report::class);
	}

    public function scopeNotRead($query)
    {
        $query->where('is_read', 0);
    }

    public function scopeNotProduced($query)
    {
        $query->where('is_produced', 0);
    }

}
