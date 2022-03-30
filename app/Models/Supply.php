<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Supply extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier',
        'phys_weight',
        'fat',
        'acid',
        'density',
        'basic_weight',
        'fat_kilo',
        'price',
        'sum',
    ];

    public $timestamps = true;
    
    public function supplier(){
        return $this->belongsTo(Supplier::class,'supplier','id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

}
