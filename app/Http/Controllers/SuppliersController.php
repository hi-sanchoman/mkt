<?php

namespace App\Http\Controllers;

use App\Models\Conversion;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Supplier;
use App\Models\Supply;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SuppliersController extends Controller
{
    public function index()
    {   	
        $supplies = Supplier::all(); 
        foreach ($supplies as $key => $supply) {
            # code...
            $count = count(Supply::where('supplier',$supply->id)->get());
            $sum = Supply::where('supplier', $supply->id)->sum('sum');
            $supply->setAttribute('count', $count);   
            $supply->setAttribute('sum', $sum);
        } 
        return Inertia::render('List/Suppliers',[
            'suppliers' => $supplies
        ]);
    }

    public function destroy(Request $request) {
        $toBeDeleted = Supplier::find($request->supplier['id']);

        if ($toBeDeleted == null) {
            return 0;
        }

        $toBeDeleted->delete();

        return 1;
    }

    public function store(Request $request){
      
        $supplier = new Supplier();
        $supplier->name = $request->name;
        $supplier->tel = $request->tel;
        $supplier->address = $request->address;

      
        $supplier->save();

        return Redirect::route('supp')->with('успешно', 'Поставщик добавлен.');
    }

    public function update(Request $request) {

        $supplier = Supplier::findOrFail($request->id);
        $supplier->name = $request->form['name'];
        $supplier->tel = $request->form['tel'];
        $supplier->address = $request->form['address'];
      
        $supplier->save();

        // return updated
        $suppliers = Supplier::all(); 
        foreach ($suppliers as $key => $supply) {
            # code...
            $count = count(Supply::where('supplier',$supply->id)->get());
            $sum = Supply::where('supplier', $supply->id)->sum('sum');
            $supply->setAttribute('count', $count);   
            $supply->setAttribute('sum', $sum);
        }

        return ['suppliers' => $suppliers];
    }

    public function create(){
        return Inertia::render('Suppliers/Create');
    }

    public function history(Request $request){

        $supply = Supply::where('supplier',$request->supplier)->whereMonth('created_at', Carbon::now()->month)->get();
        $day = date("d");


        return [ 
            'supply' => $supply,
            'day' => $day
            ];
    }

    public function bydate(Request $request) {
        $from = null;
        $to = null;

        if ($request->has('from'))
            $from = Carbon::createFromFormat('m/d/Y H:i:s',  $request->from);

        if ($request->has('to'))
            $to = Carbon::createFromFormat('m/d/Y H:i:s',  $request->to); 
        
        // dd([Carbon::today()]);

        $combined = [];

        if ($request->supplier) {
            $supplies = [];

            if (!$request->has('to')) {
                $supplies = Supply::where('supplier',$request->supplier)->whereDate('created_at', $from)->with('supplier')->orderBy('created_at')->get();
                $sum = Supply::where('supplier',$request->supplier)->whereDate('created_at',$from)->sum('sum');
                $fat_kilo = Supply::where('supplier',$request->supplier)->whereDate('created_at',$from)->sum('fat_kilo');
                $basic_weight = Supply::where('supplier',$request->supplier)->whereDate('created_at',$from)->sum('basic_weight');
                $phys_weight = Supply::where('supplier',$request->supplier)->whereDate('created_at',$from)->sum('phys_weight');
            } else {
                $supplies = Supply::where('supplier',$request->supplier)->whereBetween('created_at',[$from, $to])->with('supplier')->orderBy('created_at')->get();
                $sum = Supply::where('supplier',$request->supplier)->whereBetween('created_at',[$from, $to])->sum('sum');
                $fat_kilo = Supply::where('supplier',$request->supplier)->whereBetween('created_at',[$from, $to])->sum('fat_kilo');
                $basic_weight = Supply::where('supplier',$request->supplier)->whereBetween('created_at',[$from, $to])->sum('basic_weight');
                $phys_weight = Supply::where('supplier',$request->supplier)->whereBetween('created_at',[$from, $to])->sum('phys_weight');
            }
            
            // $combined = $this->_getCombined($supplies);

            
        } else if ($request->to) {
            $supplies = Supply::whereBetween('created_at',[$from, $to])->with('supplier')->orderBy('created_at')->get();
            
            $combined = $this->_getCombined($supplies);

            $sum = Supply::whereBetween('created_at',[$from, $to])->sum('sum');
            $fat_kilo = Supply::whereBetween('created_at',[$from, $to])->sum('fat_kilo');
            $basic_weight = Supply::whereBetween('created_at',[$from, $to])->sum('basic_weight');
            $phys_weight = Supply::whereBetween('created_at',[$from, $to])->sum('phys_weight');
        } else {
            $supplies = Supply::whereDate('created_at', Carbon::today())->with('supplier')->orderBy('created_at')->get();
            
            $combined = $this->_getCombined($supplies);

            $sum = Supply::whereDate('created_at', Carbon::today())->sum('sum');
            $fat_kilo = Supply::whereDate('created_at', Carbon::today())->sum('fat_kilo');
            $basic_weight = Supply::whereDate('created_at', Carbon::today())->sum('basic_weight');
            $phys_weight = Supply::whereDate('created_at', Carbon::today())->sum('phys_weight');
        }

        return [
            'mysupplies' => $supplies,
            'combined' => $combined,
            'phys_weight' => $phys_weight,
            'basic_weight' => number_format((float)$basic_weight, 2, '.', ''),
            'fat_kilo' => number_format((float)$fat_kilo, 2, '.', ''),
            'sum' => $sum
        ];
    }

    
    public function getSuppliesByMonth(Request $request){
        $supplies = Supply::whereYear('created_at',$request->year)->whereMonth('created_at',$request->month)->with('supplier')->get();
        
        $combined = $this->_getCombined($supplies);

        $sum = Supply::whereYear('created_at',$request->year)->whereMonth('created_at',$request->month)->sum('sum');
        $fat_kilo = Supply::whereYear('created_at',$request->year)->whereMonth('created_at',$request->month)->sum('fat_kilo');
        $basic_weight = Supply::whereYear('created_at',$request->year)->whereMonth('created_at',$request->month)->sum('basic_weight');
        $phys_weight = Supply::whereYear('created_at',$request->year)->whereMonth('created_at',$request->month)->sum('phys_weight');
        //dd($supply);
        return [
            'mysupplies' => $supplies,
            'combined' => $combined,
            'phys_weight' => $phys_weight,
            'basic_weight' => number_format((float)$basic_weight, 2, '.', ''),
            'fat_kilo' => number_format((float)$fat_kilo, 2, '.', ''),
            'sum' => $sum
        ];
    }

    public function getSuppliesByYear(Request $request){
        $myyear = $request->year;
        $month = $request->month + 1;

        $supplies = Supply::whereYear('created_at',$myyear)->whereMonth('created_at',$month)->with('supplier')->get();
        $combined = $this->_getCombined($supplies);

        $sum = Supply::whereYear('created_at',$myyear)->whereMonth('created_at',$month)->sum('sum');
        $fat_kilo = Supply::whereYear('created_at',$myyear)->whereMonth('created_at',$month)->sum('fat_kilo');
        $basic_weight = Supply::whereYear('created_at',$myyear)->whereMonth('created_at',$month)->sum('basic_weight');
        $phys_weight = Supply::whereYear('created_at',$myyear)->whereMonth('created_at',$month)->sum('phys_weight');
        //dd($supply);
        return [
            'mysupplies' => $supplies,
            'combined' => $combined,
            'phys_weight' => $phys_weight,
            'basic_weight' => number_format((float)$basic_weight, 2, '.', ''),
            'fat_kilo' => number_format((float)$fat_kilo, 2, '.', ''),
            'sum' => $sum
        ];
    }

    public function getSuppliesBySupplier(Request $request){
        $supplies = Supply::where('supplier',$request->supplier)->whereYear('created_at',Carbon::now()->year)->whereMonth('created_at',$request->month)->with('supplier')->get();
        
        $combined = $this->_getCombined($supplies);

        $sum = Supply::where('supplier',$request->supplier)->whereYear('created_at',Carbon::now()->year)->whereMonth('created_at',$request->month)->sum('sum');
        $fat_kilo = Supply::where('supplier',$request->supplier)->whereYear('created_at',Carbon::now()->year)->whereMonth('created_at',$request->month)->sum('fat_kilo');
        $basic_weight = Supply::where('supplier',$request->supplier)->whereYear('created_at',Carbon::now()->year)->whereMonth('created_at',$request->month)->sum('basic_weight');
        $phys_weight = Supply::where('supplier',$request->supplier)->whereYear('created_at',Carbon::now()->year)->whereMonth('created_at',$request->month)->sum('phys_weight');

        return [
            'mysupplies' => $supplies,
            'combined' => $combined,
            'phys_weight' => $phys_weight,
            'basic_weight' => number_format((float)$basic_weight, 2, '.', ''),
            'fat_kilo' => number_format((float)$fat_kilo, 2, '.', ''),
            'sum' => $sum
        ];
    }

    public function deletePostavka(Request $request) {

        $toBeDeleted = Supply::find($request->supply['id']);

        if ($toBeDeleted == null) {
            return 0;
        }

        DB::beginTransaction();

        $supplier = Supplier::find($request->supply['supplier'])->first();
        // dd($supplier->name);
        
        // expense
        $expense = Expense::
            where('user', $supplier->name)
            ->where('sum', $request->supply['sum'])
            ->orderBy('created_at', 'DESC')->first();

        if ($expense != null) {
            $expense->delete();
        }

        $toBeDeleted->delete();

        // re-calculate milk base, etc.
        $empty = true;
        
        $myconversions = Conversion::whereDate('created_at', $toBeDeleted->created_at);
        foreach ($myconversions as $key => $value) {
            # code...
            if ($value->assortment == 1 || $value->assortment == 2 || $value->assortment == 3) {
                $empty = false;
                break;
            }
        }

        $supplies = Supply::whereDate('created_at',  $toBeDeleted->created_at)->get();

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

        if ($empty) {
            $phys_weight = new Conversion();
            $phys_weight->assortment = 1;
            $phys_weight->kg = $moloko_total['phys'];
            $phys_weight->created_at = $request->type != 1 ? Carbon::today() : Carbon::tomorrow();
            $phys_weight->save();

            $basic_weight = new Conversion();
            $basic_weight->assortment = 2;
            $basic_weight->kg = $moloko_total['basic'];
            $basic_weight->created_at = $request->type != 1 ? Carbon::today() : Carbon::tomorrow();
            $basic_weight->save();

            $fat_kilo = new Conversion();
            $fat_kilo->assortment = 3;
            $fat_kilo->kg = $moloko_total['fat'];
            $fat_kilo->created_at = $request->type != 1 ? Carbon::today() : Carbon::tomorrow();
            $fat_kilo->save();

        } else {
            $phys_weight = Conversion::where('assortment', 1)->orderBy('id','DESC')->first();
            $phys_weight->assortment = 1;
            $phys_weight->kg = $moloko_total['phys'];
            $phys_weight->save();

            $basic_weight = Conversion::where('assortment', 2)->orderBy('id','DESC')->first();
            $basic_weight->assortment = 2;
            $basic_weight->kg = $moloko_total['basic'];
            $basic_weight->save();

            $fat_kilo = Conversion::where('assortment', 3)->orderBy('id','DESC')->first();
            $fat_kilo->assortment = 3;
            $fat_kilo->kg = $moloko_total['fat'];
            $fat_kilo->save();
        }

        DB::commit();

        return 1;
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
