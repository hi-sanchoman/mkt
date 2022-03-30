<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;


class Report extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'realization_id',
        'user_id',
        'assortment_id',
        'order_amount',
        'amount',
        'returned',
        'defect',
        'defect_sum',
        'sold',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    
    public function assortment(){
        return $this->belongsTo(Store::class,'assortment_id','id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
