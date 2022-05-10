<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Percent extends Model
{
    use HasFactory;

    protected $fillable = ['amount'];

    public function items()
    {
        return $this->belongsToMany(Store::class);
    }
}
