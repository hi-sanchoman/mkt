<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Grocery extends Model
{
    use HasFactory;

    protected $fillable = [
        'nak_id',
        'assortment_id',
        'amount',
        'brak',
        'price',
        'sum',
        'correct'
    ];

    public function assortment_id(){
        return $this->belongsTo(Store::class);
    }

    public function nak_id(){
        return $this->belongsTo(Nak::class);
    }

    public $timestamps = true;

}
