<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class ListController extends Controller
{
    public function index()
    {
        return Inertia::render('List/Index');
    }
}
