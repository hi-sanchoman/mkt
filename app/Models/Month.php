<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Month extends Model
{
    use HasFactory;

    protected $fillable = [
        'month',
        'year',
        'completed',
        'days'
    ];


    public $timestamps = true;

    /**
     * @return Month|null
     */
    public static function getCurrent()
    {
        $month = Month::where('completed', '0')->first();

        if(!$month) {
            $month = Month::where('completed', '1')->orderBy('id', 'desc')->first();
        }

        if(!$month) {
            $month = new Month();
            $month->month = date('m');
            $month->year = date("Y");
            $month->days = cal_days_in_month(CAL_GREGORIAN, date('m'), date("Y"));
            $month->save();
        }

        return $month;
    }

    /**
     * @return array
     */
    public static function getShortMonthsArray()
    {
        return [
			1 => 'янв',
			2 => 'фев',
			3 => 'мар',
			4 => 'апр',
			5 => 'май',
			6 => 'июн',
			7 => 'июл',
			8 => 'авг',
			9 => 'сен',
			10 => 'окт',
			11 => 'ноя',
			12 => 'дек',
		];
    }

}
