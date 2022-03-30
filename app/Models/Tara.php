<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Tara extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'amount',
        'inside',
        'total',
        'price',
        'sum',
        'need'
    ];

    public function store(){
        return $this->belongsToMany(Store::class,'tara_store');
    }

    public $timestamps = true;

}
