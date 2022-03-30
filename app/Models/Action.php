<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    use HasFactory;

    const CALL = 1;
    const MEETING = 2;

    protected $fillable = [
        'user_id',
        'client_id',
        'type',
        'date',
    ];

    public $timestamps = true;
    
}
