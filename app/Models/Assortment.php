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
    
    public static function sold(){
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



    public static function sold1($month, $year, $realizator) {
        $sold = [];
        $assortment = Store::orderBy('num', 'asc')->get();

        foreach($assortment as $item) {
            if ($realizator) {
                $sold[] = [
                    'assortment' => $item->type,
                    'price_zavod' => $item->price_zavod,
                    'sold_amount' => Report::whereUserId($realizator['id'])->whereMonth('created_at','=',$month)->whereYear('created_at','=',$year)->where('assortment_id',$item->id)->sum('sold'),//Report::where('assortment_id',$item->id)->count(),
                    'defect_amount' => Report::whereUserId($realizator['id'])->whereMonth('created_at','=',$month)->whereYear('created_at','=',$year)->where('assortment_id',$item->id)->sum('defect'),//Report::where('assortment_id',$item->id)->count(),
                    'sold_sum' => Report::whereUserId($realizator['id'])->whereMonth('created_at','=',$month)->whereYear('created_at','=',$year)->where('assortment_id',$item->id)->sum('sold') * $item->price_zavod,
                    'defect_sum' => Report::whereUserId($realizator['id'])->whereMonth('created_at','=',$month)->whereYear('created_at','=',$year)->where('assortment_id',$item->id)->sum('defect') * $item->price_zavod,
                ];
                continue;
            }

            $sold[] = [
                'assortment' => $item->type,
                'price_zavod' => $item->price_zavod,
                'sold_amount' => Report::whereMonth('created_at','=',$month)->whereYear('created_at','=',$year)->where('assortment_id',$item->id)->sum('sold'),//Report::where('assortment_id',$item->id)->count(),
                'defect_amount' => Report::whereMonth('created_at','=',$month)->whereYear('created_at','=',$year)->where('assortment_id',$item->id)->sum('defect'),//Report::where('assortment_id',$item->id)->count(),
                'sold_sum' => Report::whereMonth('created_at','=',$month)->whereYear('created_at','=',$year)->where('assortment_id',$item->id)->sum('sold') * $item->price_zavod,
                'defect_sum' => Report::whereMonth('created_at','=',$month)->whereYear('created_at','=',$year)->where('assortment_id',$item->id)->sum('defect') * $item->price_zavod,
            ];
        }

        return $sold;
    }


    public static function defects($month,$year) {
        $res = [];

        $sum = 0;
        $defectSum = 0;
        $percent = 0;
        $income = 0;

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

        $realIds = [];
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


        // foreach($assortment as $item) {
        //     $amount = Report::whereMonth('created_at','=',$month)->whereYear('created_at','=',$year)->where('assortment_id',$item->id)->sum('amount');
        //     $defectAmount = Report::whereMonth('created_at','=',$month)->whereYear('created_at','=',$year)->where('assortment_id',$item->id)->sum('defect');

        //     $res[] = [
        //         'assortment' => $item->type,
        //         'price' => $item->price,
        //         'amount' => $amount,
        //         'defect_amount' => $defectAmount,//Report::where('assortment_id',$item->id)->count(),
        //         'defect_sum' => Report::whereMonth('created_at','=',$month)->whereYear('created_at','=',$year)->where('assortment_id',$item->id)->sum('defect') * $item->price,
        //         'total_sum' => ($amount - $defectAmount) * $item->price,
        //     ];
        // }

        return $res;
    }


    public static function _getPivotPrice($itemId, $percentAmount) {
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
