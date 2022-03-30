<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Magazine extends Model
{
    use HasFactory;

    protected $fillable = [
       
        'name',
        'realizator'

    ];

    public $timestamps = true;

    public function realizator(){
        return $this->belongsTo(User::class,'realizator','id');
    }

    public static function dolgi2(){
        $dolgi = [
            [
                'name' => 'Магнум',
                'dolg_start' => Oweshop::where('id','1')->value('dolg_start'),
                'sold' => Oweshop::where('id','1')->value('sold'),
                'amount' => Oweshop::where('id','1')->value('amount'),

                'paid' => Oweshop::where('id','1')->value('paid'),
                'owe' => intval(Pivot::whereIn('magazine_id',Magazine::where('name','like','%Magnum%')->pluck('id'))->sum('sum'))-intval(Oweshop::where('id','1')->value('paid')),

                'realizator' =>  User::whereIn('id',Magazine::where('name','like','%Magnum%')->pluck('realizator'))->pluck('first_name')->implode(', ')

            ],
            [
                'name' => 'Фиркан',
                'dolg_start' => Oweshop::where('id','2')->value('dolg_start'),
                'sold' => Oweshop::where('id','2')->value('sold'),
                'amount' => Oweshop::where('id','2')->value('amount'),

                'paid' => Oweshop::where('id','2')->value('paid'),
                'owe' => intval(Pivot::whereIn('magazine_id',Magazine::where('name','like','%Фиркан%')->pluck('id'))->sum('sum'))-intval(Oweshop::where('id','2')->value('paid')),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%Фиркан%')->pluck('realizator'))->pluck('first_name')->implode(', ')
            ],
            [
                'name' => 'Супер-бум',
                'dolg_start' => Oweshop::where('id','3')->value('dolg_start'),
                'sold' => Oweshop::where('id','3')->value('sold'),
                'amount' => Oweshop::where('id','3')->value('amount'),

                'paid' => Oweshop::where('id','3')->value('paid'),
                'owe' => intval(Pivot::whereIn('magazine_id',Magazine::where('name','like','%Супер-бум%')->pluck('id'))->sum('sum'))-intval(Oweshop::where('id','3')->value('paid')),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%Супер-бум%')->pluck('realizator'))->pluck('first_name')->implode(', ')
            ],
            [
                'name' => 'ТОО Адема LTD',
                'dolg_start' => Oweshop::where('id','4')->value('dolg_start'),
                'sold' => Oweshop::where('id','4')->value('sold'),
                'amount' => Oweshop::where('id','4')->value('amount'),

                'paid' => Oweshop::where('id','4')->value('paid'),
                'owe' => intval(Pivot::whereIn('magazine_id',Magazine::where('name','like','%ТОО Адема LTD%')->pluck('id'))->sum('sum'))-intval(Oweshop::where('id','4')->value('paid')),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ТОО Адема LTD%')->pluck('realizator'))->pluck('first_name')->implode(', ')
            ],
            [
                'name' => 'ТОО Али Сауда',
                'dolg_start' => Oweshop::where('id','5')->value('dolg_start'),
                'sold' => Oweshop::where('id','5')->value('sold'),
                'amount' => Oweshop::where('id','5')->value('amount'),

                'paid' => Oweshop::where('id','5')->value('paid'),
                'owe' => intval(Pivot::whereIn('magazine_id',Magazine::where('name','like','%ТОО Али Сауда%')->pluck('id'))->sum('sum'))-intval(Oweshop::where('id','5')->value('paid')),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ТОО Али Сауда%')->pluck('realizator'))->pluck('first_name')->implode(', ')
            ],
            [
                'name' => 'ТОО Казына и К',
                'dolg_start' => Oweshop::where('id','6')->value('dolg_start'),
                'sold' => Oweshop::where('id','6')->value('sold'),
                'amount' => Oweshop::where('id','6')->value('amount'),

                'paid' => Oweshop::where('id','6')->value('paid'),
                'owe' => intval(Pivot::whereIn('magazine_id',Magazine::where('name','like','%ТОО Казына и К%')->pluck('id'))->sum('sum'))-intval(Oweshop::where('id','6')->value('paid')),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ТОО Казына и К%')->pluck('realizator'))->pluck('first_name')->implode(', ')
            ],
            [
                'name' => 'ТОО АК NIET GROUP',
                'dolg_start' => Oweshop::where('id','7')->value('dolg_start'),
                'sold' => Oweshop::where('id','7')->value('sold'),
                'amount' => Oweshop::where('id','7')->value('amount'),
                'paid' => Oweshop::where('id','7')->value('paid'),
                'owe' => intval(Pivot::whereIn('magazine_id',Magazine::where('name','like','%ТОО АК NIET GROUP%')->pluck('id'))->sum('sum'))-intval(Oweshop::where('id','7')->value('paid')),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ТОО АК NIET GROUP%')->pluck('realizator'))->pluck('first_name')->implode(', ')
            ],
            [
                'name' => 'ИП Васитова',
                'dolg_start' => Oweshop::where('id','8')->value('dolg_start'),
                'sold' => Oweshop::where('id','8')->value('sold'),
                'amount' => Oweshop::where('id','8')->value('amount'),
                'paid' => Oweshop::where('id','8')->value('paid'),
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Васитова%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Васитова%')->pluck('realizator'))->pluck('first_name')->implode(', ')
            ],
            [
                'name' => 'ИП Азимбаев Руслан Альбертович',
                'dolg_start' => Oweshop::where('id','9')->value('dolg_start'),
                'sold' => Oweshop::where('id','9')->value('sold'),
                'amount' => Oweshop::where('id','9')->value('amount'),
                'paid' => Oweshop::where('id','9')->value('paid'),
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Азимбаев Руслан Альбертович%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Азимбаев Руслан Альбертович%')->pluck('realizator'))->pluck('first_name')->implode(', ')
            ],
            [
                'name' => 'ИП Чапанова З.Х.',
                'dolg_start' => Oweshop::where('id','10')->value('dolg_start'),
                'sold' => Oweshop::where('id','10')->value('sold'),
                'amount' => Oweshop::where('id','10')->value('amount'),
                'paid' => Oweshop::where('id','10')->value('paid'),
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Чапанова З.Х.%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Чапанова З.Х.%')->pluck('realizator'))->pluck('first_name')->implode(', ')
            ],
            [
                'name' => 'АО "Санаторий Манкент"',
                'dolg_start' => Oweshop::where('id','11')->value('dolg_start'),
                'sold' => Oweshop::where('id','11')->value('sold'),
                'amount' => Oweshop::where('id','11')->value('amount'),
                'paid' => Oweshop::where('id','11')->value('paid'),
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%АО "Санаторий Манкент"%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%АО "Санаторий Манкент"%')->pluck('realizator'))->pluck('first_name')->implode(', ')
            ],
            [
                'name' => 'ИП Насыров А.',
                'dolg_start' => Oweshop::where('id','12')->value('dolg_start'),
                'sold' => Oweshop::where('id','12')->value('sold'),
                'amount' => Oweshop::where('id','12')->value('amount'),
                'paid' => Oweshop::where('id','12')->value('paid'),
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Насыров А.%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Насыров А.%')->pluck('realizator'))->pluck('first_name')->implode(', ')
            ],
            [
                'name' => 'ТОО Акбулак 2',
                'dolg_start' => Oweshop::where('id','13')->value('dolg_start'),
                'sold' => Oweshop::where('id','13')->value('sold'),
                'amount' => Oweshop::where('id','13')->value('amount'),
                'paid' => Oweshop::where('id','13')->value('paid'),
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ТОО Акбулак 2%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ТОО Акбулак 2%')->pluck('realizator'))->pluck('first_name')->implode(', ')
            ],
            [
                'name' => 'ТОО Сауле',
                'dolg_start' => Oweshop::where('id','14')->value('dolg_start'),
                'sold' => Oweshop::where('id','14')->value('sold'),
                'amount' => Oweshop::where('id','14')->value('amount'),
                'paid' => Oweshop::where('id','14')->value('paid'),
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ТОО Сауле%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ТОО Сауле%')->pluck('realizator'))->pluck('first_name')->implode(', ')
            ],
            [
                'name' => 'ТОО Gramad Retail',
                'dolg_start' => Oweshop::where('id','15')->value('dolg_start'),
                'sold' => Oweshop::where('id','15')->value('sold'),
                'amount' => Oweshop::where('id','15')->value('amount'),
                'paid' => Oweshop::where('id','15')->value('paid'),
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ТОО Gramad Retail%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ТОО Gramad Retail%')->pluck('realizator'))->pluck('first_name')->implode(', ')
            ]
            /*[
                'name' => 'Баян Сулу',
                'dolg_start' => Oweshop::where('shop','like','%Баян Сулу%')->first()->pluck('dolg_start')->get(),
                'sold' => Oweshop::where('shop','like','%Баян Сулу%')->first()->pluck('sold')->get(),
                'amount' => Oweshop::where('shop','like','%Баян Сулу%')->first()->pluck('amount')->get(),
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%Баян Сулу%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%Баян Сулу%')->pluck('realizator'))->pluck('first_name')->implode(', ')
            ],
            [
                'name' => 'ТОО Скиф Трейд',
                'dolg_start' => Oweshop::where('shop','like','%ТОО Скиф Трейд%')->first()->pluck('dolg_start')->get(),
                'sold' => Oweshop::where('shop','like','%ТОО Скиф Трейд%')->first()->pluck('sold')->get(),
                'amount' => Oweshop::where('shop','like','%ТОО Скиф Трейд%')->first()->pluck('amount')->get(),
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ТОО Скиф Трейд%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ТОО Скиф Трейд%')->pluck('realizator'))->pluck('first_name')->implode(', ')
            ],
            [
                'name' => 'ИП Казакбаева Г.А.',
                'dolg_start' => Oweshop::where('shop','like','%ИП Казакбаева Г.А.%')->first()->pluck('dolg_start')->get(),
                'sold' => Oweshop::where('shop','like','%ИП Казакбаева Г.А.%')->first()->pluck('sold')->get(),
                'amount' => Oweshop::where('shop','like','%ИП Казакбаева Г.А.%')->first()->pluck('amount')->get(),
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Казакбаева Г.А.%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Казакбаева Г.А.%')->pluck('realizator'))->pluck('first_name')->implode(', ')
            ],
            [
                'name' => 'ИП Мырзабаев Шифер завод',
                'dolg_start' => Oweshop::where('shop','like','%ИП Мырзабаев Шифер завод%')->first()->pluck('dolg_start')->get(),
                'sold' => Oweshop::where('shop','like','%ИП Мырзабаев Шифер завод%')->first()->pluck('sold')->get(),
                'amount' => Oweshop::where('shop','like','%ИП Мырзабаев Шифер завод%')->first()->pluck('amount')->get(),
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Мырзабаев Шифер завод%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Мырзабаев Шифер завод%')->pluck('realizator'))->pluck('first_name')->implode(', ')
            ],
            [
                'name' => 'ИП Цой Станислав',
                'dolg_start' => Oweshop::where('shop','like','%ИП Цой Станислав%')->first()->pluck('dolg_start')->get(),
                'sold' => Oweshop::where('shop','like','%ИП Цой Станислав%')->first()->pluck('sold')->get(),
                'amount' => Oweshop::where('shop','like','%ИП Цой Станислав%')->first()->pluck('amount')->get(),
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Цой Станислав%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Цой Станислав%')->pluck('realizator'))->pluck('first_name')->implode(', ')
            ],
            [
                'name' => 'ИП Ярмарка Продуктов',
                'dolg_start' => Oweshop::where('shop','like','%ИП Ярмарка Продуктов%')->first()->pluck('dolg_start')->get(),
                'sold' => Oweshop::where('shop','like','%ИП Ярмарка Продуктов%')->first()->pluck('sold')->get(),
                'amount' => Oweshop::where('shop','like','%ИП Ярмарка Продуктов%')->first()->pluck('amount')->get(),
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Ярмарка Продуктов%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Ярмарка Продуктов%')->pluck('realizator'))->pluck('first_name')->implode(', ')
            ],
            [
                'name' => 'ТОО Сункар өнімдері',
                'dolg_start' => Oweshop::where('shop','like','%ТОО Сункар өнімдері%')->first()->pluck('dolg_start')->get(),
                'sold' => Oweshop::where('shop','like','%ТОО Сункар өнімдері%')->first()->pluck('sold')->get(),
                'amount' => Oweshop::where('shop','like','%ТОО Сункар өнімдері%')->first()->pluck('amount')->get(),
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ТОО Сункар өнімдері%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ТОО Сункар өнімдері%')->pluck('realizator'))->pluck('first_name')->implode(', ')
            ],
            [
                'name' => 'ТОО КААN',
                'dolg_start' => Oweshop::where('shop','like','%ТОО КААN%')->first()->pluck('dolg_start')->get(),
                'sold' => Oweshop::where('shop','like','%ТОО КААN%')->first()->pluck('sold')->get(),
                'amount' => Oweshop::where('shop','like','%ТОО КААN%')->first()->pluck('amount')->get(),
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ТОО КААN%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ТОО КААN%')->pluck('realizator'))->pluck('first_name')->implode(', ')
            ],
            [
                'name' => 'ТОО Хадиша Отель',
                'dolg_start' => Oweshop::where('shop','like','%ТОО Хадиша Отель%')->first()->pluck('dolg_start')->get(),
                'sold' => Oweshop::where('shop','like','%ТОО Хадиша Отель%')->first()->pluck('sold')->get(),
                'amount' => Oweshop::where('shop','like','%ТОО Хадиша Отель%')->first()->pluck('amount')->get(),
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ТОО Хадиша Отель%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ТОО Хадиша Отель%')->pluck('realizator'))->pluck('first_name')->implode(', ')
            ],
            [
                'name' => 'ИП Таншолпан',
                'dolg_start' => Oweshop::where('shop','like','%ИП Таншолпан%')->first()->pluck('dolg_start')->get(),
                'sold' => Oweshop::where('shop','like','%ИП Таншолпан%')->first()->pluck('sold')->get(),
                'amount' => Oweshop::where('shop','like','%ИП Таншолпан%')->first()->pluck('amount')->get(),
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Таншолпан%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Таншолпан%')->pluck('realizator'))->pluck('first_name')->implode(', ')
            ],
            [
                'name' => 'ИП Фудмаркет',
                'dolg_start' => Oweshop::where('shop','like','%ИП Фудмаркет%')->first()->pluck('dolg_start')->get(),
                'sold' => Oweshop::where('shop','like','%ИП Фудмаркет%')->first()->pluck('sold')->get(),
                'amount' => Oweshop::where('shop','like','%ИП Фудмаркет%')->first()->pluck('amount')->get(),
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Фудмаркет%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Фудмаркет%')->pluck('realizator'))->pluck('first_name')->implode(', ')
            ],
            [
                'name' => 'ИП Алпамыс',
                'dolg_start' => Oweshop::where('shop','like','%ИП Алпамыс%')->first()->pluck('dolg_start')->get(),
                'sold' => Oweshop::where('shop','like','%ИП Алпамыс%')->first()->pluck('sold')->get(),
                'amount' => Oweshop::where('shop','like','%ИП Алпамыс%')->first()->pluck('amount')->get(),
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Алпамыс%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Алпамыс%')->pluck('realizator'))->pluck('first_name')->implode(', ')
            ],
            [
                'name' => 'ИП Жамал',
                'dolg_start' => Oweshop::where('shop','like','%ИП Жамал%')->first()->pluck('dolg_start')->get(),
                'sold' => Oweshop::where('shop','like','%ИП Жамал%')->first()->pluck('sold')->get(),
                'amount' => Oweshop::where('shop','like','%ИП Жамал%')->first()->pluck('amount')->get(),
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Жамал%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Жамал%')->pluck('realizator'))->pluck('first_name')->implode(', ')
            ],
            [
                'name'=>'ИП Ешжанова',
                'dolg_start' => Oweshop::where('shop','like','%ИП Ешжанова%')->first()->pluck('dolg_start')->get(),
                'sold' => Oweshop::where('shop','like','%ИП Ешжанова%')->first()->pluck('sold')->get(),
                'amount' => Oweshop::where('shop','like','%ИП Ешжанова%')->first()->pluck('amount')->get(),
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Ешжанова%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Ешжанова%')->pluck('realizator'))->pluck('first_name')->implode(', ')
            ],
            [
                'name'=>'ИП Жупар Жупар',
                'dolg_start' => Oweshop::where('shop','like','%ИП Жупар Жупар%')->first()->pluck('dolg_start')->get(),
                'sold' => Oweshop::where('shop','like','%ИП Жупар Жупар%')->first()->pluck('sold')->get(),
                'amount' => Oweshop::where('shop','like','%ИП Жупар Жупар%')->first()->pluck('amount')->get(),
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Жупар Жупар%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Жупар Жупар%')->pluck('realizator'))->pluck('first_name')->implode(', ')
            ],
            [
                'name'=>'ИП НЕМ НЕМ',
                'dolg_start' => Oweshop::where('shop','like','%ИП НЕМ НЕМ%')->first()->pluck('dolg_start')->get(),
                'sold' => Oweshop::where('shop','like','%ИП НЕМ НЕМ%')->first()->pluck('sold')->get(),
                'amount' => Oweshop::where('shop','like','%ИП НЕМ НЕМ%')->first()->pluck('amount')->get(),
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП НЕМ НЕМ%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП НЕМ НЕМ%')->pluck('realizator'))->pluck('first_name')->implode(', ')
            ],
            [
                'name'=>'ИП Олжас Олжас',
                'dolg_start' => Oweshop::where('shop','like','%ИП Олжас Олжас%')->first()->pluck('dolg_start')->get(),
                'sold' => Oweshop::where('shop','like','%ИП Олжас Олжас%')->first()->pluck('sold')->get(),
                'amount' => Oweshop::where('shop','like','%ИП Олжас Олжас%')->first()->pluck('amount')->get(),
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Олжас Олжас%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Олжас Олжас%')->pluck('realizator'))->pluck('first_name')->implode(', ')
            ],
            [
                'name' => 'ИП Байкуразова Л.Р.',
                'dolg_start' => Oweshop::where('shop','like','%ИП Байкуразова Л.Р.%')->first()->pluck('dolg_start')->get(),
                'sold' => Oweshop::where('shop','like','%ИП Байкуразова Л.Р.%')->first()->pluck('sold')->get(),
                'amount' => Oweshop::where('shop','like','%ИП Байкуразова Л.Р.%')->first()->pluck('amount')->get(),
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Байкуразова Л.Р.%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Байкуразова Л.Р.%')->pluck('realizator'))->pluck('first_name')->implode(', ')
            ],
            [
                'name'=>'ИП Ким Е.В.',
                'dolg_start' => Oweshop::where('shop','like','%ИП Ким Е.В.%')->first()->pluck('dolg_start')->get(),
                'sold' => Oweshop::where('shop','like','%ИП Ким Е.В.%')->first()->pluck('sold')->get(),
                'amount' => Oweshop::where('shop','like','%ИП Ким Е.В.%')->first()->pluck('amount')->get(),
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Ким Е.В.%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Ким Е.В.%')->pluck('realizator'))->pluck('first_name')->implode(', ')
            ],
            [
                'name'=>'ИП Халилов С.',
                 'dolg_start' => Oweshop::where('shop','like','%ИП Халилов С.%')->first()->pluck('dolg_start')->get(),
                'sold' => Oweshop::where('shop','like','%ИП Халилов С.%')->first()->pluck('sold')->get(),
                'amount' => Oweshop::where('shop','like','%ИП Халилов С.%')->first()->pluck('amount')->get(),
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Халилов С.%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Халилов С.%')->pluck('realizator'))->pluck('first_name')->implode(', ')
            ],*/
        ];
        return $dolgi;
    }

    public static function dolgi(){
        $dolgi = [
            [
                'name' => 'Магнум',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%Magnum%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%Magnum%')->pluck('realizator'))->get()

            ],
            [
                'name' => 'Фиркан',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%Фиркан%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%Фиркан%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'Супер-бум',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%Супер-бум%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%Супер-бум%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ТОО Адема LTD',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ТОО Адема LTD%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ТОО Адема LTD%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ТОО Али Сауда',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ТОО Али Сауда%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ТОО Али Сауда%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ТОО Казына и К',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ТОО Казына и К%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ТОО Казына и К%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ТОО АК NIET GROUP',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ТОО АК NIET GROUP%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ТОО АК NIET GROUP%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ИП Васитова',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Васитова%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Васитова%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ИП Азимбаев Руслан Альбертович',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Азимбаев Руслан Альбертович%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Азимбаев Руслан Альбертович%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ИП Чапанова З.Х.',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Чапанова З.Х.%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Чапанова З.Х.%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'АО "Санаторий Манкент"',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%АО "Санаторий Манкент"%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%АО "Санаторий Манкент"%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ИП Насыров А.',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Насыров А.%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Насыров А.%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ТОО Акбулак 2',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ТОО Акбулак 2%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ТОО Акбулак 2%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ТОО Сауле',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ТОО Сауле%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ТОО Сауле%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ТОО Gramad Retail',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ТОО Gramad Retail%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ТОО Gramad Retail%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'Баян Сулу',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%Баян Сулу%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%Баян Сулу%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ТОО Скиф Трейд',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ТОО Скиф Трейд%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ТОО Скиф Трейд%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ИП Казакбаева Г.А.',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Казакбаева Г.А.%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Казакбаева Г.А.%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ИП Мырзабаев Шифер завод',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Мырзабаев Шифер завод%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Мырзабаев Шифер завод%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ИП Цой Станислав',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Цой Станислав%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Цой Станислав%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ИП Ярмарка Продуктов',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Ярмарка Продуктов%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Ярмарка Продуктов%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ТОО Сункар өнімдері',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ТОО Сункар өнімдері%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ТОО Сункар өнімдері%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ТОО КААN',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ТОО КААN%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ТОО КААN%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ТОО Хадиша Отель',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ТОО Хадиша Отель%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ТОО Хадиша Отель%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ИП Таншолпан',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Таншолпан%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Таншолпан%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ИП Фудмаркет',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Фудмаркет%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Фудмаркет%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ИП Алпамыс',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Алпамыс%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Алпамыс%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ИП Жамал',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Жамал%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Жамал%')->pluck('realizator'))->get()
            ],
            [
                'name'=>'ИП Ешжанова',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Ешжанова%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Ешжанова%')->pluck('realizator'))->get()
            ],
            [
                'name'=>'ИП Жупар Жупар',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Жупар Жупар%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Жупар Жупар%')->pluck('realizator'))->get()
            ],
            [
                'name'=>'ИП НЕМ НЕМ',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП НЕМ НЕМ%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП НЕМ НЕМ%')->pluck('realizator'))->get()
            ],
            [
                'name'=>'ИП Олжас Олжас',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Олжас Олжас%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Олжас Олжас%')->pluck('realizator'))->get()
            ],
            [
                'name' => 'ИП Байкуразова Л.Р.',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Байкуразова Л.Р.%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Байкуразова Л.Р.%')->pluck('realizator'))->get()
            ],
            [
                'name'=>'ИП Ким Е.В.',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Ким Е.В.%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Ким Е.В.%')->pluck('realizator'))->get()
            ],
            [
                'name'=>'ИП Халилов С.',
                'owe' => Pivot::whereIn('magazine_id',Magazine::where('name','like','%ИП Халилов С.%')->pluck('id'))->sum('sum'),
                'realizator'=> User::whereIn('id',Magazine::where('name','like','%ИП Халилов С.%')->pluck('realizator'))->get()
            ],
        ];

        return $dolgi;
    }
}
