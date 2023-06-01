<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDop extends Model
{
    use HasFactory;

    const STATUS_1 = -1; // пока не знаю
    const ACCEPTED = 1; // принято
    const DECLINED = 2; // отклонено
    
    protected $table = 'orders_dop';

    public $timestamps = true;

    protected $fillable = [
        'realization_id',
        'assortment',
        'order_amount',
        'amount',
        'status'
    ];

    public function assortment()
    {
        return $this->belongsTo(Store::class,'assortment','id')->orderBy('num', 'asc');
    }

    public function status()
    {
        return $this->belongsTo(Orderstatuses::class,'status','id');
    }
}
