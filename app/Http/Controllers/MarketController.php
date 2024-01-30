<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Branch;
use App\Models\Market;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MarketController extends Controller
{
    public function index()
    {   	
        $branches = Branch::with('city')->get();
        $cities = City::get();
        return Inertia::render('Markets/Index',[
            'branches' => $branches,
            'cities' => $cities
        ]);
    }

    public function create(Request $request) {
        $branch = Branch::create([
            'name' => $request->name,
            'city_id' => $request->city_id,
            'market_id' => 1
        ]);

        return Inertia::render('Markets/Index');
    }

    public function update(Request $request) {
        $branch = Branch::findOrFail($request->id);
        $branch->name = $request->form['name'];
        $branch->city_id = $request->form['city_id'];
        $branch->save();

        return true;
    }

    public function delete(Request $request) {
        $b = Branch::find($request->branch['id']);
        if (!$b) return 0;
        $b->delete();
        return 1;
    }
}
