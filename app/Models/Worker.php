<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'surname',
        'salary',
        'saldo',
    ];

    public function salary(){
        return $this->hasMany(Salary::class,'worker_id','id');
    }

    public function lastSalary(){
        return Salary::where('worker_id',$this->id)->orderBy('id','DESC')->first();
    }

    public $timestamps = true;

    public function getSalary() {
        return $this->salary;
    }

}
