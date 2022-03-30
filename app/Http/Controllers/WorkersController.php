<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Worker;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;

class WorkersController extends Controller
{
    public function index() {
        $workers = Worker::orderBy('name')->get();

        return Inertia::render('Workers/Index',[
            'workers' => $workers
        ]);
    }

    public function store(Request $request){
      
        $worker = new Worker();
        $worker->name = $request->name;
        $worker->surname = $request->surname;     
        $worker->salary = $request->salary;
        $worker->saldo = 0;
      
        $worker->save();

        return Redirect::route('workers')->with('успешно', 'Сотрудник добавлен.');
    }

    public function destroy(Request $request) {
        $toBeDeleted = Worker::find($request->worker['id']);

        if ($toBeDeleted == null) {
            return 0;
        }

        $toBeDeleted->delete();

        return 1;
    }
}
