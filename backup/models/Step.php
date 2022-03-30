<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', // название действия / документа
        'type', // тип действия ( прикрепление документа, перевод на след)
        'resp_pos_id', // ответственная позиция
        'status_id', // к относится
    ];


}
