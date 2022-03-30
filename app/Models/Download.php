<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    use HasFactory;

    public $timestamps = true;
    
    protected $fillable = [
        'name',
        'format',
        'path',
        'model_id',
        'model_type',
    ];
}
