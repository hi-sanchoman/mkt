<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class MeetingsController extends Controller
{
    public function index()
    {
        return Inertia::render('Meetings/Index');
    }
}
