<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'city_id',
        'market_id',
        'initial_debt',
        'paid',
        'debt',
        'sold',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $hidden = ['pivot'];

    public function realizators() {
        return $this->belongsToMany(User::class, 'pivot_branch_realizator');
    }

    public function pivots() {
        return $this->hasMany(Pivot::class, 'magazine_id');
    }
}
