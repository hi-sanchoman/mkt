<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Zakvaska extends Model
{
    use HasFactory;

    protected $fillable = [
        'assortment',
        'zakvaska_id',
        'kg',
        'created_at',
    ];

    public $timestamps = true;
    


    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
