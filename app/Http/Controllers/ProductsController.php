<?php

namespace App\Http\Controllers;

use App\models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ProductsController extends Controller
{
    //
	public function index(){
		return Inertia::render('Products/Index', [
			'filters' => Request::all('search', 'trashed'),
            'products' => Product::all(),
            'categories' => Category::pluck('category'),//['John','Percival','Lemuel'],
        ]);
	}

}
