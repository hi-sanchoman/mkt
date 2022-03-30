<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class MilkFat extends Model
{
    use HasFactory;

    protected $fillable = [
        'assortment',
        'kg',
        'created_at',
    ];

    public $timestamps = true;
    


    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
