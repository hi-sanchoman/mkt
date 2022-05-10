<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Percent;
use App\Models\PercentStorePivot;
use App\Models\Store;
use DB;
use Illuminate\Support\Facades\Redirect;

class PercentController extends Controller
{
    public function index()
    {
        $percents = Percent::orderBy('amount')->get();
        // dd($percents->toArray());

        return Inertia::render('Percents/Index', [
            'percents' => $percents,
        ]);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();    

        $percent = new Percent();
        $percent->amount = $request->form['amount'];
        $percent->save();

        // create pivot tables
        $stores = Store::orderBy('num')->get();
        
        foreach ($stores as $store) {
            // $percent->items()->attach($store);

            PercentStorePivot::create([
                'percent_id' => $percent->id,
                'store_id' => $store->id,
                'price' => 0,
            ]);
        }

        DB::commit();

        return 1;
        // return Redirect::route('percents')->with('успешно', 'Процент добавлен.');
    }

    public function destroy(Request $request) {
        $toBeDeleted = Percent::find($request->percent['id']);

        if ($toBeDeleted == null) {
            return 0;
        }

        // delete all pivot records

        $toBeDeleted->delete();

        return 1;
    }

    public function update(Request $request, $id) {
        // dd($request->all());

        $percent = Percent::findOrFail($id);

        $percent->amount = $request->form['amount'];
        $percent->save();

        $percents = Percent::orderBy('amount')->get();
        
        return ['percents' => $percents];
    }
}
