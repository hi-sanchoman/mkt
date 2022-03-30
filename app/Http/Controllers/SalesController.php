<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;

/**
 * 
 */
class SalesController extends Controller
{
		
	public function index(){

		$realizators = User::where('position_id','3')->get();


		

		return Inertia::render('Sales/Index',[
			'realizators' => $realizators,
		]);
	}
}