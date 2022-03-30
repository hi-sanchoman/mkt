<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use SoftDeletes;
    protected $fillable = ['name','date','user','comment','file',];
    public function user()
    {
        return $this->belongsTo(User::class,'user','id');
    }
}
