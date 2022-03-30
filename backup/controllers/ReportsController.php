<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Report;
use App\Models\User;
use App\Models\Deal;
use App\Models\Organization;
use DB;

class ReportsController extends Controller
{
    public function index()
    {   
        return Inertia::render('Reports/Index',[
        	'reports' => Report::with('user')->get(),
        	'deals' => Deal::whereMonth('created_at', date('m'))->get()->count(),
        	'sum' => Deal::whereMonth('created_at', date('m'))->get()->sum('sum'),
        	'users' => User::select(DB::raw("CONCAT(last_name, ' ', first_name)  AS label"), 'id as code')->where('account_id', 1)->get(),
        	'organizations' => Organization::whereMonth('created_at', date('m'))->get()->count(),
        ]);
    }

    public function analytics()
    {
    	return Inertia::render('Reports/Analytics',[
    		'users' => User::all(),
    	]);
    }

    public function create()
    {
    	return Inertia::render('Reports/Create');
    }
}
