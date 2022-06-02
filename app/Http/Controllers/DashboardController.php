<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Supply;
use App\Models\Supplier;
use App\Models\Store;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Weightstore;

class DashboardController extends Controller
{
    public function index()
    {   
        // dd(Auth::user());
        if (Auth::user()->position_id == 7) {
            return redirect()->route('conversions');
        }

        if (Auth::user()->position_id == 3) {
            return redirect()->route('realizators');
        }

        if (Auth::user()->position_id == 5) {
            return redirect()->route('sales');
        }

        $supplies = Supply::whereDate('created_at', Carbon::today())
            ->with('supplier')
            ->orderBy('created_at', 'DESC')
            ->get();

        $combinedArr = [];

        foreach ($supplies as $supply) {
            

            if (!isset($combinedArr[$supply->supplier])) {
                // dd($supply->supplier);

                $combinedArr[$supply->supplier] = [
                    'supplier' => null,
                    'phys_weight' => 0,
                    'fat' => 0,
                    'acid' => 0,
                    'density' => 0,
                    'basic_weight' => 0,
                    'fat_kilo' => 0,
                    'price' => 0,
                    'sum' => 0,
                    'created_at' => '',
                ];
            }

            $combinedArr[$supply->supplier]['supplier'] = $supply->supplier()->first();
            $combinedArr[$supply->supplier]['phys_weight'] += $supply->phys_weight;
            $combinedArr[$supply->supplier]['fat'] = $supply->fat;
            $combinedArr[$supply->supplier]['acid'] = $supply->acid;
            $combinedArr[$supply->supplier]['density'] = $supply->density;
            $combinedArr[$supply->supplier]['basic_weight'] += $supply->basic_weight;
            $combinedArr[$supply->supplier]['fat_kilo'] += $supply->fat_kilo;
            $combinedArr[$supply->supplier]['price'] = $supply->price;
            $combinedArr[$supply->supplier]['sum'] += $supply->sum;
            $combinedArr[$supply->supplier]['created_at'] = $supply->created_at;
        }

        $combined = [];
        foreach ($combinedArr as $item) {
            $combined[] = $item;
        }

        // dd($combined);
            
        $sum = Supply::whereDate('created_at', Carbon::today())->sum('sum');
        $fat_kilo = Supply::whereDate('created_at', Carbon::today())->sum('fat_kilo');
        $basic_weight = Supply::whereDate('created_at', Carbon::today())->sum('basic_weight');
        $phys_weight = Supply::whereDate('created_at', Carbon::today())->sum('phys_weight');

        $suppliers = Supplier::all();

        return Inertia::render('Dashboard/Index',[
            'supplies' => $supplies ? $supplies : null,
            'combinedSrc' => $combined,
            'suppliers' => $suppliers ? $suppliers : null,
            'phys_weight' => $phys_weight ? $phys_weight : null,
            'basic_weight' => $basic_weight ? $basic_weight : null,
            'fat_kilo' => $fat_kilo ? $fat_kilo : null,
            'sum' => $sum ? $sum : null
        ]);
    }

    public function store(Request $request){

        // dd($request->all());
        
        $supply = new Supply();
        $supply->supplier = $request->supplier;
        $supply->phys_weight = $request->phys_weight;
        $supply->fat = $request->fat;
        $supply->acid = $request->acid;
        $supply->density = $request->density;
        $supply->basic_weight = $request->phys_weight / 3.6 * $request->fat;
        $supply->fat_kilo = ($request->phys_weight * $request->fat) / 100;
        $supply->price = $request->price;
        $supply->sum = $request->price * ($request->phys_weight / 3.6 * $request->fat);

        if ($request->type == 1) {
            $supply->created_at = Carbon::tomorrow();
        }

        $supply->save();

        // if ($request->type == 1) {
        //     $supply->update([
        //         'created_at' => Carbon::tomorrow(),
        //         'updated_at' => Carbon::tomorrow(),
        //     ]);
        // }

        /*$store = Weightstore::find(1);
        $store->sum = ($store->amount + $request->phys_weight) * $store->price;
        $store->amount += $request->phys_weight;
        $store->save();
        
        $store1 = Weightstore::find(2);
        $store1->sum = ($request->phys_weight/3.6*$request->fat)*$store1->price;
        $store1->amount += $request->phys_weight/3.6*$request->fat;
        $store1->save();

        $store2 = Weightstore::find(3);
        $store2->sum = (($request->phys_weight*$request->fat)/100)*$store2->price;
        $store2->amount += ($request->phys_weight*$request->fat)/100;
        $store2->save();*/

        //dobavlyaem v rashody
        $expense = new Expense();
        $expense->user = Supplier::find($supply->supplier)->name;
        $expense->sum = $supply->sum;
        $expense->category_id = 4;
        $expense->kassa = 9;
        $expense->description = 'поставка молока';
        $expense->save();

        //return Redirect::route('dashboard')->with('успешно', 'Поставка добавлена.');
        return $request->type == 0 ? $supply : null;
    }

    public function getSupplies(Request $request){
        
        $date = date_create($request->date);
        $format = date_format($date, 'm/d/Y');
        // dd($format);

        //date_add($date, date_interval_create_from_date_string('1 day'));
       
        
        $supplies = Supply::whereDate('created_at', $date)->with('supplier')->orderBy('created_at')->get();
        
        $combined = $this->_getCombined($supplies);
        
        $sum = Supply::whereDate('created_at', $date)->sum('sum');
        $fat_kilo = Supply::whereDate('created_at', $date)->sum('fat_kilo');
        $basic_weight = Supply::whereDate('created_at', $date)->sum('basic_weight');
        $phys_weight = Supply::whereDate('created_at', $date)->sum('phys_weight');

        $suppliers = Supplier::all();
        return [
            'supplies' => $supplies,
            'combined' => $combined,
            'suppliers' => $suppliers,
            'phys_weight' => $phys_weight,
            'basic_weight' => $basic_weight,
            'fat_kilo' => $fat_kilo,
            'sum' => $sum
        ];

    }

    public function getMonth(Request $request){
        $month = $request->month+1;
        $supply = Supply::whereMonth('created_at', $month)->with('supplier')->get();
        $sum = Supply::whereMonth('created_at', $month)->sum('sum');
        $fat_kilo = Supply::whereMonth('created_at', $month)->sum('fat_kilo');
        $basic_weight = Supply::whereMonth('created_at', $month)->sum('basic_weight');
        $phys_weight = Supply::whereMonth('created_at', $month)->sum('phys_weight');

        return [
            'mysupplies' => $supply,
            'phys_weight' => $phys_weight,
            'basic_weight' => number_format((float)$basic_weight, 2, '.', ''),
            'fat_kilo' => number_format((float)$fat_kilo, 2, '.', ''),
            'sum' => $sum
        ];
    }

    public function NewSupply(){
        $suppliers = Supplier::all();

        return Inertia::render('Dashboard/New_supply',[
            'suppliers' => $suppliers
        ]);
    }

    private function _getCombined($supplies) {
        $combinedArr = [];

        foreach ($supplies as $supply) {
            if (!isset($combinedArr[$supply->supplier])) {
                $combinedArr[$supply->supplier] = [
                    'supplier' => null,
                    'phys_weight' => 0,
                    'fat' => 0,
                    'acid' => 0,
                    'density' => 0,
                    'basic_weight' => 0,
                    'fat_kilo' => 0,
                    'price' => 0,
                    'sum' => 0,
                    'created_at' => '',
                ];
            }

            $combinedArr[$supply->supplier]['supplier'] = $supply->supplier()->first();
            $combinedArr[$supply->supplier]['phys_weight'] += $supply->phys_weight;
            $combinedArr[$supply->supplier]['fat'] = $supply->fat;
            $combinedArr[$supply->supplier]['acid'] = $supply->acid;
            $combinedArr[$supply->supplier]['density'] = $supply->density;
            $combinedArr[$supply->supplier]['basic_weight'] += $supply->basic_weight;
            $combinedArr[$supply->supplier]['fat_kilo'] += $supply->fat_kilo;
            $combinedArr[$supply->supplier]['price'] = $supply->price;
            $combinedArr[$supply->supplier]['sum'] += $supply->sum;
            $combinedArr[$supply->supplier]['created_at'] = $supply->created_at;
        }

        $combined = [];
        foreach ($combinedArr as $item) {
            $combined[] = $item;
        }

        return $combined;
    }
}
