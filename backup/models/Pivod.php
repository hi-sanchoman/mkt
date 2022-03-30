<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pivod extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'file_id',
    ];

    public $timestamps = false;

    public function task(){
    	return $this->belongsTo(Task::class,'task_id','id');
    }

    public function file(){
    	return $this->belongsTo(File::class,'file_id','id');
    }
    


}
