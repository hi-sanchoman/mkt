<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
       
        'user',
        'sum',
        'description',
        'category_id',
        'kassa'

    ];

    public function category(){
        return $this->belongsTo(Category::class,'category_id','id');
    }

    public $timestamps = true;

}
