<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'account_id',
        'editors',
        'logo_path',
    ];

    public function users(){
    	return $this->hasMany(GroupUser::class,'group_id','id');
    }

}
