<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Supplier;
use App\Models\Supply;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;

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

    public function store(Request $request){
      
        $supplier = new Supplier();
        $supplier->name = $request->name;
        $supplier->tel = $request->tel;
        $supplier->address = $request->address;

      
        $supplier->save();

        return Redirect::route('supp')->with('успешно', 'Поставщик добавлен.');
    }

    public function update(Request $request){

        $supplier = Supplier::find($request->id);
        $supplier->name = $request->name;
        $supplier->tel = $request->tel;
        $supplier->address = $request->address;

      
        $supplier->save();

        return Redirect::route('supp')->with('успешно', 'Поставщик добавлен.');
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

        if ($request->supplier) {
            $supply = Supply::where('supplier',$request->supplier)->whereBetween('created_at',[$from, $to])->with('supplier')->get();
            $sum = Supply::where('supplier',$request->supplier)->whereBetween('created_at',[$from, $to])->sum('sum');
            $fat_kilo = Supply::where('supplier',$request->supplier)->whereBetween('created_at',[$from, $to])->sum('fat_kilo');
            $basic_weight = Supply::where('supplier',$request->supplier)->whereBetween('created_at',[$from, $to])->sum('basic_weight');
            $phys_weight = Supply::where('supplier',$request->supplier)->whereBetween('created_at',[$from, $to])->sum('phys_weight');
        } else if ($request->to) {
            $supply = Supply::whereBetween('created_at',[$from, $to])->with('supplier')->get();
            $sum = Supply::whereBetween('created_at',[$from, $to])->sum('sum');
            $fat_kilo = Supply::whereBetween('created_at',[$from, $to])->sum('fat_kilo');
            $basic_weight = Supply::whereBetween('created_at',[$from, $to])->sum('basic_weight');
            $phys_weight = Supply::whereBetween('created_at',[$from, $to])->sum('phys_weight');
        } else {
            $supply = Supply::whereDate('created_at', Carbon::today())->with('supplier')->get();
            $sum = Supply::whereDate('created_at', Carbon::today())->sum('sum');
            $fat_kilo = Supply::whereDate('created_at', Carbon::today())->sum('fat_kilo');
            $basic_weight = Supply::whereDate('created_at', Carbon::today())->sum('basic_weight');
            $phys_weight = Supply::whereDate('created_at', Carbon::today())->sum('phys_weight');
        }

        return [
            'mysupplies' => $supply,
            'phys_weight' => $phys_weight,
            'basic_weight' => number_format((float)$basic_weight, 2, '.', ''),
            'fat_kilo' => number_format((float)$fat_kilo, 2, '.', ''),
            'sum' => $sum
        ];
    }

    public function getSuppliesByMonth(Request $request){
        $supply = Supply::whereYear('created_at',$request->year)->whereMonth('created_at',$request->month)->with('supplier')->get();
        $sum = Supply::whereYear('created_at',$request->year)->whereMonth('created_at',$request->month)->sum('sum');
        $fat_kilo = Supply::whereYear('created_at',$request->year)->whereMonth('created_at',$request->month)->sum('fat_kilo');
        $basic_weight = Supply::whereYear('created_at',$request->year)->whereMonth('created_at',$request->month)->sum('basic_weight');
        $phys_weight = Supply::whereYear('created_at',$request->year)->whereMonth('created_at',$request->month)->sum('phys_weight');
        //dd($supply);
        return [
            'mysupplies' => $supply,
            'phys_weight' => $phys_weight,
            'basic_weight' => number_format((float)$basic_weight, 2, '.', ''),
            'fat_kilo' => number_format((float)$fat_kilo, 2, '.', ''),
            'sum' => $sum
        ];
    }

    public function getSuppliesByYear(Request $request){
        $myyear = $request->year;
        $month = $request->month + 1;
        $supply = Supply::whereYear('created_at',$myyear)->whereMonth('created_at',$month)->with('supplier')->get();
        $sum = Supply::whereYear('created_at',$myyear)->whereMonth('created_at',$month)->sum('sum');
        $fat_kilo = Supply::whereYear('created_at',$myyear)->whereMonth('created_at',$month)->sum('fat_kilo');
        $basic_weight = Supply::whereYear('created_at',$myyear)->whereMonth('created_at',$month)->sum('basic_weight');
        $phys_weight = Supply::whereYear('created_at',$myyear)->whereMonth('created_at',$month)->sum('phys_weight');
        //dd($supply);
        return [
            'mysupplies' => $supply,
            'phys_weight' => $phys_weight,
            'basic_weight' => number_format((float)$basic_weight, 2, '.', ''),
            'fat_kilo' => number_format((float)$fat_kilo, 2, '.', ''),
            'sum' => $sum
        ];
    }

    public function getSuppliesBySupplier(Request $request){
        $supply = Supply::where('supplier',$request->supplier)->whereYear('created_at',Carbon::now()->year)->whereMonth('created_at',$request->month)->with('supplier')->get();
        $sum = Supply::where('supplier',$request->supplier)->whereYear('created_at',Carbon::now()->year)->whereMonth('created_at',$request->month)->sum('sum');
        $fat_kilo = Supply::where('supplier',$request->supplier)->whereYear('created_at',Carbon::now()->year)->whereMonth('created_at',$request->month)->sum('fat_kilo');
        $basic_weight = Supply::where('supplier',$request->supplier)->whereYear('created_at',Carbon::now()->year)->whereMonth('created_at',$request->month)->sum('basic_weight');
        $phys_weight = Supply::where('supplier',$request->supplier)->whereYear('created_at',Carbon::now()->year)->whereMonth('created_at',$request->month)->sum('phys_weight');

        return [
            'mysupplies' => $supply,
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

        $supplier = Supplier::find($request->supply['supplier'])->first();
        // dd($supplier->name);
        
        $expense = Expense::
            where('user', $supplier->name)
            ->where('sum', $request->supply['sum'])
            ->orderBy('created_at', 'DESC')->first();

        if ($expense != null) {
            $expense->delete();
        }

        $toBeDeleted->delete();

        return 1;
    }
}
