<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Helper extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'task_id',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function task()
    {
        return $this->belongsTo(Task::class,'task_id','id');
    }

}
