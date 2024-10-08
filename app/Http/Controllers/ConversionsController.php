<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Supplier;
use App\Models\Assortment;
use App\Models\Supply;
use App\Models\Conversion;
use App\Models\MilkFat;
use App\Models\Store;
use App\Models\Weightstore;
use App\Models\Freezer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Models\Month;
use App\Models\Zakvaska;
use App\Services\ConversionService;
use Carbon\Carbon;
use DB;

class ConversionsController extends Controller
{

    public function index()
    {
        $month = Month::getCurrent();

        $page = [
            'dbAssortments' => Assortment::where('id', '!=', 25)->orderBy('num', 'asc')->get(),
            'dbZakvaskas' => Freezer::whereIsZakvaska(1)->get(),
            'dbPrice' => Weightstore::select('price')->get(),
            'dbDays' => $month->days,
            'dbMonth1' => $month,
        ];

        return Inertia::render('Conversions/Index', $page);
    }

    public function change(Request $request)
    {
        $conversionService = new ConversionService($request->month, $request->year);

        return [
            'conversions' => Conversion::selectRaw('sum(kg) as kg, assortment')
                ->whereYear('created_at', $request->year)
                ->whereMonth('created_at', $request->month)
                ->groupBy('assortment')
                ->get(),
            'rowconversions' => $conversionService->getAssortmentDayKg(),
            'oiltotal' => $conversionService->getOilTotal(),
            'total' => $conversionService->getTotal(),
        ];
    }

    /**
     * В новой переработке есть переключатель дней x
     * Он отправляет в timestamp текущую дату //
     */
    public function getByDay(Request $request)
    {
        return [
            'myconversions' => Conversion::whereDate('created_at', $request->timestamp)->get(),
            'dopZakvaskas' => Zakvaska::whereDate('created_at', $request->timestamp)->get(),
            'milkFats' => MilkFat::selectRaw('sum(kg) as kg, assortment')
                ->whereDate('created_at', $request->timestamp)
                ->groupBy('assortment')
                ->get(),
        ];
    }

    /**
     * DEPRECATED
     */
    public function forPeriod(Request $request) {
        // dd($request->all());
        $month = Month::where('completed', '0')->first();

        if(!$month){
            $month = Month::where('completed','1')->orderBy('id','desc')->first();
            if(!$month){
                $month = new Month();
                $month->month = date('m');
                $month->year = date("Y");
                $month->days = cal_days_in_month(CAL_GREGORIAN, date('m'), date("Y"));
                $month->save();
            }
        }

        $start = Carbon::createFromDate($request->start);
        $end = Carbon::createFromDate($request->end);
        $end->setTime(23, 59);
        $empty = true;

        $myconversions = Conversion::query()
            ->whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end)
            ->get();

        // foreach ($myconversions as $key => $value) {
        //     if($value->assortment == 1 || $value->assortment == 2 || $value->assortment == 3) {
        //         $empty = false;
        //         break;
        //     }
        // }


        $removals = array(4,5,6,8,12,13,17,18,20);
        $adders = array(10,11);

        $daily_total = Conversion::query()
            ->whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end)
            ->whereIn('assortment', $removals)
            ->sum('kg');

        $total = $daily_total - $daily_total = Conversion::query()
            ->whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end)
            ->whereIn('assortment', $adders)
            ->sum('kg');

        // dd([$daily_total, $total]);

        $conversions = Conversion::selectRaw('sum(kg) as kg, assortment')
            ->whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end)
            ->groupBy('assortment')
            ->get();

        $milkFats = MilkFat::selectRaw('sum(kg) as kg, assortment')
            ->whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end)
            ->groupBy('assortment')
            ->get();

        $rowconversions = Conversion::query()
            ->whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end)
            ->get();

        $assortments = Assortment::orderBy('num', 'asc')->get();

        $oiltotal = Conversion::where('assortment', 10)
            ->whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end)
            ->sum('kg');

        $data = [];
        $assort = [0,5,0,18,6,8,15,17,13,16,23,22,30,29];
        for($i = 0; $i < 14; $i++){
            foreach ($conversions as $key => $value) {
                if($value->assortment == $assort[$i]){
                    $data[] = $value->kg;
                    break;
                }
            }
            if(empty($data[$i])){
                $data[] = 0;
            }
        }

        $assortGotov = [4,5,12,19,7,9,0,0,14,0,24,22,30,29];
        for($i = 0; $i < 14; $i++){
            foreach ($conversions as $key => $value) {
                if($value->assortment == $assortGotov[$i]){
                    $dataGotov[] = $value->kg;
                    break;
                }
            }
            if(empty($dataGotov[$i])){
                $dataGotov[] = 0;
            }
        }

        $zakvaskas = Freezer::whereIsZakvaska(1)->get();

        $dopZakvaskas = [];
        $dopZakvaskas = Zakvaska::query()
            ->whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end)
            ->get();

        $daysOfPeriod = [];
        $d = Carbon::createFromDate($start->year, $start->month, $start->day);
        // $endDate = Carbon::createFromDate($end);

        while ($end->gt($d)) {
            $daysOfPeriod[] = Carbon::createFromDate($d->year, $d->month, $d->day);
            $d->addDay(1);
        }


        return [
            'assortments' => $assortments,
            'conversions' => $conversions,
            'milkFats' => $milkFats,
            'dopZakvaskas' => $dopZakvaskas,
            'myconversions' => $myconversions,
            'rows' => $rowconversions,
            'total' => $total,
            'oiltotal' => intval($oiltotal),
            'excelKg' => $data,
            'excelGotov' => $dataGotov,
            'price' => Weightstore::select('price')->get(),
            'zakvaskas' => $zakvaskas,

            'days' => $daysOfPeriod,
            'month1' => $month,
        ];
    }

    public function store(Request $request){

        $supplier = new Supplier();
        $supplier->name = $request->name;
        $supplier->tel = $request->tel;
        $supplier->address = $request->address;


        $supplier->save();

        return Redirect::route('suppliers')->with('успешно', 'Поставщик добавлен.');
    }

    public function update(Request $request){

        $supplier = Supplier::find($request->id);
        $supplier->name = $request->name;
        $supplier->tel = $request->tel;
        $supplier->address = $request->address;


        $supplier->save();

        return Redirect::route('suppliers')->with('успешно', 'Поставщик добавлен.');
    }

    public function create(){
        return Inertia::render('Suppliers/Create');
    }

    public function history(Request $request){
        $supply = Supply::where('supplier',$request->supplier)->get();

        return $supply;
    }

    public function createAssortment(Request $request){
        return $request->name;
    }

    public function save(Request $request){
        $user = Auth::user();

        $input = $request->conversions;
        $milkFats = $request->dopMilk;

        DB::beginTransaction();

        foreach($input as $key => $item) {
            if (!is_null($item)) {
                $conversion = Conversion::
                    whereYear('created_at', $request->year)
                    ->whereMonth('created_at', $request->month)
                    ->whereDay('created_at', $request->today)
                    ->where('assortment', $key)
                    ->orderBy('created_at', 'DESC')
                    ->first();

                //dd($conversion);

                if ($conversion === null) $conversion = new Conversion();

                $oldValue = $conversion->kg;

                $conversion->assortment = $key;

                $conversion->kg = $item;

                // moloko jir
                if ($key == 21) {
                    $conversion->kg = $request->slivki;
                }

                // milk
                if ($key == 4){
                    $milk = Weightstore::find(1);
                    $milk->amount = ($user->position_id == 1) ? $milk->amount + $item - $oldValue : $milk->amount + $item;
                    $milk->save();
                }

                // kefir
                else if ($key == 5) {
                    $kefir = Weightstore::find(5);
                    $kefir->amount = ($user->position_id == 1) ? $kefir->amount + $item - $oldValue : $kefir->amount + $item;
                    $kefir->save();
                }

                // smetana 20% gotovo
                else if ($key == 7) {
                    $smetana20 = Weightstore::find(7);
                    $smetana20->amount = ($user->position_id == 1) ? $smetana20->amount + $item - $oldValue : $smetana20->amount + $item;
                    $smetana20->save();
                }

                // smetana 15% gotovo
                else if ($key == 9) {
                    $smetana15 = Weightstore::find(9);
                    $smetana15->amount = ($user->position_id == 1) ? $smetana15->amount + $item - $oldValue : $smetana15->amount + $item;
                    $smetana15->save();
                }

                // ryajenka
                else if ($key == 12) {
                    $ryajenka = Weightstore::find(12);
                    $ryajenka->amount = ($user->position_id == 1) ? $ryajenka->amount + $item - $oldValue : $ryajenka->amount + $item;
                    $ryajenka->save();
                }

                // tvorog
                else if ($key == 13) {
                    $tvorog = Weightstore::find(14);
                    $tvorog->amount = ($user->position_id == 1) ? $tvorog->amount + $item - $oldValue : $tvorog->amount + $item;
                    $tvorog->save();
                }

                // brinza kg
                else if ($key == 19) {
                    $brinza = Weightstore::find(19);
                    $brinza->amount = ($user->position_id == 1) ? $brinza->amount + $item - $oldValue : $brinza->amount + $item;
                    $brinza->save();
                }

                // slivki dlya hraneniya
                else if ($key == 20) {
                    $slivki = Weightstore::find(24);
                    $slivki->amount = ($user->position_id == 1) ? $slivki->amount + $item - $oldValue : $slivki->amount + $item;
                    $slivki->save();
                }

                // toplennoe topl
                else if ($key == 24) {
                    $toplenoe = Weightstore::find(22);
                    $toplenoe->amount = ($user->position_id == 1) ? $toplenoe->amount + $item - $oldValue : $toplenoe->amount + $item;
                    $toplenoe->save();
                }

                // slivki dlya masla
                else if ($key == 15) {
                    $slivkiMaslo = Weightstore::find(24);
                    $slivkiMaslo->amount = ($user->position_id == 1) ? $slivkiMaslo->amount - $item + $oldValue : $slivkiMaslo->amount - $item;
                    $slivkiMaslo->save();
                }

                // vyhod maslo kg
                // else if ($key == 16) {
                //     $maslo = Freezer::find(15);
                //     $maslo->amount = ($user->position_id == 1) ? $maslo->amount + $item - $oldValue : $maslo->amount + $item;
                //     $maslo->save();
                // }


                // if(Weightstore::find($key)) {
                //     $weightstore = Weightstore::find($key);
                //     $weightstore->amount = $weightstore->amount + $conversion->kg;
                //     $weightstore->sum = $weightstore->price * $weightstore->amount;
                //     $weightstore->save();
                // }

                if ($request->timestamp) {$conversion->created_at = date('Y-M-d H:i:s', strtotime($request->timestamp));}

                $conversion->save();
             }
        }

        foreach($milkFats as $key => $item) {
            if (!is_null($item)) {
                // store milk fat in history
                $milkFat = MilkFat::
                    whereYear('created_at', $request->year)
                    ->whereMonth('created_at', $request->month)
                    ->whereDay('created_at', $request->today)
                    ->where('assortment', $key)
                    ->orderBy('created_at', 'DESC')
                    ->first();

                //dd($conversion);

                if ($milkFat === null) $milkFat = new MilkFat();

                $oldValue = $milkFat->kg;

                $milkFat->assortment = $key;
                $milkFat->kg = $item;

                if ($request->timestamp) {$milkFat->created_at = date('Y-M-d H:i:s', strtotime($request->timestamp));}


                $milkFat->save();


                // update weight store

                // milk
                if ($key == 4) {
                    $ws = Weightstore::find(1);
                    $ws->amount = ($user->position_id == 1) ? $ws->amount - $item + $oldValue : $ws->amount - $item;
                    $ws->save();
                }

                // kefir
                if ($key == 5) {
                    $ws = Weightstore::find(5);
                    $ws->amount = ($user->position_id == 1) ? $ws->amount - $item + $oldValue : $ws->amount - $item;
                    $ws->save();
                }

                // smetana 20% plan
                if ($key == 6) {
                    $ws = Weightstore::find(7);
                    $ws->amount = ($user->position_id == 1) ? $ws->amount - $item + $oldValue : $ws->amount - $item;
                    $ws->save();
                }

                // smetana 15% plan
                if ($key == 8) {
                    $ws = Weightstore::find(9);
                    $ws->amount = ($user->position_id == 1) ? $ws->amount - $item + $oldValue : $ws->amount - $item;
                    $ws->save();
                }

                // ryajenka
                if ($key == 12) {
                    $ws = Weightstore::find(12);
                    $ws->amount = ($user->position_id == 1) ? $ws->amount - $item + $oldValue : $ws->amount - $item;
                    $ws->save();
                }

                // tvorog obrat
                if ($key == 13) {
                    $ws = Weightstore::find(14);
                    $ws->amount = ($user->position_id == 1) ? $ws->amount - $item + $oldValue : $ws->amount - $item;
                    $ws->save();
                }

                // slivki dlya moloka, jir v kg
                if ($key == 21) {
                    $ws = Weightstore::find(21);
                    $ws->amount = ($user->position_id == 1) ? $ws->amount - $item + $oldValue : $ws->amount - $item;
                    $ws->save();
                }
            }
        }

        // slivki
        $slivki = MilkFat::
            whereYear('created_at', $request->year)
            ->whereMonth('created_at', $request->month)
            ->whereDay('created_at', $request->today)
            ->where('assortment', 21)
            ->orderBy('created_at', 'DESC')
            ->first();

        if ($slivki == null) $slivki = new MilkFat();
        $slivki->kg = $request->slivki;
        $slivki->assortment = 21;
        if ($request->timestamp) $slivki->created_at = date('Y-M-d H:i:s', strtotime($request->timestamp));


        $slivki->save();


        // zakvaskas
        $dopZakvaska = $request->dopZakvaska;

        foreach ($dopZakvaska as $assortmentId => $zakvaskas) {
            if (!is_null($zakvaskas) && !empty($zakvaskas)) {
                foreach ($zakvaskas as $key => $zakvaska) {
                    if ($zakvaska == null) continue;

                    // store milk fat in history
                    $zak = Zakvaska::
                        whereYear('created_at', $request->year)
                        ->whereMonth('created_at', $request->month)
                        ->whereDay('created_at', $request->today)
                        ->where('assortment', $assortmentId)
                        ->where('zakvaska_id', $key)
                        ->orderBy('created_at', 'DESC')
                        ->first();


                    //dd($conversion);

                    if ($zak === null) $zak = new Zakvaska();

                    $oldValue = $zak->kg;

                    $zak->assortment = $assortmentId;
                    $zak->zakvaska_id = $key;
                    $zak->kg = $zakvaska;

                    if ($request->timestamp) {
                        $zak->created_at = date('Y-M-d H:i:s', strtotime($request->timestamp));
                    }

                    $zak->save();


                    // минус закваска из морозильника

                    $zakvaskaVMorozilke = Freezer::find($key);
                    if($zakvaskaVMorozilke) {
                        $zakvaskaVMorozilke->amount -= $zakvaska - $oldValue;
                        $zakvaskaVMorozilke->save();
                    }

                    // update weight store

                    // kefir
                    if ($assortmentId == 5) {
                        $ws = Weightstore::find(5);
                        $ws->amount = ($user->position_id == 1) ? $ws->amount - $zakvaska + $oldValue : $ws->amount - $zakvaska;
                        $ws->save();
                    }

                    // smetana 20% plan
                    if ($assortmentId == 6) {
                        $ws = Weightstore::find(7);
                        $ws->amount = ($user->position_id == 1) ? $ws->amount - $zakvaska + $oldValue : $ws->amount - $zakvaska;
                        $ws->save();
                    }

                    // smetana 15% plan
                    if ($assortmentId == 8) {
                        $ws = Weightstore::find(9);
                        $ws->amount = ($user->position_id == 1) ? $ws->amount - $zakvaska + $oldValue : $ws->amount - $zakvaska;
                        $ws->save();
                    }

                    // ryajenka
                    if ($assortmentId == 12) {
                        $ws = Weightstore::find(12);
                        $ws->amount = ($user->position_id == 1) ? $ws->amount - $zakvaska + $oldValue : $ws->amount - $zakvaska;
                        $ws->save();
                    }

                    // tvorog obrat
                    if ($assortmentId == 13) {
                        $ws = Weightstore::find(14);
                        $ws->amount = ($user->position_id == 1) ? $ws->amount - $zakvaska + $oldValue : $ws->amount - $zakvaska;
                        $ws->save();
                    }

                    // slivki dlya masla
                    if ($assortmentId == 15) {
                        $ws = Weightstore::find(21);
                        $ws->amount = ($user->position_id == 1) ? $ws->amount - $zakvaska + $oldValue : $ws->amount - $zakvaska;
                        $ws->save();
                    }
                }
            }
        }


        /*
        $removals = array(4,5,6,8,12,13,17,18,20);
        $adders = array(10,11);

        $weightstore = Weightstore::all();
        for($i = 0 ; $i < count($request->conversions); $i++){
            if(!is_null($request->conversions[$i])){
                $conversion = Conversion::whereYear('created_at',$request->year)->whereMonth('created_at',$request->month)->whereDay('created_at',$request->today)->where('assortment',$i+1)->first();
                if($conversion === null)
                    $conversion = new Conversion();

                $conversion->assortment = $i+1;
                $conversion->kg = $conversion->kg + $request->conversions[$i];
                $milk = Weightstore::find(1);
                $milk->amount = $milk->amount - $request->conversions[$i];
                $milk->save();
                if(Weightstore::find($i+1)){
                    $weightstore = Weightstore::find($i+1);
                    $weightstore->amount = $weightstore->amount + $conversion->kg;
                    $weightstore->sum = $weightstore->price * $weightstore->amount;
                    $weightstore->save();
                }
                if($request->timestamp) {$conversion->created_at = date('Y-M-d H:i:s', strtotime($request->timestamp.' + 6 hours'));}
                $conversion->save();

                if (in_array($i, $removals))
                {
                    $total_milk = Weightstore::find(1);
                    $total_milk->amount = $total_milk->amount - $conversion->kg;
                    $total_milk->save();
                }else if(in_array($conversion->assortment, $adders)){
                    $total_milk = Weightstore::find(1);
                    $total_milk = $total_milk + $conversion->kg;
                    $total_milk->save();
                }

            }
        } */

        DB::commit();

        $rowconversions = Conversion::whereYear('created_at', Carbon::now()->year)
                    ->whereMonth('created_at', Carbon::now()->month)->get();

        return ['message' => 'Переработка сохранена','rows' => $rowconversions];

    }

    public function endMonth(){
        $month = Month::where('completed','0')->first();

        //проверить есть следующий месяц впереди
        if($month){
            if($month->month == date('m') && date("Y") == $month->year){
                $month->completed = 1;
                $month->save();
                return "Месяц закончен";
            }
            else if(date('m') > $month->month && date("Y") == $month->year){
                $month->completed = 1;
                $newmonth = new Month();
                $newmonth->month = $month->month + 1;
                $newmonth->year = $month->year;
                $newmonth->days = cal_days_in_month(CAL_GREGORIAN, $month->month + 1, $month->year);
                $newmonth->save();
                $month->save();
                return "Месяц закончен";
            }else{
                $month->completed = 1;
                $newmonth = new Month();
                $newmonth->month = date('m');
                $newmonth->year = date("Y");
                $newmonth->days = cal_days_in_month(CAL_GREGORIAN, date('m'), $month->year);
                $newmonth->save();
                $month->save();
                return "Месяц закончен";
            }

        }

        return "Все месяцы закончены";
    }

    public function downloadReport($mon){

		return response()->download(storage_path('conversion.xlsx'));

        $month = Month::where('month',$mon)->where('year',date("Y"))->first();
        $removals = array(4,5,6,8,12,13,17,18,20);
        $adders = array(10,11);

        $daily_total = Conversion::whereYear('created_at', $month->year)->whereMonth('created_at', Carbon::now()->month)->whereIn('assortment',$removals)->sum('kg');
        $total = $daily_total - Conversion::whereYear('created_at', $month->year)->whereMonth('created_at', $month->month)->whereIn('assortment',$adders)->sum('kg');

        $conversions = Conversion::selectRaw('sum(kg) as kg, assortment')->whereYear('created_at', $month->year)->whereMonth('created_at', $month->month)->groupBy('assortment')->get();
        $rowconversions = Conversion::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)->get();
        $assortments = Assortment::all();

        $oiltotal = Conversion::where('assortment',10)->whereYear('created_at', $month->year)->whereMonth('created_at', $month->month)->sum('kg');
        $data = [];
        $assort = [0,5,0,18,6,8,15,17,13,16,23,22,30,29];
        for($i = 0; $i < 14; $i++){
            foreach ($conversions as $key => $value) {
                // code...
                if($value->assortment == $assort[$i]){
                    $data[] = $value->kg;
                    break;
                }
            }
            if(empty($data[$i])){
                $data[] = 0;
            }
        }

        $assortGotov = [4,5,12,19,7,9,0,0,14,0,24,22,30,29];
        for($i = 0; $i < 14; $i++){
            foreach ($conversions as $key => $value) {
                // code...
                if($value->assortment == $assortGotov[$i]){
                    $dataGotov[] = $value->kg;
                    break;
                }
            }
            if(empty($dataGotov[$i])){
                $dataGotov[] = 0;
            }
        }

        return Inertia::render('Conversions/report',[
            'conversions' => $conversions,
            'oiltotal' => intval($oiltotal),
            'excelKg' => $data,
            'excelGotov' => $dataGotov,
            'price' => Weightstore::select('price')->get(),
        ]);
    }

    public function NewConversion(){
        $assortments = Assortment::all();
        $myconversions = Conversion::whereDate('created_at', Carbon::today())->get();
        $month = Month::where('completed','0')->first();
        return Inertia::render('Conversions/New_conversion',[
            'myconversions' => $myconversions,
            'days' => $month->days,
            'assortments' => $assortments
        ]);
    }

    public function updateConversionTimestamps() {
        // Retrieve all records with created_at time at 23:00:00
        $conversions = Conversion::whereTime('created_at', '23:00:00')->get();
    
        foreach ($conversions as $conversion) {
            // Add one hour to the created_at timestamp
            $newTime = Carbon::parse($conversion->created_at)->addHour();
    
            // Update the created_at timestamp
            $conversion->created_at = $newTime;
            $conversion->save();
        }
    
        return $conversions->count();
    }

    public function calculateMilk(Request $request) {
        $chosenDate = Carbon::createFromFormat('Y-m-d', $request->timestamp);
        $empty = true;

        if ($chosenDate == null) return;

        $myconversions = Conversion::whereDate('created_at', $chosenDate)->get();
        foreach ($myconversions as $key => $value) {
            # code...
            if($value->assortment == 1 || $value->assortment == 2 || $value->assortment == 3){
                $empty = false;
                break;
            }
        }

        $supplies = Supply::whereDate('created_at', $chosenDate)->get();
        // dd($supplies);

        $moloko_total = [
            'phys' => 0,
            'basic' => 0,
            'fat' => 0,
        ];

        foreach ($supplies as $key => $item) {
            $moloko_total['phys'] += $item->phys_weight;
            $moloko_total['basic'] += $item->basic_weight;
            $moloko_total['fat'] += $item->fat_kilo;
        }

        if ($empty && count($supplies)) {
            $phys_weight = new Conversion();
            $phys_weight->assortment = 1;
            $phys_weight->kg = $moloko_total['phys'];
            $phys_weight->created_at = $chosenDate;
            $phys_weight->save();

            $basic_weight = new Conversion();
            $basic_weight->assortment = 2;
            $basic_weight->kg = $moloko_total['basic'];
            $basic_weight->created_at = $chosenDate;
            $basic_weight->save();

            $fat_kilo = new Conversion();
            $fat_kilo->assortment = 3;
            $fat_kilo->kg = $moloko_total['fat'];
            $fat_kilo->created_at = $chosenDate;
            $fat_kilo->save();

            // $myconversions = Conversion::whereDate('created_at', Carbon::today())->get();

        }
        else {
            $phys_weight = Conversion::where('assortment', 1)->orderBy('id','DESC')->first();
            $phys_weight->assortment = 1;
            // $phys_weight->kg = Weightstore::where('id','1')->value('amount');
            $phys_weight->kg = $moloko_total['phys'];
            $phys_weight->save();

            $basic_weight = Conversion::where('assortment', 2)->orderBy('id','DESC')->first();
            $basic_weight->assortment = 2;
            // $basic_weight->kg = Weightstore::where('id','2')->value('amount');
            $basic_weight->kg = $moloko_total['basic'];
            $basic_weight->save();

            $fat_kilo = Conversion::where('assortment', 3)->orderBy('id','DESC')->first();
            $fat_kilo->assortment = 3;
            // $fat_kilo->kg = Weightstore::where('id','3')->value('amount');
            $fat_kilo->kg = $moloko_total['fat'];
            $fat_kilo->save();

            // $myconversions = Conversion::whereDate('created_at', Carbon::today())->get();
        }
    }

}
