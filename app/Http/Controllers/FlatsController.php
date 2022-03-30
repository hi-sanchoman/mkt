<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class FlatsController extends Controller
{
    public function index()
    {
        return Inertia::render('Flats/Index');
    }
}
