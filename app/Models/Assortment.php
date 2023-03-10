<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assortment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public $timestamps = true;

    public static function sold()
    {
        $sold = [];
        $assortment = Store::orderBy('num', 'asc')->get();

        foreach($assortment as $item){
            $sold[] = [
                'assortment' => $item->type,
                'price_zavod' => $item->price_zavod,
                'sold_amount' => Report::where('assortment_id',$item->id)->sum('sold'),//Report::where('assortment_id',$item->id)->count(),
                'sold_sum' => Report::where('assortment_id',$item->id)->sum('sold') * $item->price_zavod,
            ];
        }

        return $sold;
    }

    public static function soldAll($month, $year)
    {
        $assortment = Store::orderBy('num', 'asc')->get();
        $reports =  Report::getSoldAndDefectOnMonth($month, $year);

        return self::formSoldInfo($assortment, $reports);
    }

    public static function soldByDistributor($distributorId, $month, $year)
    {
        $assortment = Store::orderBy('num', 'asc')->get();
        $reports = Report::getSoldAndDefectOnMonth($month, $year, $distributorId);

        return self::formSoldInfo($assortment, $reports);
    }

    /**
     * Form Sold info from Store::class and Report::class Collections
     *
     * @return array
     */
    public static function formSoldInfo($assortment, $reports ,$rep = null)
    {
        $sold = [];

        foreach($assortment as $item) {
            $report = $reports->where('assortment_id', $item->id)->first();

            $sold_amount = $report ? $report->sold : 0;
            $defect_amount = $report ? $report->defect : 0;

            $sold[] = [
                'assortment'    => $item->type,
                'price_zavod'   => $item->price_zavod,
                'sold_amount'   => $sold_amount,
                'defect_amount' => $defect_amount,
                'sold_sum'      => $sold_amount * $item->price_zavod,
                'defect_sum'    => $defect_amount * $item->price_zavod,
            ];
        }

        return $sold;
    }

    public static function defects($month,$year)
    {
        $res = [];

        $items = Store::orderBy('num', 'asc')->get();

        foreach ($items as $item) {
            $res[$item->id] = [
                'assortment' => $item->type,
                'sum' => 0,
                'defectSum' => 0,
                'percent' => 0,
                'income' => 0,
            ];
        }

        $realizations = Realization::query()
            ->with('reports')
            ->whereMonth('created_at', '=', $month)
            ->whereYear('created_at', '=', $year)
            ->where('is_released', 1)
            ->get();

        foreach ($realizations as $real) {
            foreach ($real->reports as $report) {
                $res[$report->assortment_id]['sum'] += $report->sold * Assortment::_getPivotPrice($report->assortment_id, intval($real->percent));
                $res[$report->assortment_id]['defectSum'] += ($report->defect) * Assortment::_getPivotPrice($report->assortment_id, $real->percent);

                if ($res[$report->assortment_id]['sum'] == 0) {
                    $res[$report->assortment_id]['percent'] = 0;
                } else {
                    $res[$report->assortment_id]['percent'] = ($res[$report->assortment_id]['defectSum'] / $res[$report->assortment_id]['sum']) * 100;
                }

                $res[$report->assortment_id]['income'] = ceil($res[$report->assortment_id]['sum'] - $res[$report->assortment_id]['sum'] / intval($real->percent));
            }
        }

        return $res;
    }


    public static function _getPivotPrice($itemId, $percentAmount)
    {
        $pivotPrices = PercentStorePivot::get();
		$percent = Percent::where('amount', $percentAmount)->first();

		foreach ($pivotPrices as $pivot) {
			if ($pivot->percent_id == $percent->id && $pivot->store_id == $itemId) {
				return $pivot->price;
			}
		}

		return 0;
    }
}
