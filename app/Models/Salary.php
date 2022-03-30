<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;

    protected $fillable = [
        'worker_id',
        'days',
        'income',
        'OSMS',
        'IPN',
        'OPV',
        'total_income',
        'initial_saldo',
        'end_saldo',
        'finished'
    ];

    public function worker(){
        return $this->belongsTo(Worker::class,'worker_id','id');
    }

    public $timestamps = true;

}
