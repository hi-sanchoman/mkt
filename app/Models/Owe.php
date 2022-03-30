<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Owe extends Model
{
    use HasFactory;

    protected $fillable = [
       
        'magazin',
        'owe_start',
        'sold',
        'paid',
        'remains',
        'realizator'

    ];

    public $timestamps = true;

    public function magazine(){
        return $this->belongsTo(Magazine::class,'magazin','id');
    }

    public function realizator(){
        return $this->belongsTo(User::class,'realizator','id');
    }
}
