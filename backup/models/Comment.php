<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    const TEXT = 1;
    const LINK = 2;
    const FILE = 3; 

    protected $fillable = [
        'user_id',
        'text',
        'model_id',
        'model_type',
        'type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

}
