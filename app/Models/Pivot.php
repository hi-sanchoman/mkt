<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Pivot extends Model
{
    use HasFactory;

    protected $fillable = [
        'realization_id',
        'magazine_id',
        'sum',
        'cash',
    ];
    
    public function magazine(){
        return $this->belongsTo(Magazine::class,'magazine_id','id');
    }

    public function realization(){
        return $this->belongsTo(Realization::class,'realization_id','id');
    }
}
