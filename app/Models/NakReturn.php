<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NakReturn extends Model
{
    use HasFactory;

    protected $fillable = ['oweshop_id', 'realization_id', 'sum'];

    public function oweshop() {
        return $this->belongsTo(Oweshop::class);
    }

    public function realization() {
        return $this->belongsTo(Realization::class);
    } 
}
