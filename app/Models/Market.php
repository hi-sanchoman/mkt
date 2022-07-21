<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Market extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'debt_start',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }
}
