<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;


class Report extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'realization_id',
        'nak_id',
        'user_id',
        'assortment_id',
        'order_amount',
        'amount',
        'returned',
        'defect',
        'defect_sum',
        'sold',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function assortment()
    {
        return $this->belongsTo(Store::class, 'assortment_id', 'id')->orderBy('num', 'asc');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public static function getOnDate($month, $year, $distributorId = null)
    {
        $reports = $distributorId
            ? Report::where('user_id', $distributorId)
            : Report::query();

        return $reports
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->get();
    }
}
