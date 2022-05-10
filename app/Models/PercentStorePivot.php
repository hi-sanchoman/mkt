<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PercentStorePivot extends Model
{
    use HasFactory;

    protected $table = 'percent_store';

    protected $fillable = ['store_id', 'percent_id', 'price'];
}
