<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
       
        'assortment',
        'order_amount',
        'amount',
        'status'

    ];

    public function assortment(){
        return $this->belongsTo(Store::class,'assortment','id');
    }

    public function status(){

        return $this->belongsTo(Orderstatuses::class,'status','id');
    }

    public $timestamps = true;

}
