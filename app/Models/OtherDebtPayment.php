<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherDebtPayment extends Model
{
    use HasFactory;

    protected $table = 'other_debts_payments';

    protected $fillable = [       
        'other_debt_id',
        'amount',
    ];

    public function otherDebt() {
        return $this->belongsTo(OtherDebt::class);
    }
}
