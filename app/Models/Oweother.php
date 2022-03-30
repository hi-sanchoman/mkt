<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Oweother extends Model
{
    use HasFactory;

    protected $fillable = [
       
        'name',
        
        'amount',

    ];

    public $timestamps = true;

}
