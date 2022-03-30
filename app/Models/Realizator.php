<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Realizator extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'tel'
    ];

    public $timestamps = true;
    


}
