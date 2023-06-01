<?php

namespace App\Services;

use App\Models\Weightstore;
use Carbon\Carbon;

class WeightstoreService
{      
    /**
     *  Таблица ресурсов
     *  StoreId => [ WeightstoreId => Вес в кг ]
     */
    private $table = [
        2 => [ 12 => 0.5 ], // Ряженка 4% 500 гр.
        3 => [ 5 => 0.9 ], // Кефир 2,5% 900 гр.
        4 => [ 5 => 0.5 ], // "Кефир 2,5% 500 гр."
        6 => [ 14 => 0.25 ], // "Творог 4% 250 гр."
        7 => [ 14 => 0.3 ], // "Творог 4% 300 гр."
        8 => [ 14 => 0.5 ], //"Творог вес 4% 500 гр."
        22 => [ 14 => 0.5 ], // "Творог 4% 500 гр. (пакет)"
        9 => [ 7 => 0.2 ], // "Сметана 20% 200 гр."
        10 => [ 7 => 0.4 ], // "Сметана 20% 400 гр."
        12 => [ 7 => 0.5 ], // "Сметана ведро 20% 500 гр."
        13 => [ 9 => 0.5 ], // "Сметана 15% 500 гр."
        14 => [ 19 => 1.0 ], // "Брынза вес."
        15 => [ 26 => 0.5 ], // "Сл. масло 72,5% 500 гр."
        16 => [ 27 => 0.3 ], // "Сл. масло 72,5% 300 гр."
        17 => [ 28 => 0.3 ], // "Сл. масло 72,5% конт. 300 гр."
        18 => [ 22 => 0.3 ], // "Топленое масло 300 гр."
        19 => [ 29 => 0.3 ], // "Тотыра 300 гр."
        33 => [ 25 => 1.0 ], // "Курт вес кг."
        20 => [ 25 => 1.0 ], // "Курт вакуум Вес."
        21 => [ 30 => 1.0 ], // "Чечил вакуум Вес."
        23 => [ 7 => 1.0 ], // "Сметана 20 % вес."
        24 => [ 14 => 1.0 ], // "Творог 4% вес"
        25 => [ 1 => 1.0 ], // "Молоко 2,5% вес"
        26 => [ 5 => 1.0 ], // "Кефир 2,5% вес"
        27 => [ 12 => 1.0 ], // "Ряженка 4% вес"
        28 => [ 9 => 1.0 ], // "Сметана 15% вес"
        29 => [ 23 => 1.0 ], // "Сл. масло 72,5% вес"
        30 => [ 5 => 1.0 ], // "Кефир 2,5% 200 гр."
    ];

    public function createProduct(int $assortment_id, int $amount)
    {
        if(!array_key_exists($assortment_id, $this->table)) return null;
        $ingridients = $this->table[$assortment_id];
        
        foreach($ingridients as $weightstoreID => $weight) {

            $w = Weightstore::find($weightstoreID); if(!$w) continue;
            $w->amount = $w->amount - $weight * $amount;
            $w->save();

        }
    }

}