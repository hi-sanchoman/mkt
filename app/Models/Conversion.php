<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;    
use DateTimeInterface;

class Conversion extends Model
{
    use HasFactory;

    protected $fillable = [
        'assortment',
        'kg',
        'created_at',
    ];

    public $timestamps = true;
    
    protected $dates = [
        'created_at',
    ];


    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
