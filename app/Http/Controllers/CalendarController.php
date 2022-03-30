<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use App\Models\Task;
use App\Models\User;
use App\Models\Subtask;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function calendar()
    {
        return Inertia::render('Calendar/Index', [
            'event' => Subtask::getTasks()
        ]);
    }
}
