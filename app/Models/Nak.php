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

    public static function withShopAndSum(int $realization_id)
    {
        $naks = Nak::where('realization_id', $realization_id)->with(['grocery', 'shop', 'user'])->get();

        $groceries = Grocery::whereIn('nak_id', $naks->pluck('id')->toArray())->get();
        $stores = Store::get();

        foreach ($naks as $key => $nak) {
            //$nak->sum = self::sum($nak);
            $nak->sum = self::sumEasy($nak, $groceries, $stores);
        }

        return $naks;
    }

    public static function sum(Nak $nak) {

        $grocery = Grocery::where('nak_id', $nak->id)->get();

        $totalSum = 0;

        $stores = Store::get();
        
		foreach ($grocery as $key => $item) {
			$p = $stores->where('id', $item['assortment_id'])->first();
            $p = $p ? $p->type : '';

			$table[] = [
				$key + 1,
				$p,
				$item['amount'],
				$item['brak'],
				$item['price'],
				$item['sum'],
			];

			$totalSum += $item['sum'];
		}

        return $totalSum;
    }

}
