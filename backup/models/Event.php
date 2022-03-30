<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Event extends Model
{
    // добавить таблицу event_types
    // добавить поля event_type_id, event_model_id

    const ACCEPTED_TASK = 1;
    const FINISHED_TASK = 2;

	use SoftDeletes;
    protected $fillable = [
        'user_id',
        'text',
        'task_id',
        'author_id',
        'seen',
    ];

    public $timestamps = true;
    
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function task()
    {
    	return $this->belongsTo(Task::class,'task_id','id');
    }    
}
