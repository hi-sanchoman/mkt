<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{

	const NOT_STARTED = 0;
    const COMPLETED = 1;
    const IN_PROGRESS = 2;
    const ON_CONTROL = 3; 
	
    const TYPE_TASK = 1;
    const TYPE_MEETING = 2;

    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'auditor_id',
        'status',
        'start', 
        'deadline',
        'type', 
        'urgent', 
        'account_id',
        'file_id',
        'deal_id',
        
    ];

    public $timestamps = true;
    
    public function file()
    {
        return $this->belongsTo(Document::class,'file_id','id');
    }


    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function helper()
    {
        return $this->hasMany(Helper::class);
    }

    public function auditor(){
    	return $this->belongsTo(User::class,'auditor_id','id');
    }

    public function comments()
    {
        $comments = Comment::select('user_id','text', 'created_at')->where([
            'model_id' => $this->id,
            'model_type' => 'task',
        ])->get();

        foreach($comments as $comment) {
            $comment->user = User::card($comment->user_id);
        }

    	return $comments;
    }

    public function event()
    {
    	return $this->hasMany(Event::class);
    }

    public function subtask()
    {
        return $this->hasMany(Subtask::class);
    }

    public function pivod()
    {
        return $this->hasMany(Pivod::class);
    }
}
