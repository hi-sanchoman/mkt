<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    public $timestamps = true;

    const CALL = 1; 
    const MEETING = 2; 
    const DEAL = 3; 
    const CLIENT = 4; 

    protected $fillable = [
        'user_id',
        'type',
        'value',
        'month',
        'year',
    ];

    
    
}
