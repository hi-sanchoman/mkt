<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    const DIRECTOR = 1; // директор
    const TECHNICIAN = 2; // технолог
    const DISTRIBUTOR = 3; // реализатор
    const WORKER = 4; // рабочий
    const FACTORY_WORKER = 5; // цех
    const ACCOUNTANT = 6; // бухгалтер
    const FACTORY_MANAGER = 7; // начальник цеха

    public $timestamps = true;

    protected $fillable = [
        'name',
    ];
}
