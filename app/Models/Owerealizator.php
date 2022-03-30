<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Owerealizator extends Model
{
    use HasFactory;

    protected $fillable = [
       
        'realizator',
        'amount'

    ];

    public function realizator(){
        return $this->belongsTo(User::class,'realizator','id');
    }


}
