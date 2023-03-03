<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Nak extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'shop_id',
        'consegnation',
        'realization_id',
        'finished',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function grocery()
    {
        return $this->hasMany(Grocery::class, 'nak_id', 'id');
    }

    public function shop()
    {
        return $this->hasOne(Branch::class, 'id', 'shop_id');
    }

    public function realization()
    {
        return $this->belongsTo(Realization::class, 'id', 'realization_id');
    }

    public function scopeNotFinished($query)
    {
        $query->where('finished', 0);
    }

    public static function notFinishedAmount()
    {
        return self::where('finished', '0')->count();
    }
}
