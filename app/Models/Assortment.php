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
                'price' => $item->price,
                'sold_amount' => Report::where('assortment_id',$item->id)->sum('sold'),//Report::where('assortment_id',$item->id)->count(),
                'sold_sum' => Report::where('assortment_id',$item->id)->sum('sold') * $item->price,
            ];
        }
        return $sold;
    }



    public static function sold1($month,$year) {
        $sold = [];
        $assortment = Store::orderBy('num', 'asc')->get();

        foreach($assortment as $item) {
            $sold[] = [
                'assortment' => $item->type,
                'price' => $item->price,
                'sold_amount' => Report::whereMonth('created_at','=',$month)->whereYear('created_at','=',$year)->where('assortment_id',$item->id)->sum('sold'),//Report::where('assortment_id',$item->id)->count(),
                'sold_sum' => Report::whereMonth('created_at','=',$month)->whereYear('created_at','=',$year)->where('assortment_id',$item->id)->sum('sold') * $item->price,
            ];
        }

        return $sold;
    }


    public static function defects($month,$year) {
        $res = [];
        $assortment = Store::orderBy('num', 'asc')->get();

        foreach($assortment as $item) {
            $amount = Report::whereMonth('created_at','=',$month)->whereYear('created_at','=',$year)->where('assortment_id',$item->id)->sum('amount');
            $defectAmount = Report::whereMonth('created_at','=',$month)->whereYear('created_at','=',$year)->where('assortment_id',$item->id)->sum('defect');

            $res[] = [
                'assortment' => $item->type,
                'price' => $item->price,
                'amount' => $amount,
                'defect_amount' => $defectAmount,//Report::where('assortment_id',$item->id)->count(),
                'defect_sum' => Report::whereMonth('created_at','=',$month)->whereYear('created_at','=',$year)->where('assortment_id',$item->id)->sum('defect') * $item->price,
                'total_sum' => ($amount - $defectAmount) * $item->price,
            ];
        }

        return $res;
    }

}
