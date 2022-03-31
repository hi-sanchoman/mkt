<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Tara;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;

class TaraController extends Controller
{
	public function index(){
		$tara = Tara::all();

		return Inertia::render('Dashboard/Index',[
			'tara' => $tara
		]);
	}

}