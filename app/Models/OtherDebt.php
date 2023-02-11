<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherDebt extends Model
{
    use HasFactory;

    protected $fillable = [       
        'debt',
        'fio',
    ];

    public function payments() {
        return $this->hasMany(OtherDebtPayment::class);
    }
}
