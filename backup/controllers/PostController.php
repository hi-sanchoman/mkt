<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;


class PostController extends Controller{

	public function index(){
		return Inertia::render('Post/Index');
	}
}
?>