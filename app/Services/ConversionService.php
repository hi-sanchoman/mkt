<?php

namespace App\Services;

use App\Models\Conversion;
use App\Models\Month;
use Carbon\Carbon;

class ConversionService
{
    public $month;
    public $year;

    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    /**
     * @return int|float|double
     */
    public function getTotal()
    {
        // Assortiment id
        $removals = [4,5,6,8,12,13,17,18,20];
        $adders = [10,11];

        $daily_total = Conversion::whereYear('created_at', $this->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereIn('assortment', $removals)
            ->sum('kg');

        $removeFromTotal = Conversion::whereYear('created_at', $this->year)
            ->whereMonth('created_at', $this->month)
            ->whereIn('assortment', $adders)
            ->sum('kg');

        return $daily_total - $removeFromTotal;
    }


    /**
     * [
     * ...[assortment_id][day]: kg
     * ]
     * @return array
     */
    public function getAssortmentDayKg() //
    {
        $rowconversions = Conversion::selectRaw('sum(kg) as kg, assortment, EXTRACT(DAY FROM created_at) as day')
            ->whereYear('created_at', $this->year)
            ->whereMonth('created_at', $this->month)
            ->groupBy('assortment', 'day')
            ->get();

        $rows = [];
        foreach($rowconversions as $con) {
            $rows[$con->assortment][$con->day] = $con->kg;
        }

        return $rows;
    }

    public function getOilTotal()
    {
        return Conversion::where('assortment', 10)
            ->whereYear('created_at', $this->month)
            ->whereMonth('created_at', $this->year)
            ->sum('kg');
    }

}
